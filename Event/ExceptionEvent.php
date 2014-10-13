<?php


namespace Enm\TransformerBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Class ExceptionEvent
 *
 * @package Enm\TransformerBundle\Event
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
class ExceptionEvent extends Event
{

  /**
   * @var \Exception
   */
  protected $exception;



  public function __construct(\Exception $exception)
  {
    $this->exception = $exception;
  }



  /**
   * @return \Exception
   */
  public function getException()
  {
    return $this->exception;
  }
}
