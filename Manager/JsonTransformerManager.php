<?php
/**
 * JsonTransformerManager.php
 * Date: 13.06.14
 *
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\Interfaces\JsonTransformerManagerInterface;
use Symfony\Component\DependencyInjection\Container;

class JsonTransformerManager implements JsonTransformerManagerInterface
{

  protected $container;



  function __construct(Container $container)
  {
    $this->container = $container;
  }



  /**
   * @param object $returnClass
   * @param array  $config
   * @param string $json
   *
   * @return object
   */
  public function transform($returnClass, array $config, $json)
  {
    $jsonClass = json_decode($json);
    $params    = $this->objectToArray($jsonClass);

    return $this->container->get('enm.array.transformer.service')->transform($returnClass, $config, $params);
  }



  protected function objectToArray($object)
  {
    $array = array();
    foreach ($object as $key => $value)
    {
      if (is_object($value))
      {
        $array[$key] = $this->objectToArray($value);
      }
      else
      {
        $array[$key] = $value;
      }
    }

    return $array;
  }
}