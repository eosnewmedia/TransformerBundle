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
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
   * @var \Symfony\Component\DependencyInjection\ParameterBag\ParameterBag
   */
  protected $globalParameterBag;

  /**
   * @var EventHandler
   */
  protected $eventHandler;



  public function __construct(
    EventDispatcherInterface $eventDispatcher,
    ValidatorInterface $validator,
    ParameterBag $parameterBag,
    Stopwatch $stopwatch
  )
  {
    $stopwatch->start('constructor', 'transformer');
    $this->stopwatch          = $stopwatch;
    $this->dispatcher         = $eventDispatcher;
    $this->globalParameterBag = $parameterBag;
    $this->classBuilder       = new ClassBuilder();
    $this->converter          = new Converter();
    $this->normalizer         = new Normalizer();
    $this->eventHandler       = new EventHandler($eventDispatcher, $this->classBuilder);
    $this->validator          = new Validator($eventDispatcher, $validator);
    $stopwatch->stop('constructor');
  }



  /**
   * @param string $config_key
   *
   * @return $this
   */
  protected function init($config_key)
  {
    $this->stopwatch->start('init', 'transformer');

    $global_config = $this->globalParameterBag->get('transformer.config');

    if ($global_config !== false && array_key_exists($config_key, $global_config))
    {
      $global_config[$config_key]['events'] = $this->prepareEventConfig($global_config[$config_key]['events']);
      $this->eventHandler->init($global_config[$config_key]['events']);
    }

    $this->stopwatch->stop('init');

    return $this;
  }



  /**
   * @param string $config_key
   *
   * @return $this
   */
  protected function destroy($config_key)
  {
    $this->stopwatch->start('destroy', 'transformer');

    $global_config = $this->globalParameterBag->get('transformer.config');
    if ($global_config !== false && array_key_exists($config_key, $global_config))
    {
      $this->eventHandler->destroy($global_config[$config_key]['events']);
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
      $this->stopwatch->start('transform', 'transformer');

      $params       = $this->converter->convertTo($params, ConversionEnum::ARRAY_CONVERSION);
      $config       = $this->converter->convertTo($config, ConversionEnum::ARRAY_CONVERSION);
      $configurator = new Configurator($config, $this->dispatcher);
      $returnClass  = $this->build($returnClass, $configurator->getConfig(), $params);

      $this->stopwatch->stop('transform');
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
      $configurator = new Configurator($config, $this->dispatcher);

      $returnClass = $this->reverseBuild(
                          '\stdClass',
                            $configurator->getConfig(),
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
   * @param string|object                                                 $returnClass
   * @param \ENM\TransformerBundle\ConfigurationStructure\Configuration[] $config_array
   * @param array                                                         $params
   *
   * @return object
   */
  protected function build($returnClass, array $config_array, array $params)
  {
    $params                = array_change_key_case($params, CASE_LOWER);
    $returnClassProperties = array();
    foreach ($config_array as $configuration)
    {
      $parameter = $this->getParameterObject($configuration, $params);
      $this->initRun($configuration, $parameter);

      $this->doRun($configuration, $parameter, $params);
      $returnClassProperties[$configuration->getKey()] = $parameter;

      $this->destroyRun($configuration, $parameter);
    }

    return $this->classBuilder->build($returnClass, $config_array, $returnClassProperties);
  }



  /**
   * @param string|object                                                 $returnClass
   * @param \ENM\TransformerBundle\ConfigurationStructure\Configuration[] $config_array
   * @param array                                                         $params
   *
   * @return object
   */
  protected function reverseBuild($returnClass, array $config_array, array $params)
  {
    $returnClassProperties = array();
    foreach ($config_array as $configuration)
    {
      $parameter = $this->getParameterObject($configuration, $params);
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

    return $this->classBuilder->build($returnClass, $config_array, $returnClassProperties);
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
    $this->stopwatch->start($configuration->getKey() . '.require', 'transformer');
    $this->validator->requireValue($configuration, $parameter, $params);
    $this->stopwatch->stop($configuration->getKey() . '.require');

    $this->stopwatch->start($configuration->getKey() . '.forbid', 'transformer');
    $this->validator->forbidValue($configuration, $parameter, $params);
    $this->stopwatch->stop($configuration->getKey() . '.forbid');

    $this->stopwatch->start($configuration->getKey() . '.validate', 'transformer');
    $this->validator->validate($configuration, $parameter);
    $this->stopwatch->stop($configuration->getKey() . '.validate');

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
   * @param array         $params
   *
   * @return Parameter
   */
  protected function getParameterObject(Configuration $configuration, array $params)
  {
    $parameter = new Parameter($configuration->getKey(), $this->getValue($configuration, $params));
    $this->prepareDefault($configuration, $parameter);
    $this->normalizer->normalize($parameter, $configuration);

    return $parameter;
  }



  /**
   * @param Configuration $configuration
   * @param array         $params
   *
   * @return mixed
   */
  protected function getValue(Configuration $configuration, array $params)
  {
    if (array_key_exists(strtolower($configuration->getKey()), $params))
    {
      return $params[strtolower($configuration->getKey())];
    }
    if (array_key_exists(strtolower($configuration->getRenameTo()), $params))
    {
      return $params[strtolower($configuration->getRenameTo())];
    }

    return null;
  }



  /**
   * @param array $config
   *
   * @return mixed
   */
  protected function prepareEventConfig(array $config)
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

    $child_array = $parameter->getValue();
    if (is_object($child_array))
    {
      $child_array = $this->converter->convertTo($child_array, 'array');
    }
    if (is_array($child_array))
    {
      $result_array = array();
      for ($i = 0; $i < count($child_array); $i++)
      {
        $param = new Parameter($i, $child_array[$i]);
        $this->prepareObject($configuration, $param);
        array_push($result_array, $param->getValue());
      }

      $parameter->setValue($result_array);
    }

    return $this;
  }



  protected function prepareObject(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::PREPARE_OBJECT,
                       new TransformerEvent($configuration, $parameter)
    );

    $returnClass = '';
    switch ($configuration->getType())
    {
      case TypeEnum::OBJECT_TYPE:
        $returnClass = $configuration->getOptions()->getObjectOptions()->getReturnClass();
        break;
      case TypeEnum::COLLECTION_TYPE:
        $returnClass = $configuration->getOptions()->getCollectionOptions()->getReturnClass();
        break;
    }
    $value = $this->build($returnClass, $configuration->getChildren(), $parameter->getValue());
    $parameter->setValue($value);

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