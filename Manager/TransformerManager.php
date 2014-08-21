<?php


namespace ENM\TransformerBundle\Manager;

class TransformerManager extends BaseTransformerManager
{

  /**
   * Diese Methode transformiert ein Array, ein Objekt oder einen JSON-String in ein gewünschtes Objekt und validiert die Werte
   *
   * @param object|string            $returnClass
   * @param array|object|string      $config
   * @param array|object|string      $values
   * @param null|array|string|object $global_config_key
   * @param string                   $result_type
   *
   * @return array|object|string
   */
  public function transform($returnClass, $config, $values, $local_config = null, $result_type = 'object')
  {
    $value = $this->setLocalConfig($local_config)->process($returnClass, $config, $values);

    $value = $this->converter->convertTo($value, $result_type);

    return $value;
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
  public function reverseTransform($object, array $config, $local_config = null, $result_type = 'object')
  {
    $value = $this->setLocalConfig($local_config)->reverseProcess($config, $object);

    $value = $this->converter->convertTo($value, $result_type);

    return $value;
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
  public function getEmptyObjectStructureFromConfig($config, $result_type = 'object')
  {
    $value = $this->createEmptyObjectStructure($config);
    $value = $this->converter->convertTo($value, $result_type);

    return $value;
  }
}