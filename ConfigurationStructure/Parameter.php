<?php


namespace Enm\TransformerBundle\ConfigurationStructure;

class Parameter
{

  /**
   * @var string
   */
  protected $key;

  /**
   * @var mixed
   */
  protected $value;



  public function __construct($key = null, $value = null)
  {
    $this->key   = $key;
    $this->value = $value;
  }



  /**
   * @param string $key
   */
  public function setKey($key)
  {
    $this->key = $key;

    return $this;
  }



  /**
   * @return string
   */
  public function getKey()
  {
    return $this->key;
  }



  /**
   * @param mixed $value
   */
  public function setValue($value)
  {
    $this->value = $value;

    return $this;
  }



  /**
   * @return mixed
   */
  public function getValue()
  {
    return $this->value;
  }
}