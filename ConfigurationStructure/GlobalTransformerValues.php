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
   * @var bool
   */
  protected $param_edited = false;

  /**
   * @var array
   */
  protected $config_array;

  /**
   * @var bool
   */
  protected $config_edited = false;



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
   * @param array $array
   * @param bool  $edit
   *
   * @throws \ENM\TransformerBundle\Exceptions\TransformerConfigurationException
   */
  public function setConfig(array $array, $edit = false)
  {
    if (is_array($this->config_array))
    {
      if ($edit === false)
      {
        throw new TransformerConfigurationException('Config-Array already defined!');
      }
      else
      {
        $this->config_edited = true;
      }
    }
    $this->config_array = $array;
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
   * @param array $array
   * @param bool  $edit
   *
   * @throws \ENM\TransformerBundle\Exceptions\TransformerConfigurationException
   */
  public function setParams(array $array, $edit = false)
  {
    if (is_array($this->param_array))
    {
      if ($edit === false)
      {
        throw new TransformerConfigurationException('Param-Array already defined!');
      }
      else
      {
        $this->param_edited = true;
      }
    }
    $this->param_array = $array;
  }



  /**
   * @return boolean
   */
  public function isConfigEdited()
  {
    return $this->config_edited;
  }



  /**
   * @return boolean
   */
  public function isParamEdited()
  {
    return $this->param_edited;
  }
}