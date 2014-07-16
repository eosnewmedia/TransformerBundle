<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\Exceptions\InvalidArgumentException;
use ENM\TransformerBundle\Exceptions\InvalidParameterException;
use ENM\TransformerBundle\Exceptions\MissingRequiredConfigArgumentException;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Validator\Validation;

abstract class BaseTransformerManager extends BaseValidationManager
{

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
      $this->setValue($returnClass, $key, $result, $settings);
    }

    return $returnClass;
  }



  protected function setValue($returnClass, $key, $value, array $settings)
  {
    $this->validateRequired($key, $value, $settings);
    // Überprüfen, ob Setter vorhanden sind
    if (method_exists($returnClass, 'set' . $key))
    {
      // Ruft die Setter der ReturnClass auf
      $returnClass->{'set' . $key}($value);
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
   * @return mixed|Object|bool|null
   * @throws \ENM\TransformerBundle\Exceptions\InvalidArgumentException
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
    if (count($settings['children']) > 0)
    {
      return $this->prepareNested($settings, $key, $params[$key]);
    }

    // externe Methode
    if ($settings['methodClass'] !== null)
    {
      return $this->useExternalValidation($settings, $params[$key]);
    }

    // Standardtypen
    return $this->prepareNonComplex($params[$key], $settings);
  }



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
        throw new MissingRequiredConfigArgumentException('Dynamic is undefined!');
      }

      return $this->createClass($settings['options']['returnClass'], $settings['children'], $value);
    }
    throw new InvalidArgumentException('"' . $key . '" have to be an array. ' . gettype($value) . ' given!');
  }



  /**
   * @param array $settings
   * @param mixed $value
   *
   * @return mixed
   * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
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

      throw new InvalidConfigurationException(sprintf(
        'Method %s of class %s does not exist.',
        array(
          $settings['method'],
          $settings['methodClass']
        )
      ));
    }

    throw new InvalidConfigurationException(sprintf('Class %s does not exist.', $settings['methodClass']));
  }



  /**
   * Diese Funktion verarbeitet nicht-komplexe Datentypen
   *
   * @param mixed $value
   * @param array $settings
   *
   * @return array|bool|string
   * @throws \ENM\TransformerBundle\Exceptions\InvalidParameterException
   */
  protected function prepareNonComplex($value, array $settings = array())
  {
    $constrains = $this->createValidationConstrains($settings);
    $validator  = Validation::createValidator();
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
    throw new InvalidParameterException($violationList);
  }



  /**
   * @param array  $settings
   * @param string $key
   * @param mixed  $value
   *
   * @throws \ENM\TransformerBundle\Exceptions\InvalidArgumentException
   */
  protected function prepareDate(array $settings = array(), $key, $value)
  {
    $options = $settings['options']['date'];
    $date    = \DateTime::createFromFormat($options['format'], $value);
    if (!$date instanceof \DateTime)
    {
      throw new InvalidArgumentException($key . ' is not a date string of format "' . $options['format'] . '"');
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
   * @throws \ENM\TransformerBundle\Exceptions\InvalidArgumentException
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
        throw new InvalidArgumentException('Item of Collection has to be an array. ' . gettype($value) . ' given!');
      }

      array_push($collection_array, $this->createClass($options['returnClass'], $config, $value));
    }

    return $collection_array;
  }



  /**
   * Converts an Object to an Array
   *
   * @param $object
   *
   * @return array
   */
  public function objectToArray($object)
  {
    if (!$object instanceof \stdClass)
    {
      $object = new \ReflectionObject($object);
      $object = $object->getDefaultProperties();
    }

    $array = array();

    foreach ($object as $key => $value)
    {
      if (is_object($value))
      {
        $array[$key] = $this->objectToArray($value);
      }
      else
      {
        $array[$key] = $value;
      }
    }

    return $array;
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
    throw new \Exception(sprintf('Class %s does not exist.'));
  }
}