<?php


namespace ENM\TransformerBundle\Listener;

use ENM\TransformerBundle\Event\ExceptionEvent;

class ExceptionListener
{

  public function onException(ExceptionEvent $event)
  {
    //    echo "\n";
    //    echo $event->getException()->getCode() . ': ';
    //    echo $event->getException()->getMessage() . "\n";
    //    echo $event->getException()->getFile() . ': ';
    //    echo $event->getException()->getLine() . "\n\n";
  }
} 