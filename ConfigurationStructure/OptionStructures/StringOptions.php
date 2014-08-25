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
   * @param float|int|null $max
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
   * @param float|int|null $min
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
   * @param null|string $regex
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
   * @param null|string $validation
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