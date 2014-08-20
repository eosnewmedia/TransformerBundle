<?php


namespace ENM\TransformerBundle\Event;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;

/**
 * Class TransformerEvent
 *
 * @package ENM\TransformerBundle\Event
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
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