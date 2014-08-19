<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\Interfaces\TransformerInterface;

class TransformerManagerV2 implements TransformerInterface
{

  /**
   * Diese Methode transformiert ein Array, ein Objekt oder einen JSON-String in ein gewünschtes Objekt und validiert die Werte
   *
   * @param object|string       $returnClass
   * @param array               $config
   * @param array|object|string $values
   *
   * @return object
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  public function transform($returnClass, array $config, $values, $result_type = 'object')
  {
    // TODO: Implement transform() method.
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
    // TODO: Implement reverseTransform() method.
  }



  /**
   * @param mixed $value
   *
   * @return array
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  public function toArray($value)
  {
    // TODO: Implement toArray() method.
  }



  /**
   * Creates the Structure of an Object with NULL-Values
   *
   * @param object $returnClass
   * @param array  $config
   * @param string $type
   *
   * @return array|object|string
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  public function getEmptyObjectStructureFromConfig(array $config, $result_type = 'object')
  {
    // TODO: Implement getEmptyObjectStructureFromConfig() method.
  }
}