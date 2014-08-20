<?php


namespace ENM\TransformerBundle\Resources\TestClass;

use ENM\TransformerBundle\Event\ConfigurationEvent;
use ENM\TransformerBundle\Event\TransformerEvent;
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
      TransformerEvents::VALIDATE_INTEGER           => 'testConstraintFunction',
      TransformerEvents::VALIDATE_FLOAT             => 'testConstraintFunction',
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



  public function testConstraintFunction(TransformerEvent $event)
  {
  }
}