<?php
/**
 * EnumInterface.php
 * Date: 13.06.14
 *
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

namespace Enm\TransformerBundle\Interfaces;

interface EnumInterface
{

  /**
   * This function returns an array with static properties of the extending class.
   *
   * @return array
   */
  public static function toArray();
} 