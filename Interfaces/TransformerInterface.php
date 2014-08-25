<?php


namespace ENM\TransformerBundle\Interfaces;

interface TransformerInterface
{

  /**
   * Diese Methode transformiert ein Array, ein Objekt oder einen JSON-String in ein gewünschtes Objekt und validiert die Werte
   *
   * @param object|string            $returnClass
   * @param array|object|string      $config
   * @param array|object|string      $values
   * @param null|array|string|object $local_config
   * @param string                   $result_type
   *
   * @return array|object|string
   */
  public function transform($returnClass, $config, $values, $local_config = null, $result_type = 'object');



  /**
   * Diese Methode transformiert ein Objekt zurück in einen JSON-String, ein Array oder eine Standard-Klasse
   *
   * @param object                   $object
   * @param array                    $config
   * @param null|array|string|object $local_config
   * @param string                   $result_type
   *
   * @return array|\stdClass|string
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  public function reverseTransform($object, array $config, $local_config = null, $result_type = 'object');



  /**
   * Creates the Structure of an Object with NULL-Values
   *
   * @param array  $config
   * @param string $result_type
   *
   * @return array|object|string
   */
  public function getEmptyObjectStructureFromConfig($config, $result_type = 'object');



  /**
   * @param mixed  $value
   * @param string $to
   *
   * @return array|object|string
   */
  public function convert($value, $to);
} 