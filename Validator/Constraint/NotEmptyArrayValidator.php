<?php


namespace Enm\TransformerBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotEmptyArrayValidator extends ConstraintValidator
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
    if (is_array($value) && count($value) === 0)
    {
      $this->context->addViolation($constraint->message);
    }
  }
}