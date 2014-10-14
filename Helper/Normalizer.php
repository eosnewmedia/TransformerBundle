<?php


namespace Enm\TransformerBundle\Helper;

use Enm\TransformerBundle\ConfigurationStructure\Configuration;
use Enm\TransformerBundle\ConfigurationStructure\Parameter;
use Enm\TransformerBundle\ConfigurationStructure\TypeEnum;
use Enm\TransformerBundle\Exceptions\InvalidTransformerParameterException;

class Normalizer
{

  /**
   * @var Converter
   */
  protected $converter;



  /**
   * @param Converter $converter
   */
  public function __construct(Converter $converter)
  {
    $this->converter = $converter;
  }



  /**
   * @param Parameter     $parameter
   * @param Configuration $configuration
   */
  public function normalize(Parameter $parameter, Configuration $configuration)
  {
    switch ($configuration->getType())
    {
      case TypeEnum::ARRAY_TYPE:
        $this->normalizeArray($parameter, $configuration);
        break;
      case TypeEnum::BOOL_TYPE:
        $this->normalizeBoolean($parameter);
        break;
      case TypeEnum::INTEGER_TYPE:
        $this->normalizeInteger($parameter);
        break;
      case TypeEnum::FLOAT_TYPE:
        $this->normalizeFloat($parameter);
        break;
      case TypeEnum::DATE_TYPE:
        $this->normalizeDate($parameter, $configuration);
        break;
      case TypeEnum::COLLECTION_TYPE:
        $this->normalizeCollection($parameter);
        break;
      case TypeEnum::OBJECT_TYPE:
        $this->normalizeObject($parameter);
        break;
    }
  }



  /**
   * @param Parameter $parameter
   */
  protected function normalizeBoolean(Parameter $parameter)
  {
    if (in_array($parameter->getValue(), array('1', '0', 'true', 'false')))
    {
      $value = ($parameter->getValue() === 'true') ? true : $parameter->getValue();
      $value = ($parameter->getValue() === 'false') ? false : $value;
      $value = boolval($value);
      $parameter->setValue($value);
    }
  }



  /**
   * @param Parameter $parameter
   */
  protected function normalizeInteger(Parameter $parameter)
  {
    if (is_numeric($parameter->getValue()))
    {
      $parameter->setValue(intval($parameter->getValue()));
    }
  }



  /**
   * @param Parameter $parameter
   */
  protected function normalizeFloat(Parameter $parameter)
  {
    if (is_numeric($parameter->getValue()))
    {
      $parameter->setValue(floatval($parameter->getValue()));
    }
  }



  /**
   * @param Parameter     $parameter
   * @param Configuration $configuration
   */
  protected function normalizeArray(Parameter $parameter, Configuration $configuration)
  {
    $parameter->setValue($this->converter->convertTo($parameter->getValue(), 'array'));
    if ($configuration->getOptions()->getArrayOptions()->isAssociative() === false)
    {
      $parameter->setValue(array_values($parameter->getValue()));
    }
  }



  /**
   * @param Parameter     $parameter
   * @param Configuration $configuration
   *
   * @throws \Enm\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  protected function normalizeDate(Parameter $parameter, Configuration $configuration)
  {
    if (is_array($parameter->getValue()))
    {
      try
      {
        // 'date' ist der Standard-Key, wenn ein DateTime-Objekt in Array umgewandelt wird
        $date   = new \DateTime($parameter->getValue()['date']);
        $format = $configuration->getOptions()->getDateOptions()->getExpectedFormat()[0];
        $parameter->setValue($date->format($format));
      }
      catch (\Exception $e)
      {
        throw new InvalidTransformerParameterException('"' . $configuration->getKey() . '" is not a date string.');
      }
    }
  }



  /**
   * @param Parameter $parameter
   */
  protected function normalizeCollection(Parameter $parameter)
  {
    if (is_array($parameter->getValue()))
    {
      $parameter->setValue(array_values($parameter->getValue()));
    }
    if (is_object($parameter->getValue()))
    {
      $parameter->setValue($this->converter->convertTo($parameter->getValue(), 'array'));
    }
    if (!is_array($parameter->getValue()) && !is_null($parameter->getValue()))
    {
      $parameter->setValue(array());
    }
  }



  /**
   * @param Parameter $parameter
   */
  protected function normalizeObject(Parameter $parameter)
  {
    if (is_object($parameter->getValue()))
    {
      $parameter->setValue($this->converter->convertTo($parameter->getValue(), 'array'));
    }
    if (!is_array($parameter->getValue()) && !is_null($parameter->getValue()))
    {
      $parameter->setValue(array());
    }
  }
}
