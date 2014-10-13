<?php


namespace Enm\TransformerBundle\ConfigurationStructure\OptionStructures;

class ArrayOptions
{

  /**
   * @var bool
   */
  protected $associative = true;

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
   * @param $default_value
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
   * @return bool
   */
  public function isAssociative()
  {
    return $this->associative;
  }



  /**
   * @param $associative
   *
   * @return $this
   */
  public function setAssociative($associative)
  {
    $this->associative = $associative;

    return $this;
  }
}
