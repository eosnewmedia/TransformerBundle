<?php


namespace Enm\TransformerBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateValidator extends ConstraintValidator
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
    $valid = false;
    foreach ($constraint->format as $format)
    {
      $date = \DateTime::createFromFormat($format, $value);
      if ($date instanceof \DateTime)
      {
        $valid = true;
        break;
      }
    }
    if ($valid === false)
    {
      $this->context->addViolation($constraint->message, array('%invalid%' => $value));
    }
  }
}
