<?php


namespace Enm\TransformerBundle\Event;

use Enm\TransformerBundle\ConfigurationStructure\Configuration;
use Enm\TransformerBundle\ConfigurationStructure\Parameter;

/**
 * Class TransformerEvent
 *
 * @package Enm\TransformerBundle\Event
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
class TransformerEvent extends ConfigurationEvent
{

  /**
   * @var \Enm\TransformerBundle\ConfigurationStructure\Parameter
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
