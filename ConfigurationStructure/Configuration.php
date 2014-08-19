<?php


namespace ENM\TransformerBundle\ConfigurationStructure;

class Configuration
{

  /**
   * @var string
   */
  protected $key;

  /**
   * @var string
   */
  protected $type = null;

  /**
   * @var string
   */
  protected $renameTo = null;

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\Configuration[]|array
   */
  protected $children = array();

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\ConfigurationOptions
   */
  protected $options;



  public function __construct($key)
  {
    $this->key     = $key;
    $this->options = new ConfigurationOptions();
  }



  /**
   * @param string $key
   */
  public function setKey($key)
  {
    $this->key = $key;

    return $this;
  }



  /**
   * @return string
   */
  public function getKey()
  {
    return $this->key;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\Configuration[]|array
   */
  public function setChildren(array $children)
  {
    $this->children = $children;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\Configuration[]|array
   */
  public function getChildren()
  {
    return $this->children;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\ConfigurationOptions $options
   */
  public function setOptions(ConfigurationOptions $options)
  {
    $this->options = $options;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\ConfigurationOptions
   */
  public function getOptions()
  {
    return $this->options;
  }



  /**
   * @param string $renameTo
   */
  public function setRenameTo($renameTo)
  {
    $this->renameTo = $renameTo;

    return $this;
  }



  /**
   * @return string
   */
  public function getRenameTo()
  {
    return $this->renameTo;
  }



  /**
   * @param string $type
   */
  public function setType($type)
  {
    $this->type = $type;

    return $this;
  }



  /**
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }
}