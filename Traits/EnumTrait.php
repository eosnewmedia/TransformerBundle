<?php

namespace Enm\TransformerBundle\Traits;

/**
 * Class EnumTrait
 *
 * @package Enm\TransformerBundle\Traits
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
trait EnumTrait
{

  /**
   * This method returns an array with the static properties of the called class.
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
