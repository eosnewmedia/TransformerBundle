<?php
/**
 * BaseEnumeration.php
 * Date: 13.06.14
 *
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

namespace ENM\TransformerBundle\Enumeration;

use ENM\TransformerBundle\Interfaces\EnumInterface;

abstract class BaseEnumeration implements EnumInterface
{

  /**
   * This function returns an array with static properties of the extending class.
   *
   * @return array
   */
  public static function toArray()
  {
    $self   = new \ReflectionClass(get_called_class());
    $return = array();
    foreach ($self->getConstants() as $value)
    {
      array_push($return, $value);
    }

    return $return;
  }
} 