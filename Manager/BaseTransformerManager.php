<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\ConversionEnum;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use ENM\TransformerBundle\ConfigurationStructure\TypeEnum;
use ENM\TransformerBundle\DependencyInjection\TransformerConfiguration;
use ENM\TransformerBundle\Event\ExceptionEvent;
use ENM\TransformerBundle\Event\TransformerEvent;
use ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException;
use ENM\TransformerBundle\Exceptions\TransformerException;
use ENM\TransformerBundle\Helper\ClassBuilder;
use ENM\TransformerBundle\Helper\Configurator;
use ENM\TransformerBundle\Helper\Converter;
use ENM\TransformerBundle\Helper\EventHandler;
use ENM\TransformerBundle\Helper\Normalizer;
use ENM\TransformerBundle\Helper\Validator;
use ENM\TransformerBundle\TransformerEvents;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
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
   * @var array
   */
  protected $global_configuration = array();

  /**
   * @var array
   */
  protected $local_configuration = array();

  /**
   * @var EventHandler
   */
  protected $eventHandler;



  public function __construct(
    EventDispatcherInterface $eventDispatcher,
    ValidatorInterface $validator,
    ParameterBag $parameterBag
  )
  {
    $this->dispatcher           = $eventDispatcher;
    $this->global_configuration = $parameterBag->get('transformer.config');
    $this->classBuilder         = new ClassBuilder($eventDispatcher);
    $this->converter            = new Converter();
    $this->normalizer           = new Normalizer($this->converter);
    $this->eventHandler         = new EventHandler($eventDispatcher, $this->classBuilder);
    $this->validator            = new Validator($eventDispatcher, $validator);
  }



  /**
   * @return $this
   */
  protected function init()
  {
    if (array_key_exists('events', $this->local_configuration))
    {
      $this->eventHandler->init($this->local_configuration['events']);
    }

    return $this;
  }



  /**
   * @return $this
   */
  protected function destroy()
  {
    if (array_key_exists('events', $this->local_configuration))
    {
      $this->eventHandler->destroy($this->local_configuration['events']);
    }
    $this->local_configuration = array();

    return $this;
  }



  /**
   * @param string|object $returnClass
   * @param mixed         $config
   * @param mixed         $params
   *
   * @return object
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  public function process($returnClass, $config, $params)
  {
    try
    {
      $this->init();

      $params       = $this->converter->convertTo($params, ConversionEnum::ARRAY_CONVERSION);
      $config       = $this->converter->convertTo($config, ConversionEnum::ARRAY_CONVERSION);
      $configurator = new Configurator($config, $this->dispatcher);
      $returnClass  = $this->build($returnClass, $configurator->getConfig(), $params);

      $this->destroy();

      return $returnClass;
    }
    catch (\Exception $e)
    {
      $this->dispatcher->dispatch(TransformerEvents::ON_EXCEPTION, new ExceptionEvent($e));
      $this->destroy();
      throw new TransformerException($e->getMessage());
    }
  }



  public function reverseProcess($config, $object)
  {
    try
    {
      $object = $this->process('\stdClass', $config, $object);

      $this->init();
      $configurator = new Configurator($config, $this->dispatcher);

      $returnClass = $this->reverseBuild(
                          '\stdClass',
                            $configurator->getConfig(),
                            $this->converter->convertTo($object, ConversionEnum::ARRAY_CONVERSION)
      );

      $this->destroy();

      return $returnClass;
    }
    catch (\Exception $e)
    {
      $this->dispatcher->dispatch(TransformerEvents::ON_EXCEPTION, new ExceptionEvent($e));
      throw new TransformerException($e->getMessage());
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
    $return_properties    = array();
    $return_configuration = array();
    foreach ($config_array as $configuration)
    {
      $parameter = $this->getParameterObject($configuration, $params);
      $this->initRun($configuration, $parameter);

      $modifiedConfiguration = clone $configuration;

      $this->dispatcher->dispatch(
                       TransformerEvents::REVERSE_TRANSFORM,
                         new TransformerEvent($modifiedConfiguration, $parameter)
      );

      $this->modifyConfiguration($modifiedConfiguration);

      if ($parameter->getValue() !== null)
      {
        switch ($modifiedConfiguration->getType())
        {
          case TypeEnum::COLLECTION_TYPE:
            $this->prepareCollection($modifiedConfiguration, $parameter, true);
            break;
          case TypeEnum::OBJECT_TYPE:
            $this->reverseObject($modifiedConfiguration, $parameter);
            break;
        }
      }

      $return_properties[$modifiedConfiguration->getKey()]    = $parameter;
      $return_configuration[$modifiedConfiguration->getKey()] = $modifiedConfiguration;

      $this->destroyRun($configuration, $parameter);
    }

    return $this->classBuilder->build($returnClass, $return_configuration, $return_properties);
  }



  protected function modifyConfiguration(Configuration $configuration)
  {
    if ($configuration->getRenameTo() !== null)
    {
      $rename = $configuration->getRenameTo();
      $configuration->setRenameTo($configuration->getKey());
      $configuration->setKey($rename);
    }
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function initRun(Configuration $configuration, Parameter $parameter)
  {
    $configuration->setEvents($this->setEventConfig($configuration->getEvents()));
    $this->eventHandler->init($configuration->getEvents());

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
    $this->validator->requireValue($configuration, $parameter, $params);

    $this->validator->forbidValue($configuration, $parameter, $params);

    if (!is_null($parameter->getValue()))
    {
      $this->validator->validate($configuration, $parameter);

      $this->prepareValue($configuration, $parameter);
    }
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
    $this->setDefaultIfNull($configuration, $parameter);
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
   * @param Configuration $configuration
   * @param Parameter     $parameter
   *
   * @return $this
   */
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



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   *
   * @return $this
   */
  protected function setDefaultIfNull(Configuration $configuration, Parameter $parameter)
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



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   * @param bool          $reverse
   *
   * @return $this
   */
  protected function prepareCollection(Configuration $configuration, Parameter $parameter, $reverse = false)
  {
    if ($reverse === false)
    {
      $this->dispatcher->dispatch(
                       TransformerEvents::PREPARE_COLLECTION,
                         new TransformerEvent($configuration, $parameter)
      );
    }
    else
    {
      $this->dispatcher->dispatch(
                       TransformerEvents::REVERSE_COLLECTION,
                         new TransformerEvent($configuration, $parameter)
      );
    }

    $child_array = $parameter->getValue();

    $result_array = array();
    for ($i = 0; $i < count($child_array); $i++)
    {
      $param = new Parameter($i, $child_array[$i]);
      if ($reverse === false)
      {
        $this->prepareObject($configuration, $param);
      }
      else
      {
        $this->reverseObject($configuration, $param);
      }

      array_push($result_array, $param->getValue());
    }

    $parameter->setValue($result_array);

    return $this;
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   *
   * @return $this
   */
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

    $value = $this->build(
                  $returnClass,
                    $configuration->getChildren(),
                    $this->converter->convertTo($parameter->getValue(), 'array')
    );
    $parameter->setValue($value);

    return $this;
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   *
   * @return $this
   */
  protected function reverseObject(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::REVERSE_OBJECT,
                       new TransformerEvent($configuration, $parameter)
    );

    $returnClass = '\stdClass';
    $value       = $this->reverseBuild(
                        $returnClass,
                          $configuration->getChildren(),
                          $this->converter->convertTo($parameter->getValue(), 'array')
    );
    $parameter->setValue($value);

    return $this;
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   *
   * @return $this
   */
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



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   *
   * @return $this
   */
  protected function prepareIndividual(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::PREPARE_INDIVIDUAL,
                       new TransformerEvent($configuration, $parameter)
    );

    return $this;
  }



  /**
   * @param mixed $local_config
   *
   * @return $this
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  protected function setLocalConfig($local_config)
  {
    try
    {
      if (is_null($local_config))
      {
        $this->local_configuration = array();

        return $this;
      }
      if ((is_string($local_config) && !json_decode($local_config) instanceof \stdClass))
      {
        if (!array_key_exists($local_config, $this->global_configuration))
        {
          throw new InvalidTransformerConfigurationException('Config Key "' . $local_config . '" does not exists!');
        }
        $this->local_configuration = $this->global_configuration[$local_config];

        $this->local_configuration['events'] = $this->setEventConfig($this->local_configuration['events']);

        return $this;
      }

      $config = $this->converter->convertTo($local_config, 'array');
      $key    = md5(time());

      $processor = new Processor();
      $config    = $processor->processConfiguration(
                             new TransformerConfiguration(),
                               array('enm_transformer' => [$key => $config])
      );

      $this->local_configuration = $config[$key];

      $this->local_configuration['events'] = $this->setEventConfig($this->local_configuration['events']);

      return $this;
    }
    catch (\Exception $e)
    {
      $this->dispatcher->dispatch(TransformerEvents::ON_EXCEPTION, new ExceptionEvent($e));
      throw new TransformerException($e->getMessage());
    }
  }



  /**
   * @param array $event_config
   *
   * @return array
   */
  protected function setEventConfig(array $event_config)
  {
    foreach ($event_config['listeners'] as $key => $listener)
    {
      $event_config['listeners'][$key]['class'] = $this->classBuilder->getObjectInstance($listener['class']);
    }

    return $event_config;
  }



  /**
   * @param $config
   *
   * @return object
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  protected function createEmptyObjectStructure($config)
  {
    try
    {
      $configurator = new Configurator($this->converter->convertTo($config, 'array'), $this->dispatcher);

      return $this->createPartOfStructure($configurator->getConfig());
    }
    catch (\Exception $e)
    {
      $this->dispatcher->dispatch(TransformerEvents::ON_EXCEPTION, new ExceptionEvent($e));
      throw new TransformerException($e->getMessage());
    }
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\Configuration[] $config
   *
   * @return object
   */
  protected function createPartOfStructure(array $config)
  {
    $return_properties    = array();
    $return_configuration = array();

    foreach ($config as $configuration)
    {
      $value = null;
      switch ($configuration->getType())
      {
        case TypeEnum::COLLECTION_TYPE:
          $value = array($this->createPartOfStructure($configuration->getChildren()));
          break;
        case TypeEnum::OBJECT_TYPE:
          $value = $this->createPartOfStructure($configuration->getChildren());
          break;
        case TypeEnum::ARRAY_TYPE:
          $value = array();
          break;
        default:
          break;
      }
      $this->modifyConfiguration($configuration);
      $parameter = new Parameter($configuration->getKey(), $value);

      $return_properties[$configuration->getKey()]    = $parameter;
      $return_configuration[$configuration->getKey()] = $configuration;
    }

    return $this->classBuilder->build('\stdClass', $return_configuration, $return_properties);
  }
}