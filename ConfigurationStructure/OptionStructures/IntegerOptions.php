<?php


namespace Enm\TransformerBundle\ConfigurationStructure\OptionStructures;

use Enm\TransformerBundle\ConfigurationStructure\OptionInterfaces\NumberInterface;

class IntegerOptions implements NumberInterface
{

  /**
   * @var null|integer
   */
  protected $min = null;

  /**
   * @var null|integer
   */
  protected $max = null;

  /**
   * @var array
   */
  protected $expected = array();

  /**
   * @var mixed
   */
  protected $default_value = null;



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
   * @param int|null $max
   */
  public function setMax($max)
  {
    $this->max = $max;

    return $this;
  }



  /**
   * @return int|null
   */
  public function getMax()
  {
    return $this->max;
  }



  /**
   * @param int|null $min
   */
  public function setMin($min)
  {
    $this->min = $min;

    return $this;
  }



  /**
   * @return int|null
   */
  public function getMin()
  {
    return $this->min;
  }
}
