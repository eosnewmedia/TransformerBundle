<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\ConversionEnum;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use ENM\TransformerBundle\ConfigurationStructure\TypeEnum;
use ENM\TransformerBundle\Event\ConfigurationEvent;
use ENM\TransformerBundle\Event\TransformerEvent;
use ENM\TransformerBundle\Exceptions\TransformerException;
use ENM\TransformerBundle\Helper\ClassBuilder;
use ENM\TransformerBundle\Helper\Configurator;
use ENM\TransformerBundle\Helper\Converter;
use ENM\TransformerBundle\Helper\EventHandler;
use ENM\TransformerBundle\Helper\Normalizer;
use ENM\TransformerBundle\Helper\Validator;
use ENM\TransformerBundle\TransformerEvents;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Stopwatch\Stopwatch;

class BaseTransformerManager
{

  /**
   * @var \ENM\TransformerBundle\Helper\ClassBuilder
   */
  protected $classBuilder;


  /**
   * @var \ENM\TransformerBundle\Helper\Validator
   */
  protected $validator;

  /**
   * @var \ENM\TransformerBundle\Helper\Normalizer
   */
  protected $normalizer;

  /**
   * @var \ENM\TransformerBundle\Helper\Converter
   */
  protected $converter;

  /**
   * @var \ENM\TransformerBundle\Helper\Configurator
   */
  protected $configurator;

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $dispatcher;

  /**
   * @var Stopwatch
   */
  protected $stopwatch;

  /**
   * @var Container
   */
  protected $container;

  /**
   * @var EventHandler
   */
  protected $eventHandler;



  public function __construct(Container $container)
  {
    $this->container  = $container;
    $this->dispatcher = $container->get('enm.transformer.event_dispatcher');
    $this->stopwatch  = $container->get('debug.stopwatch');
    $this->stopwatch->start('constructor.init.helpers', 'transformer');
    $this->classBuilder = new ClassBuilder();
    $this->converter    = new Converter();
    $this->normalizer   = new Normalizer();
    $this->eventHandler = new EventHandler($this->dispatcher, $this->classBuilder);
    $this->validator    = new Validator($this->dispatcher, $container->get('validator'));
    $this->stopwatch->stop('constructor.init.helpers');
  }



  /**
   * @param string $config_key
   *
   * @return $this
   */
  protected function init($config_key)
  {
    $this->stopwatch->start('init', 'transformer');
    $this->configurator = new Configurator($this->dispatcher);

    $config = $this->container->getParameter('transformer.config');
    if ($config !== false && array_key_exists($config_key, $config))
    {
      $config[$config_key]['events'] = $this->prepareEventConfig($config[$config_key]['events']);
      $this->eventHandler->init($config[$config_key]['events']);
    }

    $this->stopwatch->stop('init');
    $this->stopwatch->start('transform', 'transformer');

    return $this;
  }



  /**
   * @param string $config_key
   *
   * @return $this
   */
  protected function destroy($config_key)
  {
    if ($this->stopwatch->isStarted('transform'))
    {
      $this->stopwatch->stop('transform');
    }

    $this->stopwatch->start('destroy', 'transformer');
    unset($this->configurator);

    $config = $this->container->hasParameter('transformer.config') ?
      $this->container->getParameter('transformer.config') : false;
    if ($config !== false && array_key_exists($config_key, $config))
    {
      $this->eventHandler->destroy($config[$config_key]['events']);
    }

    $this->stopwatch->stop('destroy');

    return $this;
  }



  /**
   * @param string|object $returnClass
   * @param mixed         $config
   * @param mixed         $params
   * @param string        $config_key
   *
   * @return object
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  public function process($returnClass, $config, $params, $config_key = null)
  {
    try
    {
      $this->init($config_key);

      $config = $this->converter->convertTo($config, ConversionEnum::ARRAY_CONVERSION);
      $config = $this->configurator->getConfig($config);
      $params = $this->converter->convertTo($params, ConversionEnum::ARRAY_CONVERSION);

      $returnClass = $this->build($returnClass, $config, $params);

      $this->destroy($config_key);

      return $returnClass;
    }
    catch (\Exception $e)
    {
      $this->destroy($config_key);
      throw new TransformerException($e->getMessage() . ' --- ' . $e->getFile() . ' - ' . $e->getLine());
    }
  }



  /**
   * @param string|object   $returnClass
   * @param Configuration[] $config
   * @param array           $params
   *
   * @return object
   */
  protected function build($returnClass, array $config, array $params)
  {
    $params                = array_change_key_case($params, CASE_LOWER);
    $returnClassProperties = array();
    foreach ($config as $key => $configuration)
    {
      $this->initRun($configuration);

      $parameter = $this->getParameterObject($configuration, $key, $params);
      $this->doRun($configuration, $parameter, $params);
      $returnClassProperties[$key] = $parameter;

      $this->destroyRun($configuration);
    }

    return $this->classBuilder->build($returnClass, $config, $returnClassProperties);
  }



  /**
   * @param Configuration $configuration
   */
  protected function initRun(Configuration $configuration)
  {
    $configuration->setEvents($this->prepareEventConfig($configuration->getEvents()));
    $this->eventHandler->init($configuration->getEvents());

    $this->stopwatch->start($configuration->getKey(), 'transformer');

    $this->dispatcher->dispatch(
                     TransformerEvents::BEFORE_RUN,
                       new ConfigurationEvent($configuration)
    );
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   * @param array         $params
   */
  protected function doRun(Configuration $configuration, Parameter $parameter, array $params)
  {
    $this->stopwatch->start($configuration->getKey() . '.validate', 'transformer');
    $this->validator->validate($configuration, $parameter);
    $this->stopwatch->stop($configuration->getKey() . '.validate');

    $this->stopwatch->start($configuration->getKey() . '.require', 'transformer');
    $this->validator->requireValue($configuration, $parameter, $params);
    $this->stopwatch->stop($configuration->getKey() . '.require');

    $this->stopwatch->start($configuration->getKey() . '.forbid', 'transformer');
    $this->validator->forbidValue($configuration, $parameter, $params);
    $this->stopwatch->stop($configuration->getKey() . '.forbid');

    $this->stopwatch->start($configuration->getKey() . '.prepare', 'transformer');
    $this->prepareValue($configuration, $parameter);
    $this->stopwatch->stop($configuration->getKey() . '.prepare');
  }



  /**
   * @param Configuration $configuration
   */
  protected function destroyRun(Configuration $configuration)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::AFTER_RUN,
                       new ConfigurationEvent($configuration)
    );

    $this->stopwatch->stop($configuration->getKey());
    $this->eventHandler->destroy($configuration->getEvents());
  }



  /**
   * @param Configuration $configuration
   * @param string        $key
   * @param array         $params
   *
   * @return Parameter
   */
  protected function getParameterObject(Configuration $configuration, $key, array $params)
  {
    $value = null;
    if (array_key_exists(strtolower($key), $params))
    {
      $value = $params[strtolower($key)];
    }
    $parameter = new Parameter($key, $value);
    $this->prepareDefault($configuration, $parameter);
    $this->normalizer->normalize($parameter, $configuration);

    return $parameter;
  }



  protected function prepareEventConfig($config)
  {
    foreach ($config['listeners'] as $key => $listener)
    {
      $config['listeners'][$key]['class'] = $this->classBuilder->getClass($listener['class']);
    }

    return $config;
  }



  protected function prepareValue(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::PREPARE_VALUE,
                       new TransformerEvent($configuration, $parameter)
    );

    switch ($configuration->getType())
    {
      case TypeEnum::COLLECTION_TYPE:
        $this->prepareCollection($configuration, $parameter);
        break;
      case TypeEnum::OBJECT_TYPE:
        $this->prepareObject($configuration, $parameter);
        break;
      case TypeEnum::DATE_TYPE:
        $this->prepareDate($configuration, $parameter);
        break;
      case TypeEnum::INDIVIDUAL_TYPE:
        $this->prepareIndividual($configuration, $parameter);
        break;
    }

    return $this;
  }



  protected function prepareDefault(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::PREPARE_DEFAULT,
                       new TransformerEvent($configuration, $parameter)
    );

    if ($parameter->getValue() === null)
    {
      $method = 'get' . ucfirst($configuration->getType()) . 'Options';
      if (method_exists($configuration->getOptions(), $method))
      {
        if (method_exists($configuration->getOptions()->{$method}(), 'getDefaultValue'))
        {
          $value = $configuration->getOptions()->{$method}()->getDefaultValue();
          $parameter->setValue($value);
        }
      }
    }

    return $this;
  }



  protected function prepareCollection(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::PREPARE_COLLECTION,
                       new TransformerEvent($configuration, $parameter)
    );

    $childs   = $parameter->getValue();
    $children = array();
    foreach ($childs as $key => $value)
    {
      $param = new Parameter($key, $value);
      $this->prepareObject($configuration, $param);
      array_push($children, $param->getValue());
    }
    $parameter->setValue($children);

    return $this;
  }



  protected function prepareObject(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::PREPARE_OBJECT,
                       new TransformerEvent($configuration, $parameter)
    );

    $returnClass = 'DEFAULT';
    switch ($configuration->getType())
    {
      case TypeEnum::OBJECT_TYPE:
        $returnClass = $configuration->getOptions()->getObjectOptions()->getReturnClass();
        break;
      case TypeEnum::COLLECTION_TYPE:
        $returnClass = $configuration->getOptions()->getCollectionOptions()->getReturnClass();
        break;
    }
    $parameter->setValue(
              $this->build(
                   $returnClass,
                     $configuration->getChildren(),
                     $parameter->getValue()
              )
    );

    return $this;
  }



  protected function prepareDate(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::PREPARE_DATE,
                       new TransformerEvent($configuration, $parameter)
    );

    $date = new \DateTime();
    foreach ($configuration->getOptions()->getDateOptions()->getFormat() as $format)
    {
      $date = \DateTime::createFromFormat($format, $parameter->getValue());
      if ($date instanceof \DateTime)
      {
        break;
      }
    }
    if ($configuration->getOptions()->getDateOptions()->getConvertToObject() === true)
    {
      $parameter->setValue($date);
    }
    elseif ($configuration->getOptions()->getDateOptions()->getConverToFormat() !== null)
    {
      $parameter->setValue($date->format($configuration->getOptions()->getDateOptions()->getConverToFormat()));
    }

    return $this;
  }



  protected function prepareIndividual(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::PREPARE_INDIVIDUAL,
                       new TransformerEvent($configuration, $parameter)
    );

    return $this;
  }
}