<?php


namespace ENM\TransformerBundle\Helper;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use ENM\TransformerBundle\Event\TransformerEvent;
use ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException;
use ENM\TransformerBundle\TransformerEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ClassBuilder
{

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
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
    $returnClass = $this->getClass($returnClass);
    foreach ($config as $key => $settings)
    {
      if (!$settings instanceof Configuration)
      {
        throw new InvalidTransformerConfigurationException('Parameter "config" have to be an array of Configuration-Objects!');
      }
      if (!$params[$key] instanceof Parameter)
      {
        throw new InvalidTransformerConfigurationException('Parameter "params" have to be an array of Parameter-Objects!');
      }
      $this->dispatcher->dispatch(
                       TransformerEvents::BEFORE_CLASS_SET_VALUE,
                         new TransformerEvent($settings, $params[$key])
      );
      $this->setValue($returnClass, $settings, $params[$key]);
      $this->dispatcher->dispatch(
                       TransformerEvents::AFTER_CLASS_SET_VALUE,
                         new TransformerEvent($settings, $params[$key])
      );
    }

    return $returnClass;
  }



  /**
   * @param string|object $class
   *
   * @return object
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException
   */
  protected function getClass($class)
  {
    if (is_object($class))
    {
      return $class;
    }

    if (class_exists($class))
    {
      $reflection = new \ReflectionClass($class);

      return $reflection->newInstanceWithoutConstructor();
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
      // setzt den Property Wert
      $returnClass->$key = $parameter->getValue();
    }
  }



  protected function getSetter($key)
  {
    $pieces = explode('_', $key);
    $setter = 'set';
    if (count($pieces) > 0)
    {
      foreach ($pieces as $piece)
      {
        $setter .= ucfirst($piece);
      }

      return $setter;
    }

    return $setter . ucfirst($key);
  }
} 