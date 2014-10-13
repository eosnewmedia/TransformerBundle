<?php


namespace Enm\TransformerBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * Class Date
 *
 * @package Enm\TransformerBundle\Validator\Constraint
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
class Date extends Constraint
{

  public $message = 'The value "%invalid%" is not a valid date string!"';

  public $format;



  public function __construct($options = null)
  {
    if (is_array($options) && !isset($options['format']))
    {
      throw new ConstraintDefinitionException(
        sprintf(
          'The %s constraint requires the "format" option to be set.',
          get_class($this)
        )
      );
    }
    if (!is_array($options['format']))
    {
      throw new ConstraintDefinitionException(
        'The Date constraint requires the "format" option to be an array.'
      );
    }
    parent::__construct($options);
  }



  /**
   * @return array
   */
  public function getRequiredOptions()
  {
    return array('format');
  }
}
