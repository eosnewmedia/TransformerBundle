<?php


namespace Enm\TransformerBundle\Event;

use Enm\TransformerBundle\ConfigurationStructure\Configuration;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class ConfigurationEvent
 *
 * @package Enm\TransformerBundle\Event
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
class ConfigurationEvent extends Event
{


  /**
   * @var \Enm\TransformerBundle\ConfigurationStructure\Configuration
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
   * @return \Enm\TransformerBundle\ConfigurationStructure\Configuration
   */
  public function getConfiguration()
  {
    return $this->configuration;
  }
}
