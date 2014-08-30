<?php


namespace ENM\TransformerBundle\Helper;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use ENM\TransformerBundle\Event\ClassBuilderEvent;
use ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException;
use ENM\TransformerBundle\TransformerEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ClassBuilder
{

  /**
   * @var EventDispatcherInterface
   */
  protected $dispatcher;



  public function __construct(EventDispatcherInterface $dispatcher)
  {
    $this->dispatcher = $dispatcher;
  }



  /**
   * @param string|object                                                 $returnClass
   * @param \ENM\TransformerBundle\ConfigurationStructure\Configuration[] $config
   * @param \ENM\TransformerBundle\ConfigurationStructure\Parameter[]     $params
   *
   * @return object
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException
   */
  public function build($returnClass, array $config, array $params)
  {
    $returnClass = $this->getObjectInstance($returnClass);
    foreach ($config as $configuration)
    {
      if (!$configuration instanceof Configuration)
      {
        throw new InvalidTransformerConfigurationException('Parameter "config" have to be an array of Configuration-Objects!');
      }
      if (!$params[$configuration->getKey()] instanceof Parameter)
      {
        throw new InvalidTransformerConfigurationException('Parameter "params" have to be an array of Parameter-Objects!');
      }
      $this->setValue($returnClass, $configuration, $params[$configuration->getKey()]);
    }

    return $returnClass;
  }



  /**
   * @param string|object $class
   *
   * @return object
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException
   */
  public function getObjectInstance($class)
  {
    // Eventbasierte Objekt-Erzeugung
    $event = new ClassBuilderEvent($class);

    // $class enthält bereits ein Objekt
    if ($event->isObject() === true)
    {
      // Vor der Rückgabe an die EventListener/EventSubscriber geben...
      $this->dispatcher->dispatch(TransformerEvents::OBJECT_RETURN_INSTANCE, $event);

      return $event->getObject();
    }

    // $class enthält noch kein Objekt
    if (class_exists($event->getObject()))
    {
      $this->dispatcher->dispatch(TransformerEvents::OBJECT_CREATE_INSTANCE, $event);

      // Wenn in keinem EventListener/EventSubscriber die Instanz erzeugt wurde...
      if ($event->isObject() === false)
      {
        $reflection = new \ReflectionClass($class);

        return $reflection->newInstanceWithoutConstructor();
      }

      // Wenn in einem EventListener/EventSubscriber die Instanz erzeugt wurde...
      return $event->getObject();
    }
    throw new InvalidTransformerConfigurationException(sprintf('Class %s does not exist.', $class));
  }



  /**
   * @param object        $returnClass
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function setValue($returnClass, Configuration $configuration, Parameter $parameter)
  {
    // todo Class Default, wenn Value Null
    $key = $configuration->getRenameTo() !== null ? $configuration->getRenameTo() : $configuration->getKey();

    $setter = $this->getSetter($key);

    // Überprüfen, ob der Setter vorhanden ist
    if (method_exists($returnClass, $setter))
    {
      // Ruft den Setter der ReturnClass auf
      $returnClass->{$setter}($parameter->getValue());
    }
    else
    {
      // @todo Reflection
      // setzt den Property Wert
      $returnClass->$key = $parameter->getValue();
    }
  }



  protected function getSetter($key)
  {
    return 'set' . $this->getMethodName($key);
  }



  protected function getGetter($key)
  {
    return 'get' . $this->getMethodName($key);
  }



  protected function getMethodName($key)
  {
    $pieces = explode('_', $key);
    if (count($pieces) > 0)
    {
      $method = '';
      foreach ($pieces as $piece)
      {
        $method .= ucfirst($piece);
      }

      return $method;
    }

    return ucfirst($key);
  }
} 