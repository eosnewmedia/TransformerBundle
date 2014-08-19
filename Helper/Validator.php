<?php


namespace ENM\TransformerBundle\Helper;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use ENM\TransformerBundle\ConfigurationStructure\StringValidationEnum;
use ENM\TransformerBundle\ConfigurationStructure\TypeEnum;
use ENM\TransformerBundle\Event\ValidatorEvent;
use ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException;
use ENM\TransformerBundle\TransformerEvents;
use ENM\TransformerBundle\Validator\Constraint\Date;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validator
{

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $dispatcher;

  /**
   * @var array
   */
  protected $constrains;

  /**
   * @var \Symfony\Component\Validator\Validator\ValidatorInterface
   */
  protected $validator;



  /**
   * @param EventDispatcherInterface $dispatcher
   * @param ValidatorInterface       $validatorInterface
   */
  public function __construct(EventDispatcherInterface $dispatcher, ValidatorInterface $validatorInterface)
  {
    $this->dispatcher = $dispatcher;
    $this->validator  = $validatorInterface;
  }



  /**
   * @param Configuration $configuration
   * @param Parameter     $parameter
   */
  public function validate(Configuration $configuration, Parameter $parameter)
  {
    $this->constrains = array();
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
    $this->validateConstrains($parameter);
    $this->dispatcher->dispatch(
                     TransformerEvents::AFTER_VALIDATION,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
  }



  public function addConstraint(Constraint $constraint)
  {
    if (!is_array($this->constrains))
    {
      $this->constrains = array();
    }
    array_push($this->constrains, $constraint);
  }



  /**
   * @param Parameter $parameter
   *
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  protected function validateConstrains(Parameter $parameter)
  {
    try
    {
      $violationList = $this->validator->validate(
                                       $parameter->getValue(),
                                         $this->constrains,
                                         Constraint::PROPERTY_CONSTRAINT
      );
    }
    catch (\Exception $e)
    {
      $violationList = $this->handleException($e, $parameter);
    }

    if ($violationList->count() > 0)
    {
      throw new InvalidTransformerParameterException($violationList);
    }
  }



  /**
   * @param \Exception $e
   * @param Parameter  $parameter
   *
   * @return ConstraintViolationList
   */
  protected function handleException(\Exception $e, Parameter $parameter)
  {
    $violation = new ConstraintViolation($e->getMessage(), $e->getMessage(), array(
      $e->getCode(),
      $e->getFile(),
      $e->getLine()
    ), $parameter->getValue(), null, $parameter->getValue());

    return $violationList = new ConstraintViolationList(array($violation));
  }



  /**
   * @param Configuration $configuration
   */
  protected function validateType(Configuration $configuration)
  {
    array_push($this->constrains, new Constraints\Type(array('type' => $configuration->getType())));
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

      array_push(
        $this->constrains,
        new Constraints\Choice($config)
      );
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
      array_push(
        $this->constrains,
        new Constraints\GreaterThanOrEqual(array('value' => $min))
      );
    }
    if ($max !== null)
    {
      array_push(
        $this->constrains,
        new Constraints\LessThanOrEqual(array('value' => $max))
      );
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
    array_push(
      $this->constrains,
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
    // Special Validation
    switch ($configuration->getOptions()->getStringOptions()->getValidation())
    {
      case StringValidationEnum::EMAIL:
        array_push(
          $this->constrains,
          new Constraints\Email(array('checkMX' => true, 'checkHost' => true))
        );
        break;
      case StringValidationEnum::URL:
        array_push(
          $this->constrains,
          new Constraints\Url(array('protocols' => array('http', 'https', 'ftp')))
        );
        break;
      case StringValidationEnum::IP:
        array_push(
          $this->constrains,
          new Constraints\Ip(array('version' => 'all'))
        );
        break;
    }
    // String-Length
    if ($configuration->getOptions()->getStringOptions()->getMin() !== null
        && $configuration->getOptions()->getStringOptions()->getMax() !== null
    )
    {
      array_push(
        $this->constrains,
        new Constraints\Length(array(
          'min' => $configuration->getOptions()->getStringOptions()->getMin(),
          'max' => $configuration->getOptions()->getStringOptions()->getMax(),
        ))
      );
    }
    // RegEx
    if ($configuration->getOptions()->getStringOptions()->getRegex() !== null)
    {
      array_push(
        $this->constrains,
        new Constraints\Regex(array(
          'pattern' => $configuration->getOptions()->getStringOptions()->getRegex()
        ))
      );
    }
    $this->dispatcher->dispatch(
                     TransformerEvents::VALIDATE_STRING,
                       new ValidatorEvent($configuration, $parameter, $this)
    );
  }
} 