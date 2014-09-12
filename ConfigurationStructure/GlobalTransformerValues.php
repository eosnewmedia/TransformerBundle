<?php


namespace ENM\TransformerBundle\ConfigurationStructure;

use ENM\TransformerBundle\Exceptions\TransformerConfigurationException;

/**
 * Class GlobalTransformerValues
 * Singleton-Pattern
 *
 * @package ENM\TransformerBundle\ConfigurationStructure
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
class GlobalTransformerValues
{

  /**
   * @var self
   */
  protected static $instance = null;

  /**
   * @var array
   */
  protected $param_array;

  /**
   * @var array
   */
  protected $config_array;



  private function __construct()
  {
  }



  private function __clone()
  {
  }



  public static function createNewInstance()
  {
    self::$instance = new self();

    return self::$instance;
  }



  /**
   * @return self
   */
  public static function getInstance()
  {
    if (null === self::$instance)
    {
      self::createNewInstance();
    }

    return self::$instance;
  }



  /**
   * @return array
   * @throws \ENM\TransformerBundle\Exceptions\TransformerConfigurationException
   */
  public function getConfig()
  {
    if (!is_array($this->config_array))
    {
      throw new TransformerConfigurationException('Config-Array is not defined!');
    }

    return $this->config_array;
  }



  /**
   * @param array $full_array
   *
   * @throws \ENM\TransformerBundle\Exceptions\TransformerConfigurationException
   */
  public function setConfig(array $full_array)
  {
    if (is_array($this->config_array))
    {
      throw new TransformerConfigurationException('Config-Array already defined!');
    }
    $this->config_array = $full_array;
  }



  /**
   * @return array
   * @throws \ENM\TransformerBundle\Exceptions\TransformerConfigurationException
   */
  public function getParams()
  {
    if (!is_array($this->param_array))
    {
      throw new TransformerConfigurationException('Param-Array is not defined!');
    }

    return $this->param_array;
  }



  /**
   * @param array $full_array
   *
   * @throws \ENM\TransformerBundle\Exceptions\TransformerConfigurationException
   */
  public function setParams(array $full_array)
  {
    if (is_array($this->param_array))
    {
      throw new TransformerConfigurationException('Param-Array already defined!');
    }
    $this->param_array = $full_array;
  }
}