<?php


namespace Enm\TransformerBundle\Event;

use Enm\TransformerBundle\ConfigurationStructure\Configuration;
use Enm\TransformerBundle\ConfigurationStructure\Parameter;
use Enm\TransformerBundle\Helper\Validator;
use Enm\TransformerBundle\Manager\ValidationManager;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ValidatorEvent
 *
 * @package Enm\TransformerBundle\Event
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
class ValidatorEvent extends TransformerEvent
{


  /**
   * @var \Enm\TransformerBundle\Helper\Validator
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