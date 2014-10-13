<?php
/**
 * BaseEnumeration.php
 * Date: 13.06.14
 *
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

namespace Enm\TransformerBundle\Enumeration;

use Enm\TransformerBundle\Interfaces\EnumInterface;

/**
 * Class BaseEnumeration
 *
 * @package    Enm\TransformerBundle\Enumeration
 * @author     Philipp Marien <marien@eosnewmedia.de>
 *
 * @deprecated use the Enm\TransformerBundle\Traits\EnumTrait instead
 */
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
