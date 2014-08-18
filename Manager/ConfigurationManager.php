<?php


namespace ENM\TransformerBundle\Manager;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\RequiredIfStructure;
use ENM\TransformerBundle\DependencyInjection\TransformerConfiguration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationManager
{

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\Configuration[]
   */
  protected $configuration;



  public function __construct()
  {
    $this->configuration = array();
  }



  /**
   * @param array $config
   *
   * @return Configuration
   */
  public function getConfig(array $config)
  {
    $config = $this->validateConfiguration($config);

    foreach ($config as $key => $settings)
    {
      $this->configuration[$key] = new Configuration();
      $this->setBaseConfiguration($settings, $key);
      $this->setBaseOptions($settings, $key);
      $this->{'set' . ucfirst($settings['type']) . 'Options'}($settings, $key);
    }

    return $this->configuration;
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



  protected function setBaseConfiguration(array $config, $key)
  {
    $this->configuration[$key]->setType($config['type']);
    $this->configuration[$key]->setRenameTo($config['renameTo']);
    $this->configuration[$key]->setChildren($config['children']);
  }



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



  protected function setArrayOptions(array $config, $key)
  {
  }



  protected function setBoolOptions(array $config, $key)
  {
  }



  protected function setCollectionOptions(array $config, $key)
  {
  }



  protected function setDateOptions(array $config, $key)
  {
  }



  protected function setFloatOptions(array $config, $key)
  {
  }



  protected function setIntegerOptions(array $config, $key)
  {
  }



  protected function setMethodOptions(array $config, $key)
  {
  }



  protected function setObjectOptions(array $config, $key)
  {
  }



  protected function setStringOptions(array $config, $key)
  {
  }
}