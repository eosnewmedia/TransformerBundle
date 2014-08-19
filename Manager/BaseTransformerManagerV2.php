<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use ENM\TransformerBundle\ConfigurationStructure\TypeEnum;
use ENM\TransformerBundle\Event\TransformerEvent;
use ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException;
use ENM\TransformerBundle\Exceptions\TransformerException;
use ENM\TransformerBundle\TransformerEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseTransformerManagerV2
{

  /**
   * @var ClassBuilderManager
   */
  protected $classBuilder;


  /**
   * @var ValidationManager
   */
  protected $validator;

  /**
   * @var NormalizerManager
   */
  protected $normalizer;

  /**
   * @var ConfigurationManager
   */
  protected $configurationManager;

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $dispatcher;



  public function __construct(EventDispatcherInterface $dispatcher, ValidatorInterface $validator)
  {
    $this->classBuilder         = new ClassBuilderManager($dispatcher);
    $this->configurationManager = new ConfigurationManager($dispatcher);
    $this->validator            = new ValidationManager($dispatcher, $validator);
    $this->normalizer           = new NormalizerManager($dispatcher);
    $this->dispatcher           = $dispatcher;
  }



  public function process($returnClass, array $config, $params)
  {
    try
    {
      $config = $this->configurationManager->getConfig($config);

      return $this->build($returnClass, $config, $params);
    }
    catch (\Exception $e)
    {
      throw new TransformerException($e->getMessage() . ' --- ' . $e->getFile() . ' - ' . $e->getLine());
    }
  }



  /**
   * @param string|object   $returnClass
   * @param Configuration[] $config
   * @param array           $params
   */
  protected function build($returnClass, array $config, array $params)
  {
    $params                = array_change_key_case($params, CASE_LOWER);
    $returnClassProperties = array();
    foreach ($config as $key => $configuration)
    {
      $parameter = $this->getParameterObject($configuration, $key, $params);
      $this->validator->validate($configuration, $parameter);
      $this->prepareValue($configuration, $parameter);
      $returnClassProperties[$key] = $parameter;
    }

    return $this->classBuilder->build($returnClass, $config, $returnClassProperties);
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
      $value = $params[$key];
    }
    $parameter = new Parameter($key, $value);
    $this->normalizer->normalize($parameter, $configuration);

    return $parameter;
  }



  protected function prepareValue(Configuration $configuration, Parameter $parameter)
  {
    $this->prepareDefault($configuration, $parameter);
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
  }



  protected function prepareDefault(Configuration $configuration, Parameter $parameter)
  {
    if ($parameter->getValue() === null)
    {
      $method = 'get' . ucfirst($configuration->getType()) . 'Options';
      if (method_exists($configuration, $method))
      {
        if (method_exists($configuration->{$method}(), 'getDefaultValue'))
        {
          $parameter->setValue($configuration->getOptions()->{$method}()->getDefaultValue());
        }
      }
    }
  }



  protected function prepareCollection(Configuration $configuration, Parameter $parameter)
  {
    $childs   = $parameter->getValue();
    $children = array();
    foreach ($childs as $key => $value)
    {
      $param = new Parameter($key, $value);
      $this->prepareObject($configuration, $param);
      array_push($children, $param->getValue());
    }
    $parameter->setValue($children);
  }



  protected function prepareObject(Configuration $configuration, Parameter $parameter)
  {
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
  }



  protected function prepareDate(Configuration $configuration, Parameter $parameter)
  {
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
  }



  protected function prepareIndividual(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::PREPARE_INDIVIDUAL,
                       new TransformerEvent($configuration, $parameter)
    );
  }
}