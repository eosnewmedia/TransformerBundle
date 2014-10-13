<?php


namespace Enm\TransformerBundle\Helper;

use Enm\TransformerBundle\ConfigurationStructure\Configuration;
use Enm\TransformerBundle\ConfigurationStructure\Parameter;
use Enm\TransformerBundle\Exceptions\InvalidTransformerConfigurationException;

class ArrayBuilder
{


  /**
   * @param \Enm\TransformerBundle\ConfigurationStructure\Configuration[] $config
   * @param \Enm\TransformerBundle\ConfigurationStructure\Parameter[]     $params
   *
   * @return array
   * @throws \Enm\TransformerBundle\Exceptions\InvalidTransformerConfigurationException
   */
  public function build(array $config, array $params)
  {
    $return = array();
    foreach ($config as $configuration)
    {
      if (!$configuration instanceof Configuration)
      {
        throw new InvalidTransformerConfigurationException(
          'Parameter "config" have to be an array of Configuration-Objects!'
        );
      }
      if (!$params[$configuration->getKey()] instanceof Parameter)
      {
        throw new InvalidTransformerConfigurationException(
          'Parameter "params" have to be an array of Parameter-Objects!'
        );
      }
      $return = $this->setValue($return, $configuration, $params[$configuration->getKey()]);
    }

    return $return;
  }



  /**
   * @param array         $return
   * @param Configuration $configuration
   * @param Parameter     $parameter
   *
   * @return array
   */
  protected function setValue(array $return, Configuration $configuration, Parameter $parameter)
  {
    $key   = $configuration->getRenameTo() !== null ? $configuration->getRenameTo() : $configuration->getKey();
    $array = array($key => $parameter->getValue());

    return array_merge($return, $array);
  }
}
