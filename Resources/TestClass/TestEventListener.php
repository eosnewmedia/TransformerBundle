<?php


namespace Enm\TransformerBundle\Resources\TestClass;

use Enm\TransformerBundle\Event\TransformerEvent;

class TestEventListener
{

  public function testListener(TransformerEvent $event)
  {
    if (!$event->getConfiguration())
    {
      throw new \Exception();
    }
  }



  public function testNotAlwaysListener(TransformerEvent $event)
  {
    if (!$event->getConfiguration())
    {
      throw new \Exception();
    }
  }
} 