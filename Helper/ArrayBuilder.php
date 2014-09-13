<?php


namespace ENM\TransformerBundle\Helper;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException;

class ArrayBuilder
{


  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\Configuration[] $config
   * @param \ENM\TransformerBundle\ConfigurationStructure\Parameter[]     $params
   *
   * @return array
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerConfigurationException
   */
  public function build(array $config, array $params)
  {
    $return = array();
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
      $return = $this->setValue($return, $configuration, $params[$configuration->getKey()]);
    }

    return $return;
  }



  /**
   * @param array         $return
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function setValue(array $return, Configuration $configuration, Parameter $parameter)
  {
    $key   = $configuration->getRenameTo() !== null ? $configuration->getRenameTo() : $configuration->getKey();
    $array = array($key => $parameter->getValue());

    return array_merge($return, $array);
  }
} 