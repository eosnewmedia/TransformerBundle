<?php
/**
 * TransformerManagerInterfaceace.php
 * Date: 13.06.14
 *
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

namespace ENM\TransformerBundle\Interfaces;

interface JsonTransformerManagerInterface
{

  /**
   * @param object $returnClass
   * @param array  $config
   * @param string $json
   *
   * @return object
   */
  public function transform($returnClass, array $config, $json);
} 