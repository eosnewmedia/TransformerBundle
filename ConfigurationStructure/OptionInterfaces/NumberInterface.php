<?php


namespace Enm\TransformerBundle\ConfigurationStructure\OptionInterfaces;

interface NumberInterface extends NonComplexInterface
{

  /**
   * @param float|integer $max
   */
  public function setMax($max);



  /**
   * @return float|null|integer
   */
  public function getMax();



  /**
   * @param float|integer $min
   */
  public function setMin($min);



  /**
   * @return float|null|integer
   */
  public function getMin();
}
