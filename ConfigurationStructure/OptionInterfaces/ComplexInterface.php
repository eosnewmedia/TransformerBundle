<?php


namespace ENM\TransformerBundle\ConfigurationStructure\OptionInterfaces;

interface ComplexInterface
{

  /**
   * @param mixed $default_value
   */
  public function setDefaultValue($default_value);



  /**
   * @return mixed
   */
  public function getDefaultValue();



  /**
   * @param string|object $returnClass
   */
  public function setReturnClass($returnClass);



  /**
   * @return string|object
   */
  public function getReturnClass();
} 