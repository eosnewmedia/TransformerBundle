<?php


namespace Enm\TransformerBundle\Interfaces;

/**
 * Interface ConfigInterface
 *
 * @package Enm\TransformerBundle\Interfaces
 */
interface ConfigInterface
{

  /**
   * Method returns a configuration array for the transformer
   *
   * @return array
   */
  public static function getConfig();
} 