<?php


namespace Enm\TransformerBundle\ConfigurationStructure;

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
   * @var \Enm\TransformerBundle\ConfigurationStructure\Configuration[]|array
   */
  protected $children = array();

  /**
   * @var null|Configuration
   */
  protected $parent = null;

  /**
   * @var \Enm\TransformerBundle\ConfigurationStructure\ConfigurationOptions
   */
  protected $options;

  /**
   * @var array
   */
  protected $events = array();



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
   * @param \Enm\TransformerBundle\ConfigurationStructure\Configuration[]|array
   */
  public function setChildren(array $children)
  {
    $this->children = $children;

    return $this;
  }



  /**
   * @return \Enm\TransformerBundle\ConfigurationStructure\Configuration[]|array
   */
  public function getChildren()
  {
    return $this->children;
  }



  /**
   * @param \Enm\TransformerBundle\ConfigurationStructure\ConfigurationOptions $options
   */
  public function setOptions(ConfigurationOptions $options)
  {
    $this->options = $options;

    return $this;
  }



  /**
   * @return \Enm\TransformerBundle\ConfigurationStructure\ConfigurationOptions
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



  /**
   * @param array $events
   */
  public function setEvents(array $events)
  {
    $this->events = $events;

    return $this;
  }



  /**
   * @return array
   */
  public function getEvents()
  {
    return $this->events;
  }



  /**
   * @param \Enm\TransformerBundle\ConfigurationStructure\Configuration $parent
   */
  public function setParent(Configuration $parent = null)
  {
    $this->parent = $parent;

    return $this;
  }



  /**
   * @return \Enm\TransformerBundle\ConfigurationStructure\Configuration|null
   */
  public function getParent()
  {
    return $this->parent;
  }
}