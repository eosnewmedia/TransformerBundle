<?php


namespace ENM\TransformerBundle\ConfigurationStructure\OptionStructures;

use ENM\TransformerBundle\ConfigurationStructure\OptionInterfaces\ComplexInterface;

class CollectionOptions implements ComplexInterface
{

  /**
   * @var mixed
   */
  protected $default_value = null;

  /**
   * @var string|object|null
   */
  protected $returnClass = null;



  /**
   * @param mixed $default_value
   */
  public function setDefaultValue($default_value)
  {
    $this->default_value = $default_value;

    return $this;
  }



  /**
   * @return mixed
   */
  public function getDefaultValue()
  {
    return $this->default_value;
  }



  /**
   * @param string|object $returnClass
   */
  public function setReturnClass($returnClass)
  {
    $this->returnClass = $returnClass;

    return $this;
  }



  /**
   * @return string|object
   */
  public function getReturnClass()
  {
    return $this->returnClass;
  }
}