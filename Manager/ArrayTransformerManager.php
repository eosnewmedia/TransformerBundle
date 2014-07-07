<?php
/**
 * ArrayArrayTransformerManager.php
 * Date: 13.06.14
 *
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\DependencyInjection\TransformerConfiguration;
use ENM\TransformerBundle\Interfaces\ArrayTransformerManagerInterface;
use ENM\TransformerBundle\Exceptions\InvalidArgumentException;
use ENM\TransformerBundle\Exceptions\InvalidParameterException;
use ENM\TransformerBundle\Exceptions\MissingRequiredArgumentException;
use ENM\TransformerBundle\Exceptions\MissingRequiredConfigArgumentException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;

class ArrayTransformerManager implements ArrayTransformerManagerInterface
{

  /**
   * @param object $returnClass
   * @param array  $config
   * @param array  $params
   *
   * @return object
   */
  public function transform($returnClass, array $config, array $params = array())
  {
    $this->validateConfiguration($config);

    foreach ($config as $key => $settings) // config-Array mit den erwarteten Werten durchlaufen
    {
      // Standard-Wert auf NULL setzen
      $result = null;
      // prüfen, ob ein passender Eintrag im Array vorhanden ist.
      if (array_key_exists($key, $params))
      {
        // Value validieren und wenn nötig verarbeiten
        $result = $this->prepare($key, $params, $settings);

        // Überprüfen, ob Setter vorhanden sind
        if (method_exists($returnClass, 'set' . $key))
        {
          // Ruft die Setter der ReturnClass auf
          $returnClass->{'set' . $key}($result);
        }
        else
        {
          // setzt den Property Wert
          $returnClass->$key = $result;
        }
      }

      // prüfen, ob der Wert vorhanden ist, wenn er benötigt wird
      $this->validateRequired($key, $returnClass->$key, $settings);
    }

    return $returnClass;
  }



  /**
   * @param array $config
   */
  protected function validateConfiguration(array $config = array())
  {
    $processor = new Processor();
    $processor->processConfiguration(new TransformerConfiguration(), array('config' => $config));
  }



  /**
   * @param string $key
   * @param mixed  $value
   * @param array  $settings
   *
   * @throws \ENM\TransformerBundle\Exceptions\MissingRequiredArgumentException
   */
  protected function validateRequired($key, $value, array $settings = array())
  {
    if ($this->canBeNull($settings) === false && is_null($value))
    {
      throw new MissingRequiredArgumentException('Required parameter "' . $key . '" is missing.');
    }
  }



  /**
   * @param array $settings
   *
   * @return bool
   */
  protected function canBeNull(array $settings = array())
  {
    if (!array_key_exists('options', $settings))
    {
      return true;
    }
    if (!array_key_exists('required', $settings['options']))
    {
      return true;
    }
    if ($settings['options']['required'] === false)
    {
      return true;
    }

    return false;
  }



  /**
   * @param string $key
   * @param array  $params
   * @param array  $settings
   *
   * @return mixed|Object|bool|null
   * @throws \ENM\TransformerBundle\Exceptions\InvalidArgumentException
   * @throws \ENM\TransformerBundle\Exceptions\MissingRequiredConfigArgumentException
   */
  protected function prepare($key, array $params = array(), array $settings = array())
  {
    // NULL-Werte dierekt zurückgeben
    if (is_null($params[$key]))
    {
      return null;
    }
    // primitive Typen verarbeiten
    if (!$settings['complex'])
    {
      return $this->prepareNonComplex($params[$key], $settings);
    }

    // Wenn das ein verschachteltes Objekt ist.
    if (count($settings['children']))
    {
      if (is_array($params[$key]))
      {
        return $this->prepareNested($settings['options'], $settings['children'], $params[$key]);
      }
      throw new InvalidArgumentException('"' . $key . '" have to be an array. ' . gettype($params[$key]) . ' given!');
    }

    // Ruft weitere Transformer-Methoden in dieser Klasse auf
    if (method_exists(new $settings['methodClass'](), $settings['method']))
    {
      return $this->{$settings['method']}($params);
    }

    throw new MissingRequiredConfigArgumentException('It must be defined at least one of the following parameters: complex(false), children(count > 0), method(!= null)');
  }



  /**
   * Diese Funktion verarbeitet Unter-Konfigurationen
   *
   * @param array $options
   * @param array $config
   * @param array $params
   *
   * @return Object
   * @throws \ENM\TransformerBundle\Exceptions\InvalidArgumentException
   */
  protected function prepareNested(array $options = array(), array $config = array(), array $params = array())
  {
    if (class_exists($options['returnClass']))
    {
      $returnClass = $options['returnClass'];

      return $this->transform(new $returnClass(), $config, $params);
    }
    throw new InvalidArgumentException('The Class ' . $options['returnClass'] . ' does not exists');
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
    $constrains = array(
      new Constraints\Type(array('type' => $settings['type']))
    );
    $constrains = array_merge($constrains, $this->getConstraintsByOptions($settings));

    $validator = Validation::createValidator();

    try
    {
      if ($settings['type'] === 'bool' && in_array($value, array('1', '0', 'true', 'false')))
      {
        $value = ($value === 'true') ? true : $value;
        $value = ($value === 'false') ? false : $value;

        $value = boolval($value);
      }

      if ($settings['type'] === 'integer' && is_numeric($value))
      {
        $value = intval($value);
      }

      if ($settings['type'] === 'float' && is_numeric($value))
      {
        $value = floatval($value);
      }

      $violationList = $validator->validate($value, $constrains);
    }
    catch (\Exception $e)
    {
      $violation     = new ConstraintViolation($e->getMessage(), $e->getMessage(), array(
        $e->getCode(),
        $e->getFile(),
        $e->getLine()
      ), $value, null, $value);
      $violationList = new ConstraintViolationList(array($violation));
    }

    if ($violationList->count() === 0)
    {
      if (
        $settings['type'] === 'array'
        && array_key_exists('assoc', $settings['options'])
        && $settings['options']['assoc'] === false
      )
      {
        return array_values($value);
      }

      return $value;
    }
    throw new InvalidParameterException($violationList);
  }



  /**
   * Diese Methode fügt der Validierung nicht-komplexer Datentypen Regeln hinzu
   *
   * @param $settings
   *
   * @return array
   */
  protected function getConstraintsByOptions($settings)
  {
    $constraints = array();
    if (array_key_exists('options', $settings))
    {
      if (in_array($settings['type'], array('integer', 'float')))
      {
        $constraints = array_merge($constraints, $this->getConstraintsByOptionMin($settings));
        $constraints = array_merge($constraints, $this->getConstraintsByOptionMax($settings));
      }
      $constraints = array_merge($constraints, $this->getConstraintsByOptionExpected($settings));
    }

    return $constraints;
  }



  /**
   * Diese Methode fügt der Validierung eine Regel für mindest-Werte hinzu.
   *
   * @param array $settings
   *
   * @return array
   */
  protected function getConstraintsByOptionMin(array $settings = array())
  {
    $constraints = array();
    if (array_key_exists('min', $settings['options']))
    {
      $constraints[] = new Constraints\GreaterThanOrEqual(array('value' => $settings['options']['min']));
    }

    return $constraints;
  }



  /**
   * Diese Methode fügt der Validierung eine Regel für maximal-Werte hinzu.
   *
   * @param array $settings
   *
   * @return array
   */
  protected function getConstraintsByOptionMax(array $settings = array())
  {
    $constraints = array();
    if (array_key_exists('max', $settings['options']))
    {
      $constraints[] = new Constraints\LessThanOrEqual(array('value' => $settings['options']['max']));
    }

    return $constraints;
  }



  /**
   * Diese Methode fügt der Validierung eine Regel für erwartete-Werte hinzu.
   *
   * @param array $settings
   *
   * @return array
   */
  protected function getConstraintsByOptionExpected(array $settings = array())
  {
    $constraints = array();

    if (array_key_exists('expected', $settings['options']) && count($settings['options']['expected']))
    {
      $config = array('choices' => $settings['options']['expected']);

      if ($settings['type'] === 'array')
      {
        $config['multiple'] = true;
      }

      $constraints[] = new Constraints\Choice($config);
    }

    return $constraints;
  }
}