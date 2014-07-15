<?php


namespace ENM\TransformerBundle\Resources\TestClass;

class HobbyTestClass
{

  /**
   * @var string
   */
  protected $name;

  /**
   * @var float
   */
  protected $years;

  /**
   * @var integer
   */
  protected $days_in_week;



  /**
   * @param int $days_in_week
   */
  public function setDaysInWeek($days_in_week)
  {
    $this->days_in_week = $days_in_week;

    return $this;
  }



  /**
   * @return int
   */
  public function getDaysInWeek()
  {
    return $this->days_in_week;
  }



  /**
   * @param string $name
   */
  public function setName($name)
  {
    $this->name = $name;

    return $this;
  }



  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }



  /**
   * @param float $years
   */
  public function setYears($years)
  {
    $this->years = $years;

    return $this;
  }



  /**
   * @return float
   */
  public function getYears()
  {
    return $this->years;
  }
}