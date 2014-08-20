<?php


namespace ENM\TransformerBundle\Resources\TestClass;

use ENM\TransformerBundle\Event\ConfigurationEvent;
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
      TransformerEvents::BEFORE_CHILD_CONFIGURATION => 'testFunction',
      TransformerEvents::AFTER_CHILD_CONFIGURATION  => 'testFunction',
    );
  }



  /**
   * @param ConfigurationEvent $event
   */
  public function testFunction(ConfigurationEvent $event)
  {
    if (!$event->getConfiguration()->getType())
    {
      throw new \Exception();
    }
  }
}