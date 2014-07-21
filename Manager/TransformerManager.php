<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException;
use ENM\TransformerBundle\Interfaces\TransformerInterface;

/**
 * Class TransformerManager
 *
 * @package ENM\TransformerBundle\Manager
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
class TransformerManager extends BaseTransformerManager implements TransformerInterface
{

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
        $returnClass = $this->createClass($returnClass, $config, $values);
        break;
      case 'object':
        $returnClass = $this->createClass($returnClass, $config, $this->objectToArray($values));
        break;
      case 'string':
        $returnClass = $this->createClass($returnClass, $config, $this->transformJsonToArray($values));
        break;
      default:
        throw new InvalidTransformerParameterException(sprintf(
          'Value of type %s can not be transformed by this Method.',
          gettype($values)
        ));
    }

    return $returnClass;
  }



  /**
   * Diese Methode wandelt einen JSON-String in ein Array um.
   *
   * @param string $value
   *
   * @return array
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  public function transformJsonToArray($value)
  {
    try
    {
      return $this->objectToArray(json_decode($value));
    }
    catch (\Exception $e)
    {
    }
    throw new InvalidTransformerParameterException("The given Value isn't a valid JSON-String.");
  }
} 