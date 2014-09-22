<?php


namespace ENM\TransformerBundle\Helper;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use ENM\TransformerBundle\ConfigurationStructure\StringValidationEnum;
use ENM\TransformerBundle\ConfigurationStructure\TypeEnum;
use ENM\TransformerBundle\Event\ValidatorEvent;
use ENM\TransformerBundle\TransformerEvents;
use ENM\TransformerBundle\Validator\Constraint\ArrayRegex;
use ENM\TransformerBundle\Validator\Constraint\Date;
use ENM\TransformerBundle\Validator\Constraint\EmptyArrayOrNull;
use ENM\TransformerBundle\Validator\Constraint\NotEmptyArray;
use Symfony\Component\Validator\Constraints;

class Validator extends BaseValidator
{


  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  public function validate(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::BEFORE_VALIDATION,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
    switch ($configuration->getType())
    {
      case TypeEnum::ARRAY_TYPE:
        $this->validateArray($configuration, $parameter);
        break;
      case TypeEnum::BOOL_TYPE:
        $this->validateBool($configuration, $parameter);
        break;
      case TypeEnum::COLLECTION_TYPE:
        $this->validateCollection($configuration, $parameter);
        break;
      case TypeEnum::DATE_TYPE:
        $this->validateDate($configuration, $parameter);
        break;
      case TypeEnum::FLOAT_TYPE:
        $this->validateFloat($configuration, $parameter);
        break;
      case TypeEnum::INTEGER_TYPE:
        $this->validateInteger($configuration, $parameter);
        break;
      case TypeEnum::OBJECT_TYPE:
        $this->validateObject($configuration, $parameter);
        break;
      case TypeEnum::STRING_TYPE:
        $this->validateString($configuration, $parameter);
        break;
      case TypeEnum::INDIVIDUAL_TYPE:
        $this->validateIndividual($configuration, $parameter);
        break;
    }
    $this->validateConstrains($this->getConstraints(), $parameter);
    $this->clearConstraints();
    $this->dispatcher->dispatch(
                     TransformerEvents::AFTER_VALIDATION,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   * @param array         $params
   */
  public function requireValue(Configuration $configuration, Parameter $parameter, array $params)
  {
    $not_null    = false;
    $constraints = array();

    if ($configuration->getOptions()->getRequired() === true)
    {
      $not_null = true;
    }

    $methods = array(
      'requireIfAvailableAnd',
      'requireIfAvailableOR',
      'requireIfNotAvailableAnd',
      'requireIfNotAvailableOr'
    );

    $i = 1;

    while ($not_null = false && $i < count($methods))
    {
      $not_null = $this->{$methods[$i - 1]}($configuration, $params);
      ++$i;
    }

    if ($not_null === true)
    {
      array_push($constraints, new Constraints\NotNull());
      if (in_array($configuration->getType(), array(TypeEnum::COLLECTION_TYPE, TypeEnum::OBJECT_TYPE)))
      {
        array_push($constraints, new NotEmptyArray());
      }
      $this->validateConstrains($constraints, $parameter);
    }
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   * @param array         $params
   */
  public function forbidValue(Configuration $configuration, Parameter $parameter, array $params)
  {
    $constraints = array();

    $forbidden = false;

    $ifAvailable    = $configuration->getOptions()->getForbiddenIfAvailable();
    $ifNotAvailable = $configuration->getOptions()->getForbiddenIfNotAvailable();

    if ($forbidden !== true)
    {
      foreach ($ifAvailable as $key)
      {
        if (array_key_exists(strtolower($key), $params))
        {
          $forbidden = true;
          break;
        }
      }
    }
    if ($forbidden !== true)
    {
      foreach ($ifNotAvailable as $key)
      {
        if (!array_key_exists(strtolower($key), $params))
        {
          $forbidden = true;
          break;
        }
      }
    }
    if ($forbidden === true)
    {
      if (in_array($configuration->getType(), array(TypeEnum::COLLECTION_TYPE, TypeEnum::OBJECT_TYPE)))
      {
        array_push($constraints, new EmptyArrayOrNull());
      }
      else
      {
        array_push($constraints, new Constraints\Null());
      }
      $this->validateConstrains($constraints, $parameter);
    }
  }



  /**
   * @param Configuration $configuration
   */
  protected function validateType(Configuration $configuration)
  {
    $this->addConstraint(new Constraints\Type(array('type' => $configuration->getType())));
  }



  /**
   * @param array $expected
   * @param bool  $multiple
   */
  protected function validateExpected(array $expected, $multiple = false)
  {
    if (count($expected))
    {
      $config = array(
        'choices'  => $expected,
        'multiple' => $multiple
      );

      $this->addConstraint(new Constraints\Choice($config));
    }
  }



  /**
   * @param $min
   * @param $max
   */
  protected function validateMinMax($min, $max)
  {
    if ($min !== null)
    {
      $this->addConstraint(new Constraints\GreaterThanOrEqual(array('value' => $min)));
    }
    if ($max !== null)
    {
      $this->addConstraint(new Constraints\LessThanOrEqual(array('value' => $max)));
    }
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function validateArray(Configuration $configuration, Parameter $parameter)
  {
    $this->validateType($configuration);
    $this->validateExpected($configuration->getOptions()->getArrayOptions()->getExpected(), true);

    // RegEx
    if ($configuration->getOptions()->getArrayOptions()->getRegex() !== null)
    {
      $this->addConstraint(
           new ArrayRegex(
             array(
               'pattern' => $configuration->getOptions()->getArrayOptions()->getRegex()
             )
           )
      );
    }

    $this->dispatcher->dispatch(
                     TransformerEvents::VALIDATE_ARRAY,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function validateBool(Configuration $configuration, Parameter $parameter)
  {
    $this->validateType($configuration);
    $this->validateExpected($configuration->getOptions()->getBoolOptions()->getExpected());

    $this->dispatcher->dispatch(
                     TransformerEvents::VALIDATE_BOOL,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function validateCollection(Configuration $configuration, Parameter $parameter)
  {
    $this->addConstraint(
         new Constraints\Collection(array('fields' => array(), 'allowExtraFields' => true))
    );
    $this->dispatcher->dispatch(
                     TransformerEvents::VALIDATE_COLLECTION,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function validateDate(Configuration $configuration, Parameter $parameter)
  {
    $this->addConstraint(
         new Date(array('format' => $configuration->getOptions()->getDateOptions()->getFormat()))
    );

    $this->dispatcher->dispatch(
                     TransformerEvents::VALIDATE_DATE,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function validateFloat(Configuration $configuration, Parameter $parameter)
  {
    $this->validateType($configuration);

    if ($configuration->getOptions()->getFloatOptions()->getRound() !== null)
    {
      $parameter->setValue(
                round(
                  $parameter->getValue(),
                  $configuration->getOptions()->getFloatOptions()->getRound(),
                  PHP_ROUND_HALF_DOWN
                )
      );
    }

    $this->validateMinMax(
         $configuration->getOptions()->getFloatOptions()->getMin(),
           $configuration->getOptions()->getFloatOptions()->getMax()
    );
    $this->validateExpected($configuration->getOptions()->getFloatOptions()->getExpected());

    $this->dispatcher->dispatch(
                     TransformerEvents::VALIDATE_FLOAT,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function validateInteger(Configuration $configuration, Parameter $parameter)
  {
    $this->validateType($configuration);

    $this->validateMinMax(
         $configuration->getOptions()->getIntegerOptions()->getMin(),
           $configuration->getOptions()->getIntegerOptions()->getMax()
    );
    $this->validateExpected($configuration->getOptions()->getIntegerOptions()->getExpected());

    $this->dispatcher->dispatch(
                     TransformerEvents::VALIDATE_INTEGER,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function validateIndividual(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::VALIDATE_INDIVIDUAL,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function validateObject(Configuration $configuration, Parameter $parameter)
  {
    $this->dispatcher->dispatch(
                     TransformerEvents::VALIDATE_OBJECT,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function validateString(Configuration $configuration, Parameter $parameter)
  {
    $this->validateType($configuration);
    $this->validateExpected($configuration->getOptions()->getStringOptions()->getExpected());
    // String-Length
    if ($configuration->getOptions()->getStringOptions()->getMin() !== null
        && $configuration->getOptions()->getStringOptions()->getMax() !== null
    )
    {
      $this->addConstraint(
           new Constraints\Length(
             array(
               'min' => $configuration->getOptions()->getStringOptions()->getMin(),
               'max' => $configuration->getOptions()->getStringOptions()->getMax(),
             )
           )
      );
    }

    $this->specialValidation($configuration);

    $this->dispatcher->dispatch(
                     TransformerEvents::VALIDATE_STRING,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  protected function specialValidation(Configuration $configuration)
  {
    // RegEx
    if ($configuration->getOptions()->getStringOptions()->getRegex() !== null)
    {
      $this->addConstraint(
           new Constraints\Regex(
             array(
               'pattern' => $configuration->getOptions()->getStringOptions()->getRegex()
             )
           )
      );
    }
    // Special Validation
    switch ($configuration->getOptions()->getStringOptions()->getValidation())
    {
      case StringValidationEnum::EMAIL:
        $this->addConstraint(
             new Constraints\Email(array(
               'checkMX'   => true,
               'checkHost' => $configuration->getOptions()->getStringOptions()
                                            ->getStrongValidation()
             ))
        );
        break;
      case StringValidationEnum::URL:
        $this->addConstraint(new Constraints\Url(array('protocols' => array('http', 'https', 'ftp'))));
        break;
      case StringValidationEnum::IP:
        $this->addConstraint(new Constraints\Ip(array('version' => 'all')));
        break;
    }
  }
} 