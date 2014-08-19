<?php


namespace ENM\TransformerBundle\Event;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use ENM\TransformerBundle\Manager\ValidationManager;
use Symfony\Component\EventDispatcher\Event;

class ValidatorEvent extends Event
{

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\Configuration
   */
  protected $configuration;

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\Parameter
   */
  protected $parameter;

  /**
   * @var \ENM\TransformerBundle\Manager\ValidationManager
   */
  protected $validator;



  function __construct(Configuration $configuration, Parameter $parameter, ValidationManager $validator)
  {
    $this->configuration = $configuration;
    $this->parameter     = $parameter;
    $this->validator     = $validator;
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



  /**
   * @return \ENM\TransformerBundle\Manager\ValidationManager
   */
  public function getValidator()
  {
    return $this->validator;
  }
}