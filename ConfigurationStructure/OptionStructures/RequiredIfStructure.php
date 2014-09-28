<?php


namespace Enm\TransformerBundle\ConfigurationStructure\OptionStructures;

class RequiredIfStructure
{

  /**
   * @var array
   */
  protected $or = array();

  /**
   * @var array
   */
  protected $and = array();



  function __construct(array $or = array(), array $and = array())
  {
    $this->or  = $or;
    $this->and = $and;
  }



  /**
   * @param array $and
   */
  public function setAnd($and)
  {
    $this->and = $and;

    return $this;
  }



  /**
   * @return array
   */
  public function getAnd()
  {
    return $this->and;
  }



  /**
   * @param array $or
   */
  public function setOr($or)
  {
    $this->or = $or;

    return $this;
  }



  /**
   * @return array
   */
  public function getOr()
  {
    return $this->or;
  }
}