<?php


namespace Enm\TransformerBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ClassBuilderEvent extends Event
{

  /**
   * @var object|string
   */
  protected $object;



  public function __construct($object)
  {
    $this->object = $object;
  }



  /**
   * @return object|string
   */
  public function getObject()
  {
    return $this->object;
  }



  public function isObject()
  {
    return is_object($this->object);
  }
}
