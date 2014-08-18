<?php


namespace ENM\TransformerBundle\ConfigurationStructure\OptionStructures;

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
}