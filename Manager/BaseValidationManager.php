<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\DependencyInjection\TransformerConfiguration;
use ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException;
use ENM\TransformerBundle\Exceptions\MissingTransformerParameterException;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

abstract class BaseValidationManager
{

  /**
   * @var \Symfony\Component\DependencyInjection\Container
   */
  protected $container;



  public function __construct(Container $container)
  {
    $this->container = $container;
  }



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
  protected function validateRequired($key, $value, array $params, array $settings)
  {
    if (is_null($value))
    {
      $value = $settings['options']['defaultValue'];

      $params   = array_change_key_case($params, CASE_LOWER);
      $settings = $this->requiredIf($params, $settings);

      if ($settings['options']['required'] === true && is_null($value))
      {
        throw new MissingTransformerParameterException('Required parameter "' . $key . '" is missing.');
      }
    }

    $this->forbiddenIf($key, $value, $params, $settings);

    return $value;
  }



  /**
   * @param array $params
   * @param array $settings
   *
   * @return array $settings
   */
  protected function requiredIf(array $params, array $settings)
  {
    $required = $settings['options']['required'];
    if ($required === false)
    {
      if ($this->requiredIfNotAvailableAnd($params, $settings['options']['requiredIfNotAvailable']['or']) === true)
      {
        $required = true;
      }
      elseif ($this->requiredIfNotAvailableOr($params, $settings['options']['requiredIfNotAvailable']['and']) === true)
      {
        $required = true;
      }
      elseif ($this->requiredIfAvailableAnd($params, $settings['options']['requiredIfAvailable']['or']) === true)
      {
        $required = true;
      }
      elseif ($this->requiredIfAvailableOr($params, $settings['options']['requiredIfAvailable']['and']) === true)
      {
        $required = true;
      }
      $settings['options']['required'] = $required;
    }

    return $settings;
  }



  /**
   * @param array $params
   * @param array $or
   *
   * @return bool
   */
  protected function requiredIfNotAvailableOr(array $params, array $or)
  {
    foreach ($or as $param)
    {
      $param = strtolower($param);
      if (!array_key_exists($param, $params) || $params[$param] === null)
      {
        return true;
      }
    }

    return false;
  }



  /**
   * @param array $params
   * @param array $and
   *
   * @return bool
   */
  protected function requiredIfNotAvailableAnd(array $params, array $and)
  {
    if (count($and) > 0)
    {
      foreach ($and as $param)
      {
        $param = strtolower($param);
        if (array_key_exists($param, $params) && $params[$param] !== null)
        {
          return false;
        }
      }

      return true;
    }

    return false;
  }



  /**
   * @param array $params
   * @param array $or
   *
   * @return bool
   */
  protected function requiredIfAvailableOr(array $params, array $or)
  {
    foreach ($or as $param)
    {
      $param = strtolower($param);
      if (array_key_exists($param, $params) && $params[$param] !== null)
      {
        return true;
      }
    }

    return false;
  }



  /**
   * @param array $params
   * @param array $and
   *
   * @return bool
   */
  protected function requiredIfAvailableAnd(array $params, array $and)
  {
    if (count($and) > 0)
    {
      foreach ($and as $param)
      {
        $param = strtolower($param);
        if (!array_key_exists($param, $params) || $params[$param] === null)
        {
          return false;
        }
      }

      return true;
    }

    return false;
  }



  protected function forbiddenIf($key, $value, array $params, array $settings)
  {
    if (!is_null($value))
    {
      $this->forbiddenIfAvailable($key, $params, $settings['options']['forbiddenIfAvailable']);
      $this->forbiddenIfNotAvailable($key, $params, $settings['options']['forbiddenIfNotAvailable']);
    }
  }



  /**
   * @param string $key
   * @param array  $params
   * @param array  $available
   *
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  protected function forbiddenIfNotAvailable($key, array $params, array $available)
  {
    foreach ($available as $param)
    {
      $param = strtolower($param);
      if (!array_key_exists($param, $params) || $params[$param] === null)
      {
        throw new InvalidTransformerParameterException(sprintf('%s can not be used, if %s is missing', $key, $param));
      }
    }
  }



  /**
   * @param string $key
   * @param array  $params
   * @param array  $settings
   *
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  protected function forbiddenIfAvailable($key, array $params, array $available)
  {
    foreach ($available as $param)
    {
      $param = strtolower($param);
      if (array_key_exists($param, $params) && $params[$param] !== null)
      {
        throw new InvalidTransformerParameterException(sprintf('%s can not be used, if %s is set', $key, $param));
      }
    }
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
    $constraints = array_merge($constraints, $this->getConstraintsByOptionStringValidation($settings));
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
  protected function getConstraintsByOptionMin(array $settings)
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
  protected function getConstraintsByOptionMax(array $settings)
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
  protected function getConstraintsByOptionExpected(array $settings)
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



  public function getConstraintsByOptionStringValidation(array $settings)
  {
    $constraints = array();

    switch (strtolower($settings['options']['stringValidation']))
    {
      case 'email':
        $constraints[] = new Constraints\Email(array('checkMX' => true, 'checkHost' => true));
        break;
      case 'url':
        $constraints[] = new Constraints\Url(array('protocols' => array('http', 'https', 'ftp')));
        break;
      case 'ip':
        $constraints[] = new Constraints\Ip(array('version' => 'all'));
        break;
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
  protected function getConstraintsByOptionLength(array $settings)
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
  protected function getConstraintsByOptionRegex(array $settings)
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