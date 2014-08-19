<?php


namespace ENM\TransformerBundle\Event;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use Symfony\Component\EventDispatcher\Event;

class TransformerEvent extends Event
{

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\Configuration
   */
  protected $configuration;

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\Parameter
   */
  protected $parameter;



  function __construct(Configuration $configuration, Parameter $parameter)
  {
    $this->configuration = $configuration;
    $this->parameter     = $parameter;
  }



  /**
   * @return Configuration
   */
  public function getConfiguration()
  {
    return $this->configuration;
  }



  /**
   * @return Parameter
   */
  public function getParameter()
  {
    return $this->parameter;
  }
}