<?php


namespace ENM\TransformerBundle\Event;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;

class TransformerEvent extends ConfigurationEvent
{

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\Parameter
   */
  protected $parameter;



  function __construct(Configuration $configuration, Parameter $parameter)
  {
    $this->parameter = $parameter;
    parent::__construct($configuration);
  }



  /**
   * @return Parameter
   */
  public function getParameter()
  {
    return $this->parameter;
  }
}