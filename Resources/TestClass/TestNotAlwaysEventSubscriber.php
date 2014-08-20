<?php


namespace ENM\TransformerBundle\Resources\TestClass;

use ENM\TransformerBundle\Event\ConfigurationEvent;
use ENM\TransformerBundle\Event\TransformerEvent;
use ENM\TransformerBundle\Event\ValidatorEvent;
use ENM\TransformerBundle\TransformerEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class TestEventSubscriber implements EventSubscriberInterface
{

  /**
   * @return array
   */
  public static function getSubscribedEvents()
  {
    return array(
      TransformerEvents::BEFORE_VALIDATION => 'testFunctionNotAlways',
    );
  }



  /**
   * @param ConfigurationEvent $event
   */
  public function testFunctionNotAlways(ValidatorEvent $event)
  {
    if (!$event->getConfiguration()->getType())
    {
      throw new \Exception();
    }
  }
}