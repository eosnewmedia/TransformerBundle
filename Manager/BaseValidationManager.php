<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\DependencyInjection\TransformerConfiguration;
use ENM\TransformerBundle\Exceptions\MissingTransformerParameterException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

abstract class BaseValidationManager
{

  /**
   * Konfiguration überprüfen
   *
   * @param array $config
   */
  protected function validateConfiguration(array $config = array())
  {
    $processor = new Processor();

    return $processor->processConfiguration(new TransformerConfiguration(), array('config' => $config));
  }



  /**
   * Pflichtfelder überprüfen
   *
   * @param string $key
   * @param mixed  $value
   * @param array  $settings
   *
   * @return mixed
   * @throws \ENM\TransformerBundle\Exceptions\MissingTransformerParameterException
   */
  protected function validateRequired($key, $value, array $settings)
  {
    if (is_null($value))
    {
      $value = $settings['defaultValue'];
      if ($settings['options']['required'] === true && is_null($value))
      {
        throw new MissingTransformerParameterException('Required parameter "' . $key . '" is missing.');
      }
    }

    return $value;
  }



  /**
   * Validierung zusammensetzen
   *
   * @param array $settings
   *
   * @return array
   */
  protected function createValidationConstrains(array $settings)
  {
    $constrains = array(
      new Constraints\Type(array('type' => $settings['type']))
    );
    $constrains = array_merge($constrains, $this->getConstraintsByOptions($settings));

    return $constrains;
  }



  /**
   * @param \Exception $e
   * @param mixed      $value
   *
   * @return ConstraintViolationList
   */
  protected function handleException(\Exception $e, $value)
  {
    $violation = new ConstraintViolation($e->getMessage(), $e->getMessage(), array(
      $e->getCode(),
      $e->getFile(),
      $e->getLine()
    ), $value, null, $value);

    return $violationList = new ConstraintViolationList(array($violation));
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

    $constraints = array_merge($constraints, $this->getConstraintsByOptionMin($settings));
    $constraints = array_merge($constraints, $this->getConstraintsByOptionMax($settings));
    $constraints = array_merge($constraints, $this->getConstraintsByOptionExpected($settings));
    $constraints = array_merge($constraints, $this->getConstraintsByOptionLength($settings));
    $constraints = array_merge($constraints, $this->getConstraintsByOptionRegex($settings));

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
    if ($settings['options']['min'] !== null)
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
    if ($settings['options']['max'] !== null)
    {
      $constraints[] = new Constraints\LessThanOrEqual(array('value' => $settings['options']['max']));
    }

    return $constraints;
  }



  /**
   * Diese Methode fügt der Validierung eine Regel für erwartete Werte hinzu.
   *
   * @param array $settings
   *
   * @return array
   */
  protected function getConstraintsByOptionExpected(array $settings = array())
  {
    $constraints = array();

    if (count($settings['options']['expected']))
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



  /**
   * Validierung für Sting-Länge hinzufügen
   *
   * @param array $settings
   *
   * @return array
   */
  protected function getConstraintsByOptionLength(array $settings = array())
  {
    $constraints = array();

    if (array_key_exists('length', $settings['options']))
    {
      $constraints[] = new Constraints\Length(array(
        'min' => $settings['options']['length']['min'],
        'max' => $settings['options']['length']['max'],
      ));
    }

    return $constraints;
  }



  /**
   * Validierung für RegEx hinzufügen
   *
   * @param array $settings
   *
   * @return array
   */
  protected function getConstraintsByOptionRegex(array $settings = array())
  {
    $constraints = array();

    if ($settings['options']['regex'] !== null)
    {
      $constraints[] = new Constraints\Regex(array(
        'pattern' => $settings['options']['regex'],
      ));
    }

    return $constraints;
  }



  /**
   * @param mixed $value
   * @param array $settings
   *
   * @return bool|mixed
   */
  protected function normalizeBoolean($value, array $settings)
  {
    if ($settings['type'] === 'bool' && in_array($value, array('1', '0', 'true', 'false')))
    {
      $value = ($value === 'true') ? true : $value;
      $value = ($value === 'false') ? false : $value;

      $value = boolval($value);

      return $value;
    }

    return $value;
  }



  /**
   * @param mixed $value
   * @param array $settings
   *
   * @return int|mixed
   */
  protected function normalizeInteger($value, array $settings)
  {
    if ($settings['type'] === 'integer' && is_numeric($value))
    {
      $value = intval($value);

      return $value;
    }

    return $value;
  }



  /**
   * @param mixed $value
   * @param array $settings
   *
   * @return float|mixed
   */
  protected function normalizeFloat($value, array $settings)
  {
    if ($settings['type'] === 'float' && is_numeric($value))
    {
      $value = floatval($value);

      return $value;
    }

    return $value;
  }



  /**
   * @param mixed $value
   * @param array $settings
   *
   * @return array|bool|float|int|mixed
   */
  protected function normalizeType($value, array $settings)
  {
    $value = $this->normalizeBoolean($value, $settings);

    $value = $this->normalizeInteger($value, $settings);

    $value = $this->normalizeFloat($value, $settings);

    $value = $this->normalizeArray($value, $settings);

    return $value;
  }



  /**
   * @param mixed $value
   * @param array $settings
   *
   * @return array|mixed
   */
  protected function normalizeArray($value, array $settings)
  {
    if ($settings['type'] === 'array' && $settings['options']['assoc'] === false)
    {
      return array_values($value);
    }

    return $value;
  }
}