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
      TransformerEvents::BEFORE_RUN => 'testFunction',
    );
  }



  public function testFunction(TransformerEvent $event)
  {
    if ($event->getParameter()->getKey() === 'user' && $event->getParameter()->getValue() !== 'testUser')
    {
      //echo $event->getParameter()->getKey() . "\n";
      //var_dump($event->getParameter()->getValue());
      //var_dump($event->getConfiguration());
    }
  }
}