<?php


namespace Enm\TransformerBundle\Helper;

use Enm\TransformerBundle\Exceptions\InvalidTransformerConfigurationException;
use Enm\TransformerBundle\Exceptions\TransformerException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventHandler
{

  /**
   * @var ClassBuilder
   */
  protected $classBuilder;

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $dispatcher;



  /**
   * @param EventDispatcherInterface $dispatcher
   * @param ClassBuilder             $classBuilder
   */
  public function __construct(EventDispatcherInterface $dispatcher, ClassBuilder $classBuilder)
  {
    $this->classBuilder = $classBuilder;
    $this->dispatcher   = $dispatcher;
  }



  /**
   * @param array $events
   *
   * @return $this
   */
  public function init(array $events)
  {
    try
    {
      $this->addEventListeners($events['listeners'])->addEventSubscribers($events['subscribers']);
    }
    catch (\Exception $e)
    {
      throw new TransformerException($e->getMessage());
    }

    return $this;
  }



  /**
   * @param array $events
   *
   * @return $this
   */
  public function destroy(array $events)
  {
    try
    {
      $this->removeEventListeners($events['listeners'])->removeEventSubscribers($events['subscribers']);
    }
    catch (\Exception $e)
    {
      throw new TransformerException($e->getMessage());
    }

    return $this;
  }



  /**
   * @param array $listeners
   *
   * @return $this
   * @throws \Enm\TransformerBundle\Exceptions\InvalidTransformerConfigurationException
   */
  protected function addEventListeners(array $listeners)
  {
    try
    {
      foreach ($listeners as $listener)
      {
        $this->dispatcher->addListener(
                         $listener['event'],
                           array(
                             $listener['class'],
                             $listener['method']
                           ),
                           $listener['priority']
        );
      }
    }
    catch (\Exception $e)
    {
      throw new InvalidTransformerConfigurationException($e->getMessage());
    }

    return $this;
  }



  /**
   * @param array $subscribers
   *
   * @return $this
   * @throws \Enm\TransformerBundle\Exceptions\InvalidTransformerConfigurationException
   */
  protected function addEventSubscribers(array $subscribers)
  {
    try
    {
      foreach ($subscribers as $subscriber)
      {
        $this->dispatcher->addSubscriber($this->classBuilder->getObjectInstance($subscriber));
      }
    }
    catch (\Exception $e)
    {
      throw new InvalidTransformerConfigurationException($e->getMessage());
    }

    return $this;
  }



  /**
   * @param array $listeners
   *
   * @return $this
   * @throws \Enm\TransformerBundle\Exceptions\InvalidTransformerConfigurationException
   */
  protected function removeEventListeners(array $listeners)
  {
    try
    {
      foreach ($listeners as $listener)
      {
        $this->dispatcher->removeListener(
                         $listener['event'],
                           array(
                             $listener['class'],
                             $listener['method']
                           )
        );
      }
    }
    catch (\Exception $e)
    {
      throw new InvalidTransformerConfigurationException($e->getMessage());
    }

    return $this;
  }



  /**
   * @param array $subscribers
   *
   * @return $this
   * @throws \Enm\TransformerBundle\Exceptions\InvalidTransformerConfigurationException
   */
  protected function removeEventSubscribers(array $subscribers)
  {
    try
    {
      foreach ($subscribers as $subscriber)
      {
        $this->dispatcher->removeSubscriber($this->classBuilder->getObjectInstance($subscriber));
      }
    }
    catch (\Exception $e)
    {
      throw new InvalidTransformerConfigurationException($e->getMessage());
    }

    return $this;
  }
}