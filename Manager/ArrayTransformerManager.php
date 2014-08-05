<?php
/**
 * ArrayArrayTransformerManager.php
 * Date: 13.06.14
 *
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

namespace ENM\TransformerBundle\Manager;

use Symfony\Component\DependencyInjection\Container;

/**
 * Class ArrayTransformerManager
 *
 * @package    ENM\TransformerBundle\Manager
 * @author     Philipp Marien <marien@eosnewmedia.de>
 * @deprecated Use TransformerManager instead
 */
class ArrayTransformerManager extends BaseTransformerManager
{

  public function __construct(Container $container)
  {
    parent::__construct($container);
  }



  /**
   * @param object $returnClass
   * @param array  $config
   * @param array  $params
   *
   * @return object
   * @deprecated Use TransformerManager->transform() instead
   */
  public function transform($returnClass, array $config, array $params = array())
  {
    return $this->createClass($returnClass, $config, $params);
  }
}