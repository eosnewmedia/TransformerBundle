<?php


namespace ENM\TransformerBundle\ConfigurationStructure\OptionStructures;

use ENM\TransformerBundle\ConfigurationStructure\OptionInterfaces\NonComplexInterface;

class StringOptions implements NonComplexInterface
{

  /**
   * @var array
   */
  protected $expected = array();

  /**
   * @var mixed
   */
  protected $default_value = null;

  /**
   * @var string|null
   */
  protected $validation = null;

  /**
   * @var bool
   */
  protected $strongValidation = false;

  /**
   * @var string|null
   */
  protected $regex = null;

  /**
   * @var integer|float|null
   */
  protected $min = null;

  /**
   * @var integer|float|null
   */
  protected $max = null;



  /**
   * @param mixed $default_value
   *
   * @return $this
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
   *
   * @return $this
   */
  public function setExpected(array $expected)
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
   * @param $strongValidation
   *
   * @return $this
   */
  public function setStrongValidation($strongValidation)
  {
    $this->strongValidation = $strongValidation;

    return $this;
  }



  /**
   * @return boolean
   */
  public function getStrongValidation()
  {
    return $this->strongValidation;
  }



  /**
   * @param $max
   *
   * @return $this
   */
  public function setMax($max)
  {
    $this->max = $max;

    return $this;
  }



  /**
   * @return float|int|null
   */
  public function getMax()
  {
    return $this->max;
  }



  /**
   * @param $min
   *
   * @return $this
   */
  public function setMin($min)
  {
    $this->min = $min;

    return $this;
  }



  /**
   * @return float|int|null
   */
  public function getMin()
  {
    return $this->min;
  }



  /**
   * @param $regex
   *
   * @return $this
   */
  public function setRegex($regex)
  {
    $this->regex = $regex;

    return $this;
  }



  /**
   * @return null|string
   */
  public function getRegex()
  {
    return $this->regex;
  }



  /**
   * @param $validation
   *
   * @return $this
   */
  public function setValidation($validation)
  {
    $this->validation = $validation;

    return $this;
  }



  /**
   * @return null|string
   */
  public function getValidation()
  {
    return $this->validation;
  }
}