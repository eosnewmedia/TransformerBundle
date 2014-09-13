<?php


namespace ENM\TransformerBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\RegexValidator;

class ArrayRegexValidator extends RegexValidator
{

  public function validate($value, Constraint $constraint)
  {
    if (is_array($value))
    {
      foreach ($value as $validate)
      {
        parent::validate($validate, $constraint);
      }
    }
    else
    {
      parent::validate($value, $constraint);
    }
  }
}