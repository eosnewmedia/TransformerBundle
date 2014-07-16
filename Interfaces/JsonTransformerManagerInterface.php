<?php
/**
 * TransformerManagerInterfaceace.php
 * Date: 13.06.14
 *
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

namespace ENM\TransformerBundle\Interfaces;

/**
 * Interface JsonTransformerManagerInterface
 *
 * @package ENM\TransformerBundle\Interfaces
 * @deprecated
 */
interface JsonTransformerManagerInterface
{

  /**
   * @param object $returnClass
   * @param array  $config
   * @param string $json
   *
   * @return object
   * @deprecated
   */
  public function transform($returnClass, array $config, $json);
} 