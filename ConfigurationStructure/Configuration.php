<?php


namespace ENM\TransformerBundle\ConfigurationStructure;

class Configuration
{

  /**
   * @var string
   */
  protected $type = null;

  /**
   * @var string
   */
  protected $renameTo = null;

  /**
   * @var array
   */
  protected $children = array();

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\ConfigurationOptions
   */
  protected $options;



  function __construct()
  {
    $this->options = new ConfigurationOptions();
  }



  /**
   * @param array
   */
  public function setChildren(array $children)
  {
    $this->children = $children;

    return $this;
  }



  /**
   * @return array
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