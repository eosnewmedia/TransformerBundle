<?php


namespace ENM\TransformerBundle\Resources\TestClass;

use ENM\TransformerBundle\Event\TransformerEvent;

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