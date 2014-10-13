<?php


namespace Enm\TransformerBundle\ConfigurationStructure\OptionStructures;

class DateOptions
{

  /**
   * @var array
   */
  protected $expectedFormat = array('Y-m-d', 'd.m.Y');

  /**
   * @var bool
   */
  protected $convert_to_object = false;

  /**
   * @var string|null
   */
  protected $convert_to_format = null;



  /**
   * @param string|null $conver_to_format
   */
  public function setConvertToFormat($convert_to_format)
  {
    $this->convert_to_format = $convert_to_format;

    return $this;
  }



  /**
   * @return string|null
   */
  public function getConvertToFormat()
  {
    return $this->convert_to_format;
  }



  /**
   * @param boolean $convert_to_object
   */
  public function setConvertToObject($convert_to_object)
  {
    $this->convert_to_object = (bool) $convert_to_object;

    return $this;
  }



  /**
   * @return boolean
   */
  public function getConvertToObject()
  {
    return $this->convert_to_object;
  }



  /**
   * @param array $format
   */
  public function setExpectedFormat(array $format)
  {
    $this->expectedFormat = $format;

    return $this;
  }



  /**
   * @return array
   */
  public function getExpectedFormat()
  {
    return $this->expectedFormat;
  }
}
