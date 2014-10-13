<?php


namespace Enm\TransformerBundle\Manager;

use Enm\TransformerBundle\Interfaces\TransformerInterface;

class TransformerManager extends BaseTransformerManager implements TransformerInterface
{

  /**
   * This method transforms an array, an object or a json into the needed format.
   * It will validate the structure and the values with reference to a given configuration array.
   *
   * @param object|string            $returnClass
   * @param array|object|string      $config
   * @param array|object|string      $values
   * @param null|array|string|object $local_config
   * @param string                   $result_type
   *
   * @return array|object|string
   * @throws \Enm\TransformerBundle\Exceptions\TransformerException
   */
  public function transform($returnClass, $config, $values, $local_config = null, $result_type = 'object')
  {
    $value = $this->setLocalConfig($local_config)->process($returnClass, $config, $values);

    $value = $this->converter->convertTo($value, $result_type);

    return $value;
  }



  /**
   * This method reverses the transforming.
   *
   * @param object|object|string     $object
   * @param array|object|string      $config
   * @param null|array|string|object $local_config
   * @param string                   $result_type
   *
   * @return array|\stdClass|string
   * @throws \Enm\TransformerBundle\Exceptions\TransformerException
   */
  public function reverseTransform($object, $config, $local_config = null, $result_type = 'object')
  {
    $value = $this->setLocalConfig($local_config)->reverseProcess($config, $object);

    $value = $this->converter->convertTo($value, $result_type);

    return $value;
  }



  /**
   * Creates the Structure of an Object with NULL-Values
   *
   * @param array  $config
   * @param string $result_type
   *
   * @return array|object|string
   */
  public function getEmptyObjectStructureFromConfig($config, $result_type = 'object')
  {
    $value = $this->createEmptyObjectStructure($config);
    $value = $this->converter->convertTo($value, $result_type);

    return $value;
  }



  /**
   * Converts a value to an other format.
   *
   * @param mixed  $value
   * @param string $to
   *
   * @return array|object|string
   */
  public function convert($value, $to)
  {
    return $this->converter->convertTo($value, $to);
  }
}
