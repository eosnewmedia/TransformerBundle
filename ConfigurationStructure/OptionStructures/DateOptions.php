<?php


namespace ENM\TransformerBundle\ConfigurationStructure\OptionStructures;

class DateOptions
{

  /**
   * @var array
   */
  protected $format = array('Y-m-d', 'd.m.Y');

  /**
   * @var bool
   */
  protected $convert_to_object = false;

  /**
   * @var string|null
   */
  protected $conver_to_format = null;



  /**
   * @param string|null $conver_to_format
   */
  public function setConverToFormat($conver_to_format)
  {
    $this->conver_to_format = $conver_to_format;

    return $this;
  }



  /**
   * @return string|null
   */
  public function getConverToFormat()
  {
    return $this->conver_to_format;
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
  public function setFormat(array $format)
  {
    $this->format = $format;

    return $this;
  }



  /**
   * @return array
   */
  public function getFormat()
  {
    return $this->format;
  }
}