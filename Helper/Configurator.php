<?php


namespace ENM\TransformerBundle\Helper;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\ArrayOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\BoolOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\CollectionOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\DateOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\FloatOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\IndividualOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\IntegerOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\ObjectOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\RequiredIfStructure;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\StringOptions;
use ENM\TransformerBundle\ConfigurationStructure\TypeEnum;
use ENM\TransformerBundle\DependencyInjection\TransformerConfiguration;
use ENM\TransformerBundle\Event\ConfigurationEvent;
use ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException;
use ENM\TransformerBundle\Exceptions\TransformerException;
use ENM\TransformerBundle\TransformerEvents;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Configurator
{

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\Configuration[]
   */
  protected $configuration;

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $dispatcher;



  public function __construct(EventDispatcherInterface $eventDispatcher)
  {
    $this->configuration = array();
    $this->dispatcher    = $eventDispatcher;
  }



  /**
   * @param array $config
   *
   * @return \ENM\TransformerBundle\ConfigurationStructure\Configuration[]
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  public function getConfig(array $config)
  {
    try
    {
      $config = $this->validateConfiguration($config);
      foreach ($config as $key => $settings)
      {
        $this->configuration[$key] = new Configuration($key);
        $this->setBaseConfiguration($settings, $key);
        $this->setBaseOptions($settings, $key);
        $this->{'set' . ucfirst($settings['type']) . 'Options'}($settings, $key);
        $this->getChildren($settings, $key);
        $this->dispatcher->dispatch(
                         TransformerEvents::AFTER_CONFIGURATION,
                           new ConfigurationEvent($this->configuration[$key])
        );
      }

      return $this->configuration;
    }
    catch (\Exception $e)
    {
      throw new TransformerException($e->getMessage() . ' --- ' . $e->getFile() . ': ' . $e->getLine());
    }
  }



  /**
   * @param array $config
   *
   * @return array
   */
  protected function validateConfiguration(array $config)
  {
    $processor = new Processor();

    return $processor->processConfiguration(new TransformerConfiguration(), array('config' => $config));
  }



  /**
   * @param array  $config
   * @param string $key
   */
  protected function setBaseConfiguration(array $config, $key)
  {
    $this->configuration[$key]->setType($config['type']);
    $this->configuration[$key]->setRenameTo($config['renameTo']);
  }



  /**
   * @param array  $config
   * @param string $key
   *
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException
   */
  protected function getChildren(array $config, $key)
  {
    if (in_array($this->configuration[$key]->getType(), array(TypeEnum::COLLECTION_TYPE, TypeEnum::OBJECT_TYPE)))
    {
      $this->configuration[$key]->setChildren($config['children']);
      $this->dispatcher->dispatch(
                       TransformerEvents::BEFORE_CHILD_CONFIGURATION,
                         new ConfigurationEvent($this->configuration[$key])
      );

      $configuration = new self($this->dispatcher);
      $children      = $configuration->getConfig($this->configuration[$key]->getChildren());

      $this->configuration[$key]->setChildren($children);

      if (!count($this->configuration[$key]->getChildren()) > 0)
      {
        throw new InvalidTransformerConfigurationException('A child configuration is required for type '
                                                           . $this->configuration[$key]->getType());
      }

      $this->dispatcher->dispatch(
                       TransformerEvents::AFTER_CHILD_CONFIGURATION,
                         new ConfigurationEvent($this->configuration[$key])
      );
    }
  }



  /**
   * @param array  $config
   * @param string $key
   */
  protected function setBaseOptions(array $config, $key)
  {
    $options = $this->configuration[$key]->getOptions();
    $options->setRequired($config['options']['required']);
    $options->setForbiddenIfAvailable(
            array_change_key_case($config['options']['forbiddenIfAvailable'], CASE_LOWER)
    );
    $options->setForbiddenIfNotAvailable(
            array_change_key_case($config['options']['forbiddenIfNotAvailable'], CASE_LOWER)
    );
    $options->setRequiredIfAvailable(
            new RequiredIfStructure(
              array_change_key_case($config['options']['requiredIfAvailable']['or'], CASE_LOWER),
              array_change_key_case($config['options']['requiredIfAvailable']['and'], CASE_LOWER)
            )
    );
    $options->setRequiredIfNotAvailable(
            new RequiredIfStructure(
              array_change_key_case($config['options']['requiredIfNotAvailable']['or'], CASE_LOWER),
              array_change_key_case($config['options']['requiredIfNotAvailable']['and'], CASE_LOWER)
            )
    );
  }



  /**
   * @param array  $config
   * @param string $key
   */
  protected function setArrayOptions(array $config, $key)
  {
    $options = new ArrayOptions();
    $options->setDefaultValue($config['options']['defaultValue']);
    $options->setExpected($config['options']['expected']);
    $options->setIsAssociative($config['options']['assoc']);
    $this->configuration[$key]->getOptions()->setArrayOptions($options);
  }



  /**
   * @param array  $config
   * @param string $key
   */
  protected function setBoolOptions(array $config, $key)
  {
    $options = new BoolOptions();
    $options->setExpected($config['options']['expected']);
    $options->setDefaultValue($config['options']['defaultValue']);
    $this->configuration[$key]->getOptions()->setBoolOptions($options);
  }



  /**
   * @param array  $config
   * @param string $key
   */
  protected function setCollectionOptions(array $config, $key)
  {
    $options = new CollectionOptions();
    $options->setDefaultValue($config['options']['defaultValue']);
    $options->setReturnClass($config['options']['returnClass']);
    $this->configuration[$key]->getOptions()->setCollectionOptions($options);
  }



  /**
   * @param array  $config
   * @param string $key
   */
  protected function setDateOptions(array $config, $key)
  {
    $options = new DateOptions();
    $format  = $config['options']['date']['format'];
    if (is_array($format))
    {
      $options->setFormat($format);
    }
    else
    {
      $options->setFormat(array($format));
    }
    $options->setConvertToObject($config['options']['date']['convertToObject']);
    $options->setConverToFormat($config['options']['date']['convertToFormat']);
    $this->configuration[$key]->getOptions()->setDateOptions($options);
  }



  /**
   * @param array  $config
   * @param string $key
   */
  protected function setFloatOptions(array $config, $key)
  {
    $options = new FloatOptions();
    $options->setExpected($config['options']['expected']);
    $options->setDefaultValue($config['options']['defaultValue']);
    $options->setMax($config['options']['max']);
    $options->setMin($config['options']['min']);
    $this->configuration[$key]->getOptions()->setFloatOptions($options);
  }



  /**
   * @param array  $config
   * @param string $key
   */
  protected function setIntegerOptions(array $config, $key)
  {
    $options = new IntegerOptions();
    $options->setExpected($config['options']['expected']);
    $options->setDefaultValue($config['options']['defaultValue']);
    $options->setMax($config['options']['max']);
    $options->setMin($config['options']['min']);
    $this->configuration[$key]->getOptions()->setIntegerOptions($options);
  }



  /**
   * @param array  $config
   * @param string $key
   */
  protected function setIndividualOptions(array $config, $key)
  {
    $options = new IndividualOptions();
    $options->setOptions($config['options']['individual']);
    $this->configuration[$key]->getOptions()->setIndividualOptions($options);
  }



  /**
   * @param array  $config
   * @param string $key
   */
  protected function setObjectOptions(array $config, $key)
  {
    $options = new ObjectOptions();
    $options->setDefaultValue($config['options']['defaultValue']);
    $options->setReturnClass($config['options']['returnClass']);
    $this->configuration[$key]->getOptions()->setObjectOptions($options);
  }



  /**
   * @param array  $config
   * @param string $key
   */
  protected function setStringOptions(array $config, $key)
  {
    $options = new StringOptions();
    $options->setRegex($config['options']['regex']);
    $options->setValidation($config['options']['stringValidation']);
    $options->setExpected($config['options']['expected']);
    $options->setDefaultValue($config['options']['defaultValue']);
    $options->setMax($config['options']['length']['max']);
    $options->setMin($config['options']['length']['min']);
    $this->configuration[$key]->getOptions()->setStringOptions($options);
  }
} 