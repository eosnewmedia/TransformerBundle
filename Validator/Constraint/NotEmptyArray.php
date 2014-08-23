<?php


namespace ENM\TransformerBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

/**
 * Class Date
 *
 * @package ENM\TransformerBundle\Validator\Constraint
 * @author  Philipp Marien <marien@eosnewmedia.de>
 */
class NotEmptyArray extends Constraint
{

  public $message = 'The array can not be empty!"';



  /**
   * @return array
   */
  public function getRequiredOptions()
  {
    return array();
  }
}