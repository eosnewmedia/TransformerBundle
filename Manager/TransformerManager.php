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
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  public function transform($returnClass, array $config, $values, $result_type = 'object')
  {
    return $this->convertTo($this->createClass($returnClass, $config, $values), $result_type);
  }



  /**
   * Diese Methode transformiert ein Objekt zurück in einen JSON-String, ein Array oder eine Standard-Klasse
   *
   * @param object $object
   * @param array  $config
   * @param string $result_type
   *
   * @return array|\stdClass|string
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  public function reverseTransform($object, array $config, $result_type = 'object')
  {
    return $this->convertTo($this->reverseClass($config, $object), $result_type);
  }



  /**
   * Creates the Structure of an Object with NULL-Values
   *
   * @param object $returnClass
   * @param array  $config
   * @param string $result_type
   *
   * @return array|object|string
   */
  public function getEmptyObjectStructureFromConfig($returnClass, array $config, $result_type = 'object')
  {
    $return = $this->createEmptyObjectStructure($returnClass, $config);

    return $this->convertTo($return, $result_type);
  }



  /**
   * @param mixed $value
   *
   * @return array
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  public function toArray($value)
  {
    return parent::toArray($value);
  }



  /**
   * @param object $object
   * @param string $result_type
   *
   * @return array|string
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  protected function convertTo($object, $result_type)
  {
    return parent::convertTo($object, $result_type);
  }



  /**
   * Diese Methode wandelt einen JSON-String in ein Array um.
   *
   * @param $value
   *
   * @return array
   *
   * @deprecated Use toArray instead
   *
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  public function transformJsonToArray($value)
  {
    return $this->toArray($value);
  }



  /**
   * @param array $config
   *
   * @return array|object|string
   * @deprecated use getEmptyObjectStructureFromConfig() instead
   */
  public function getSampleArrayFromConfig(array $config)
  {
    return $this->getEmptyObjectStructureFromConfig(new \stdClass(), $config, 'array');
  }
}