<?php


namespace ENM\TransformerBundle\ConfigurationStructure\OptionInterfaces;

interface NonComplexInterface
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
   * @param array $expected
   */
  public function setExpected(array $expected);



  /**
   * @return array
   */
  public function getExpected();
} 