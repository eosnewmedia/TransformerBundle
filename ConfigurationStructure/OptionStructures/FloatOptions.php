<?php


namespace ENM\TransformerBundle\ConfigurationStructure\OptionStructures;

use ENM\TransformerBundle\ConfigurationStructure\OptionInterfaces\NumberInterface;

class FloatOptions implements NumberInterface
{

  /**
   * @var null|float
   */
  protected $min = null;

  /**
   * @var null|float
   */
  protected $max = null;

  /**
   * @var null|int
   */
  protected $round = null;

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
   * @param null $max
   */
  public function setMax($max)
  {
    $this->max = floatval($max);

    return $this;
  }



  /**
   * @return float|null
   */
  public function getMax()
  {
    return $this->max;
  }



  /**
   * @param float $min
   */
  public function setMin($min)
  {
    $this->min = floatval($min);

    return $this;
  }



  /**
   * @return float|null
   */
  public function getMin()
  {
    return $this->min;
  }



  /**
   * @param int|null $round
   */
  public function setRound($round)
  {
    if (!is_null($round))
    {
      $this->round = intval($round);
    }

    return $this;
  }



  /**
   * @return int|null
   */
  public function getRound()
  {
    return $this->round;
  }
}