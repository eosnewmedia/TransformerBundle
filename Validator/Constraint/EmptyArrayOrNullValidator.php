<?php


namespace ENM\TransformerBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotEmptyArrayOrNullValidator extends ConstraintValidator
{

  /**
   * Checks if the passed value is valid.
   *
   * @param mixed $value      The value that should be validated
   * @param Date  $constraint The constraint for the validation
   *
   * @api
   */
  public function validate($value, Constraint $constraint)
  {
    if ((is_array($value) && count($value) > 0) || (!is_array($value) && !is_null($value)))
    {
      $this->context->addViolation($constraint->message);
    }
  }
}