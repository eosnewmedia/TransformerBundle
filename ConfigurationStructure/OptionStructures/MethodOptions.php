<?php


namespace ENM\TransformerBundle\ConfigurationStructure\OptionStructures;

class MethodOptions
{

  /**
   * @var string|null
   */
  protected $method = null;

  /**
   * @var string|null
   */
  protected $method_class = null;

  /**
   * @var array
   */
  protected $parameter = array();



  /**
   * @param null|string $method
   */
  public function setMethod($method)
  {
    $this->method = $method;

    return $this;
  }



  /**
   * @return null|string
   */
  public function getMethod()
  {
    return $this->method;
  }



  /**
   * @param null|string $method_class
   */
  public function setMethodClass($method_class)
  {
    $this->method_class = $method_class;

    return $this;
  }



  /**
   * @return null|string
   */
  public function getMethodClass()
  {
    return $this->method_class;
  }



  /**
   * @param array $parameter
   */
  public function setParameter(array $parameter)
  {
    $this->parameter = $parameter;

    return $this;
  }



  /**
   * @return array
   */
  public function getParameter()
  {
    return $this->parameter;
  }
}