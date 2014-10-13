<?php


namespace Enm\TransformerBundle\ConfigurationStructure\OptionStructures;

use Enm\TransformerBundle\ConfigurationStructure\OptionInterfaces\ComplexInterface;

class ObjectOptions implements ComplexInterface
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
   * @param null|object|string $returnClass
   */
  public function setReturnClass($returnClass)
  {
    $this->returnClass = $returnClass;

    return $this;
  }



  /**
   * @return null|object|string
   */
  public function getReturnClass()
  {
    return $this->returnClass;
  }
}
