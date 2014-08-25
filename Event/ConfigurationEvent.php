<?php


namespace ENM\TransformerBundle\Event;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ConfigurationEvent
 *
 * @package ENM\TransformerBundle\Event
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
class ConfigurationEvent extends Event
{


  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\Configuration
   */
  protected $configuration;



  /**
   * @param Configuration $configuration
   */
  public function __construct(Configuration $configuration)
  {
    $this->configuration = $configuration;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\Configuration
   */
  public function getConfiguration()
  {
    return $this->configuration;
  }
}