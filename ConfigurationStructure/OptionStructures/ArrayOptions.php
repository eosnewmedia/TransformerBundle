<?php


namespace ENM\TransformerBundle\ConfigurationStructure\OptionStructures;

class ArrayOptions
{

  /**
   * @var bool
   */
  protected $is_associative = true;

  /**
   * @var array
   */
  protected $expected = array();

  /**
   * @var mixed
   */
  protected $default_value = null;

  protected $regex = null;



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
   * @param array $expected
   */
  public function setExpected($expected)
  {
    $this->expected = $expected;

    return $this;
  }



  /**
   * @return array
   */
  public function getExpected()
  {
    return $this->expected;
  }



  /**
   * @param boolean $is_associative
   */
  public function setIsAssociative($is_associative)
  {
    $this->is_associative = $is_associative;

    return $this;
  }



  /**
   * @return boolean
   */
  public function getIsAssociative()
  {
    return $this->is_associative;
  }



  /**
   * @param null $regex
   */
  public function setRegex($regex)
  {
    $this->regex = $regex;

    return $this;
  }



  /**
   * @return null
   */
  public function getRegex()
  {
    return $this->regex;
  }
}