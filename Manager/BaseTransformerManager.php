<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\ConversionEnum;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use ENM\TransformerBundle\ConfigurationStructure\TypeEnum;
use ENM\TransformerBundle\Event\ExceptionEvent;
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

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\Configuration[]
   */
  protected $configuration;



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
   * @param mixed  $config
   * @param string $config_key
   *
   * @return $this
   */
  protected function init($config, $config_key)
  {
    $this->stopwatch->start('init', 'transformer');

    $config              = $this->converter->convertTo($config, ConversionEnum::ARRAY_CONVERSION);
    $configurator        = new Configurator($config, $this->dispatcher);
    $this->configuration = $configurator->getConfig();

    $global_config = $this->container->getParameter('transformer.config');
    if ($global_config !== false && array_key_exists($config_key, $global_config))
    {
      $global_config[$config_key]['events'] = $this->prepareEventConfig($global_config[$config_key]['events']);
      $this->eventHandler->init($global_config[$config_key]['events']);
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
    $this->configuration = array();

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
      $this->init($config, $config_key);

      $params = $this->converter->convertTo($params, ConversionEnum::ARRAY_CONVERSION);

      $returnClass = $this->build($returnClass, $params);

      $this->destroy($config_key);

      return $returnClass;
    }
    catch (\Exception $e)
    {
      $this->dispatcher->dispatch(TransformerEvents::ON_EXCEPTION, new ExceptionEvent($e));
      $this->destroy($config_key);
      throw new TransformerException($e->getMessage());
    }
  }



  public function reverseProcess($config, $object, $config_key = null)
  {
    try
    {
      $object = $this->process('\stdClass', $config, $object, $config_key);

      $this->init($config, $config_key);

      $returnClass = $this->reverseBuild(
                          '\stdClass',
                            $this->converter->convertTo($object, ConversionEnum::ARRAY_CONVERSION)
      );

      $this->destroy($config, $config_key);

      return $returnClass;
    }
    catch (\Exception $e)
    {
      $this->dispatcher->dispatch(TransformerEvents::ON_EXCEPTION, new ExceptionEvent($e));
      throw new TransformerException($e->getMessage() . ' --- ' . $e->getFile() . ' - ' . $e->getLine());
    }
  }



  /**
   * @param string|object $returnClass
   * @param array         $params
   *
   * @return object
   */
  protected function build($returnClass, array $params)
  {
    $params                = array_change_key_case($params, CASE_LOWER);
    $returnClassProperties = array();
    foreach ($this->configuration as $configuration)
    {
      $parameter = $this->getParameterObject($configuration, $configuration->getKey(), $params);
      $this->initRun($configuration, $parameter);

      $this->doRun($configuration, $parameter, $params);
      $returnClassProperties[$configuration->getKey()] = $parameter;

      $this->destroyRun($configuration, $parameter);
    }

    return $this->classBuilder->build($returnClass, $this->configuration, $returnClassProperties);
  }



  protected function reverseBuild($returnClass, array $params)
  {
    $returnClassProperties = array();
    foreach ($this->configuration as $configuration)
    {
      $parameter = $this->getParameterObject($configuration, $configuration->getKey(), $params);
      $this->initRun($configuration, $parameter);

      if ($configuration->getRenameTo() !== null)
      {
        $rename = $configuration->getRenameTo();
        $configuration->setRenameTo($configuration->getKey());
        $configuration->setKey($rename);
      }

      $returnClassProperties[$configuration->getKey()] = $parameter;

      $this->destroyRun($configuration, $parameter);
    }

    return $this->classBuilder->build($returnClass, $this->configuration, $returnClassProperties);
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function initRun(Configuration $configuration, Parameter $parameter)
  {
    $configuration->setEvents($this->prepareEventConfig($configuration->getEvents()));
    $this->eventHandler->init($configuration->getEvents());

    $this->stopwatch->start($configuration->getKey(), 'transformer');

    $this->dispatcher->dispatch(
                     TransformerEvents::BEFORE_RUN,
                       new TransformerEvent($configuration, $parameter)
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
   * @param Parameter     $parameter
   */
  protected function destroyRun(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::AFTER_RUN,
                       new TransformerEvent($configuration, $parameter)
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
    elseif (array_key_exists(strtolower($configuration->getRenameTo()), $params))
    {
      $value = $params[strtolower($configuration->getRenameTo())];
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