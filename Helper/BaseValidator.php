<?php


namespace Enm\TransformerBundle\Helper;

use Enm\TransformerBundle\ConfigurationStructure\Configuration;
use Enm\TransformerBundle\ConfigurationStructure\Parameter;
use Enm\TransformerBundle\Event\ExceptionEvent;
use Enm\TransformerBundle\Exceptions\InvalidTransformerParameterException;
use Enm\TransformerBundle\TransformerEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseValidator
{

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $dispatcher;

  /**
   * @var array
   */
  protected $constraints;

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
   * @param Constraint $constraint
   */
  public function addConstraint(Constraint $constraint)
  {
    if (!is_array($this->constraints))
    {
      $this->constraints = array();
    }
    array_push($this->constraints, $constraint);
  }



  /**
   * @return array
   */
  public function getConstraints()
  {
    if (!is_array($this->constraints))
    {
      $this->constraints = array();
    }

    return $this->constraints;
  }



  public function clearConstraints()
  {
    $this->constraints = array();
  }



  /**
   * @param Parameter $parameter
   *
   * @throws \Enm\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  public function validateConstrains(array $constraints, Parameter $parameter)
  {
    try
    {
      $violationList = $this->validator->validate(
        $parameter->getValue(),
        $constraints,
        array(Constraint::PROPERTY_CONSTRAINT, Constraint::DEFAULT_GROUP)
      );
    }
    catch (\Exception $e)
    {
      $this->dispatcher->dispatch(TransformerEvents::ON_EXCEPTION, new ExceptionEvent($e));
      $violationList = $this->handleException($e, $parameter);
    }

    if ($violationList->count() > 0)
    {
      throw new InvalidTransformerParameterException(
        'Key: ' . $parameter->getKey() . ' - ' . 'Value: '
        . $violationList->get(0)->getInvalidValue() . ' - Error: '
        . $violationList->get(0)->getMessage()
      );
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
    $violation = new ConstraintViolation(
      $e->getMessage(), $e->getMessage(), array(
        $e->getCode(),
        $e->getFile(),
        $e->getLine()
      ), $parameter->getValue(), null, $parameter->getValue()
    );

    return $violationList = new ConstraintViolationList(array($violation));
  }



  public function requireIfAvailableAnd(Configuration $configuration, array $params)
  {
    $requiredIfAvailable = $configuration->getOptions()->getRequiredIfAvailable();
    if (is_array($requiredIfAvailable->getAnd()) && count($requiredIfAvailable->getAnd()) > 0)
    {
      foreach ($requiredIfAvailable->getAnd() as $key)
      {
        if (!array_key_exists(strtolower($key), $params))
        {
          return false;
          break;
        }
      }

      return true;
    }

    return false;
  }



  public function requireIfAvailableOr(Configuration $configuration, array $params)
  {
    $requiredIfAvailable = $configuration->getOptions()->getRequiredIfAvailable();
    foreach ($requiredIfAvailable->getOr() as $key)
    {
      if (array_key_exists(strtolower($key), $params))
      {
        return true;
        break;
      }
    }

    return false;
  }



  public function requireIfNotAvailableAnd(Configuration $configuration, array $params)
  {
    $requiredIfNotAvailable = $configuration->getOptions()->getRequiredIfNotAvailable();

    if (is_array($requiredIfNotAvailable->getAnd()) && count($requiredIfNotAvailable->getAnd()) > 0)
    {
      foreach ($requiredIfNotAvailable->getAnd() as $key)
      {
        if (array_key_exists(strtolower($key), $params))
        {
          return false;
          break;
        }
      }

      return true;
    }

    return false;
  }



  public function requireIfNotAvailableOr(Configuration $configuration, array $params)
  {
    $requiredIfNotAvailable = $configuration->getOptions()->getRequiredIfNotAvailable();
    foreach ($requiredIfNotAvailable->getOr() as $key)
    {
      if (!array_key_exists(strtolower($key), $params))
      {
        return true;
        break;
      }
    }

    return false;
  }
}