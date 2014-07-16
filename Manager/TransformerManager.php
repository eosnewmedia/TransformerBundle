<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\Exceptions\InvalidArgumentException;

/**
 * Class TransformerManager
 *
 * @package ENM\TransformerBundle\Manager
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
class TransformerManager extends BaseTransformerManager
{

  /**
   * @param object              $returnClass
   * @param array               $config
   * @param array|object|string $values
   *
   * @return object
   * @throws \ENM\TransformerBundle\Exceptions\InvalidArgumentException
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
        $stdClass    = '';
        $returnClass = $this->createClass($returnClass, $config, $this->objectToArray($stdClass));
        break;
      default:
        throw new InvalidArgumentException(sprintf(
          'Value of type %s can not be transformed by this Method.',
          gettype($values)
        ));
    }
    return $returnClass;
  }



  /**
   * @param string $value
   *
   * @return object
   * @throws \ENM\TransformerBundle\Exceptions\InvalidArgumentException
   */
  protected function convertStringToJson($value)
  {
    try
    {
      return json_decode($value);
    }
    catch (\Exception $e)
    {
    }
    throw new InvalidArgumentException("The given Value isn't a valid JSON-String.");
  }
} 