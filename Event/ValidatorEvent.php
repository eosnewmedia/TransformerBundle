<?php


namespace ENM\TransformerBundle\Event;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use ENM\TransformerBundle\Helper\Validator;
use ENM\TransformerBundle\Manager\ValidationManager;
use Symfony\Component\EventDispatcher\Event;

class ValidatorEvent extends TransformerEvent
{


  /**
   * @var \ENM\TransformerBundle\Helper\Validator
   */
  protected $validator;



  function __construct(Configuration $configuration, Parameter $parameter, Validator $validator)
  {
    $this->validator = $validator;
    parent::__construct($configuration, $parameter);
  }



  /**
   * @return Validator
   */
  public function getValidator()
  {
    return $this->validator;
  }
}