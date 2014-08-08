<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException;
use ENM\TransformerBundle\Interfaces\TransformerInterface;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class TransformerManager
 *
 * @package ENM\TransformerBundle\Manager
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
class TransformerManager extends BaseTransformerManager implements TransformerInterface
{

  public function __construct(Container $container)
  {
    parent::__construct($container);
  }



  /**
   * Diese Methode transformiert ein Array, ein Objekt oder einen JSON-String in ein gewünschtes Objekt und validiert die Werte
   *
   * @param object|string       $returnClass
   * @param array               $config
   * @param array|object|string $values
   *
   * @return object
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  public function transform($returnClass, array $config, $values)
  {
    switch (gettype($values))
    {
      case 'array':
        return $this->createClass($returnClass, $config, $values);
      case 'object':
      case 'string':
        return $this->createClass($returnClass, $config, $this->toArray($values));
    }
    throw new InvalidTransformerParameterException(sprintf(
      'Value of type %s can not be transformed by this Method.',
      gettype($values)
    ));
  }



  /**
   * Diese Methode wandelt einen JSON-String in ein Array um.
   *
   * @param $value
   *
   * @return array
   *
   * @deprecated Use jsonToArray instead
   */
  public function transformJsonToArray($value)
  {
    return $this->jsonToArray($value);
  }



  /**
   * Diese Methode transformiert ein Objekt zurück in einen JSON-String, ein Array oder eine Standard-Klasse
   *
   * @param object $object
   * @param array  $config
   * @param string $result_type
   *
   * @return array|\stdClass|string
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  public function reverseTransform($object, array $config, $result_type)
  {
    switch (strtolower($result_type))
    {
      case 'array':
        return $this->toArray($this->reverseClass($config, $object));
      case 'object':
        return $this->reverseClass($config, $object);
      case 'json':
      case 'string':
        return json_encode($this->reverseClass($config, $object));
    }
    throw new InvalidTransformerParameterException(sprintf(
      "The given Object can't be converted to %s by this method!",
      $result_type
    ));
  }



  /**
   * @param mixed $value
   *
   * @return array
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  public function toArray($value)
  {
    switch (gettype($value))
    {
      case 'array':
      case 'object':
        return $this->objectToArray($value);
      case 'string':
        return $this->jsonToArray($value);
    }
    throw new InvalidTransformerParameterException(sprintf(
      'Value of type %s can not be transformed to array by this method.',
      gettype($value)
    ));
  }



  public function getSampleArrayFromConfig(array $config)
  {
    $config = $this->validateConfiguration($config);
    $array  = array();

    foreach ($config as $key => $settings)
    {
      if ($settings['type'] === 'collection')
      {
        $value = $this->getSampleArrayFromConfig($settings['children']['dynamic']);
      }
      elseif ($settings['type'] === 'object')
      {
        $value = $this->getSampleArrayFromConfig($settings['children']);
      }
      else
      {
        $value = $settings['options']['defaultValue'];
      }
      $array[$key] = $value;
    }

    return $array;
  }
}