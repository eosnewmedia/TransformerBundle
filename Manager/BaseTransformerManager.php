<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException;
use ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Validation;

abstract class BaseTransformerManager extends BaseValidationManager
{


  public function __construct(Container $container)
  {
    parent::__construct($container);
  }



  /**
   * @param object|string $returnClass
   * @param array         $config
   * @param array         $params
   *
   * @return object
   */
  protected function createClass($returnClass, array $config, array $params = array())
  {
    $returnClass = $this->validateReturnClass($returnClass);

    $config = $this->validateConfiguration($config);

    $params = array_change_key_case($params, CASE_LOWER);

    foreach ($config as $key => $settings) // config-Array mit den erwarteten Werten durchlaufen
    {
      // Standard-Wert auf NULL setzen
      $result = null;
      // prüfen, ob ein passender Eintrag im Array vorhanden ist.
      if (array_key_exists(strtolower($key), $params))
      {
        // Value validieren und wenn nötig verarbeiten
        $result = $this->prepare(strtolower($key), $params, $settings);
      }
      // anderer Key ("renameTo" aus der Konfiguration)
      elseif (array_key_exists(strtolower($settings['renameTo']), $params))
      {
        // Value validieren und wenn nötig verarbeiten
        $result = $this->prepare(strtolower($settings['renameTo']), $params, $settings);
      }
      $this->setValue($returnClass, $key, $result, $settings);
    }

    return $returnClass;
  }



  /**
   * @param array  $config
   * @param object $object
   *
   * @return \stdClass
   */
  protected function reverseClass(array $config, $object)
  {
    $returnClass = new \stdClass();

    $object = $this->createClass(new \stdClass(), $config, $this->objectToArray($object));
    $config = $this->validateConfiguration($config);
    foreach ($config as $key => $settings) // config-Array mit den erwarteten Werten durchlaufen
    {
      if (array_key_exists('renameTo', $settings) && $settings['renameTo'] !== null)
      {
        $temp                 = $key;
        $key                  = $settings['renameTo'];
        $settings['renameTo'] = $temp;
      }
      $this->setValue($returnClass, $key, $object->$key, $settings, false);
    }

    return $returnClass;
  }



  /**
   * @param object $returnClass
   * @param string $key
   * @param mixed  $value
   * @param array  $settings
   */
  protected function setValue($returnClass, $key, $value, array $settings, $reverse = false)
  {
    $value = $this->validateRequired($key, $value, $settings);

    // Anderer Name im Objekt?
    if ($settings['renameTo'] !== null && $reverse === false)
    {
      $key = $settings['renameTo'];
    }

    $pieces = explode('_', $key);
    $setter = 'set';
    if (count($pieces) > 0)
    {
      foreach ($pieces as $piece)
      {
        $setter .= ucfirst($piece);
      }
    }
    else
    {
      $setter .= ucfirst($key);
    }

    // Überprüfen, ob Setter vorhanden sind
    if (method_exists($returnClass, $setter))
    {
      // Ruft die Setter der ReturnClass auf
      $returnClass->{$setter}($value);
    }
    else
    {
      // setzt den Property Wert
      $returnClass->$key = $value;
    }
  }



  /**
   * @param string $key
   * @param array  $params
   * @param array  $settings
   *
   * @return array|bool|\DateTime|mixed|null|object|string
   */
  protected function prepare($key, array $params = array(), array $settings = array())
  {
    // NULL-Werte dierekt zurückgeben
    if (is_null($params[$key]))
    {
      return null;
    }

    // Datum
    if ($settings['type'] === 'date')
    {
      return $this->prepareDate($settings, $key, $params[$key]);
    }

    // verschachteltes Objekt
    if (count($settings['children']) > 0 || in_array($settings['type'], array('object', 'collection')))
    {
      return $this->prepareNested($settings, $key, $params[$key]);
    }

    // externe Methode
    if ($settings['methodClass'] !== null || $settings['type'] === 'method')
    {
      return $this->useExternalValidation($settings, $params[$key]);
    }

    // Standardtypen
    return $this->prepareNonComplex($params[$key], $settings);
  }



  /**
   * @param array  $settings
   * @param string $key
   * @param mixed  $value
   *
   * @return array|object
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  protected function prepareNested(array $settings, $key, $value)
  {
    if (is_array($value))
    {
      if ($settings['type'] === 'collection')
      {
        if (array_key_exists('dynamic', $settings['children']) && is_array($settings['children']['dynamic']))
        {
          return $this->prepareCollection($settings['options'], $settings['children']['dynamic'], $value);
        }
        throw new InvalidTransformerConfigurationException('Dynamic is undefined!');
      }

      return $this->createClass($settings['options']['returnClass'], $settings['children'], $value);
    }
    throw new InvalidTransformerParameterException('"' . $key . '" have to be an array. ' . gettype($value)
                                                   . ' given!');
  }



  /**
   * @param array $settings
   * @param mixed $value
   *
   * @return mixed
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException
   */
  protected function useExternalValidation(array $settings, $value)
  {
    if (class_exists($settings['methodClass']))
    {
      $class = new $settings['methodClass']();
      if (method_exists($class, $settings['method']))
      {
        return $class->{$settings['method']}($value);
      }

      throw new InvalidTransformerConfigurationException(sprintf(
        'Method %s of class %s does not exist.',
        array(
          $settings['method'],
          $settings['methodClass']
        )
      ));
    }

    throw new InvalidTransformerConfigurationException(sprintf('Class %s does not exist.', $settings['methodClass']));
  }



  /**
   * Diese Funktion verarbeitet nicht-komplexe Datentypen
   *
   * @param mixed $value
   * @param array $settings
   *
   * @return array|bool|float|int|string
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  protected function prepareNonComplex($value, array $settings = array())
  {
    $constrains = $this->createValidationConstrains($settings);
    $validator  = $this->container->get('validator');
    $value      = $this->normalizeType($value, $settings);
    try
    {
      $violationList = $validator->validateValue($value, $constrains);
    }
    catch (\Exception $e)
    {
      $violationList = $this->handleException($e, $value);
    }

    if ($violationList->count() === 0)
    {
      return $value;
    }
    throw new InvalidTransformerParameterException($violationList);
  }



  protected function createDateFromArray($key, array $array, $format)
  {
    if (array_key_exists('date', $array))
    {
      try
      {
        $date = new \DateTime($array['date']);

        return $date->format($format);
      }
      catch (\Exception $e)
      {
      }
    }
    throw new InvalidTransformerParameterException(
      $key . ' is not a date string of format "' . $format . '"');
  }



  /**
   * @param array  $settings
   * @param string $key
   * @param mixed  $value
   *
   * @return \DateTime|string
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  protected function prepareDate(array $settings = array(), $key, $value)
  {
    $options = $settings['options']['date'];

    if (is_array($value))
    {
      $value = $this->createDateFromArray($key, $value, $options['format']);
    }

    $date = \DateTime::createFromFormat($options['format'], $value);

    if (!$date instanceof \DateTime)
    {
      throw new InvalidTransformerParameterException($key . ' is not a date string of format "' . $options['format']
                                                     . '"');
    }

    if ($options['convertToObject'] === true)
    {
      $value = $date;
    }
    elseif ($options['convertToFormat'] !== null)
    {
      $value = $date->format($options['convertToFormat']);
    }

    return $value;
  }



  /**
   * @param array $options
   * @param array $config
   * @param array $parameter
   *
   * @return array
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  protected function prepareCollection(array $options = array(), array $config = array(), array $values = array())
  {
    $collection_array = array();

    foreach ($values as $value)
    {
      if (is_object($value))
      {
        $value = $this->objectToArray($value);
      }
      if (!is_array($value))
      {
        throw new InvalidTransformerParameterException('Item of Collection has to be an array. ' . gettype($value)
                                                       . ' given!');
      }

      array_push($collection_array, $this->createClass($options['returnClass'], $config, $value));
    }

    return $collection_array;
  }



  /**
   * Converts an Object to an Array
   *
   * @param object|array $object
   *
   * @return array
   */
  public function objectToArray($input)
  {
    // Rückgabe Array erstellen
    $final = array();
    // Object in Array umwandeln
    $array = (array) $input;

    foreach ($array as $key => $value)
    {
      // Protected und Private Properties des Objektes im Array erreichbar machen
      if (is_object($input))
      {
        // PHP Benennungen vom Konvertieren Rückgängigmachen
        $key = str_replace("\0*\0", '', $key);
        $key = str_replace("\0" . get_class($input) . "\0", '', $key);

        // Die Reflection-Klasse wird an dieser Stelle benötigt, da PHP Integer-Werte aus dem Objekt nicht in das Array übernimmts
        $reflectionClass = new \ReflectionClass(get_class($input));
        if ($reflectionClass->hasProperty($key))
        {
          $property = $reflectionClass->getProperty($key);
          $property->setAccessible(true);
          $value = $property->getValue($input);
        }
      }
      // Tiefer verschachtelt?
      if (is_object($value) || is_array($value))
      {
        $value = $this->objectToArray($value);
      }
      // Wert ins Array setzen
      $final[$key] = $value;
    }

    return $final;
  }



  /**
   * @param object|string $returnClass
   *
   * @return object
   * @throws \Exception
   */
  protected function validateReturnClass($returnClass)
  {
    if (is_object($returnClass))
    {
      return $returnClass;
    }

    if (class_exists($returnClass))
    {
      return new $returnClass();
    }
    throw new InvalidTransformerConfigurationException(sprintf('Class %s does not exist.'));
  }
}