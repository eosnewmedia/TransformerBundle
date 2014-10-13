<?php


namespace Enm\TransformerBundle\Tests;

use Enm\TransformerBundle\Manager\TransformerManager;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Validator\Validation;

class BaseTest extends \PHPUnit_Framework_TestCase
{

  protected $instance = null;



  /**
   * @return TransformerManager
   */
  protected function getTransformer()
  {
    if (!$this->instance instanceof TransformerManager)
    {
      $this->instance = new TransformerManager(new EventDispatcher(), Validation::createValidator(), array());
    }

    return $this->instance;
  }



  /**
   * @param array   $config
   * @param  string $key
   */
  protected function exceptionWithObjectTest(array $config, $key)
  {
    try
    {
      $transformer = $this->getTransformer();
      $input       = new \stdClass('Invalid param');
      $transformer->transform(new \stdClass(), $config, array($key => $input));
      $this->fail(sprintf('No exception thrown with invalid value (object) for %s.', $key));
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param array   $config
   * @param  string $key
   */
  protected function exceptionWithArrayTest(array $config, $key)
  {
    try
    {
      $transformer = $this->getTransformer();
      $input       = array('Invalid param');
      $transformer->transform(new \stdClass(), $config, array($key => $input));
      $this->fail(sprintf('No exception thrown with invalid value (array) for %s.', $key));
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param array   $config
   * @param  string $key
   */
  protected function exceptionWithBooleanTest(array $config, $key)
  {
    try
    {
      $transformer = $this->getTransformer();
      $input       = true;
      $transformer->transform(new \stdClass(), $config, array($key => $input));
      $this->fail(sprintf('No exception thrown with invalid value (boolean) for %s.', $key));
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param array   $config
   * @param  string $key
   */
  protected function exceptionWithStringTest(array $config, $key)
  {
    try
    {
      $transformer = $this->getTransformer();
      $input       = 'Invalid param';
      $transformer->transform(new \stdClass(), $config, array($key => $input));
      $this->fail(sprintf('No exception thrown with invalid value (string) for %s.', $key));
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param array   $config
   * @param  string $key
   */
  protected function exceptionWithNegativeIntegerTest(array $config, $key)
  {
    try
    {
      $transformer = $this->getTransformer();
      $input       = -2;
      $transformer->transform(new \stdClass(), $config, array($key => $input));
      $this->fail(sprintf('No exception thrown with invalid value (negative integer) for %s.', $key));
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param array   $config
   * @param  string $key
   */
  protected function exceptionWithPositiveIntegerTest(array $config, $key)
  {
    try
    {
      $transformer = $this->getTransformer();
      $input       = 5;
      $transformer->transform(new \stdClass(), $config, array($key => $input));
      $this->fail(sprintf('No exception thrown with invalid value (positive integer) for %s.', $key));
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param array   $config
   * @param  string $key
   */
  protected function exceptionWithNullTest(array $config, $key)
  {
    try
    {
      $transformer = $this->getTransformer();
      $input       = null;
      $transformer->transform(new \stdClass(), $config, array($key => $input));
      $this->fail(sprintf('No exception thrown with invalid value (null) for %s.', $key));
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param array   $config
   * @param  string $key
   */
  protected function exceptionWithZeroTest(array $config, $key)
  {
    try
    {
      $transformer = $this->getTransformer();
      $input       = 0;
      $transformer->transform(new \stdClass(), $config, array($key => $input));
      $this->fail(sprintf('No exception thrown with invalid value (zero) for %s.', $key));
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param array   $config
   * @param  string $key
   */
  protected function exceptionWithPositiveFloatTest(array $config, $key)
  {
    try
    {
      $transformer = $this->getTransformer();
      $input       = 3.5;
      $transformer->transform(new \stdClass(), $config, array($key => $input));
      $this->fail(sprintf('No exception thrown with invalid value (zero) for %s.', $key));
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param array   $config
   * @param  string $key
   */
  protected function exceptionWithNegativeFloatTest(array $config, $key)
  {
    try
    {
      $transformer = $this->getTransformer();
      $input       = -3.5;
      $transformer->transform(new \stdClass(), $config, array($key => $input));
      $this->fail(sprintf('No exception thrown with invalid value (zero) for %s.', $key));
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param array   $config
   * @param string  $key
   * @param   mixed $expectedValue
   */
  protected function expectSuccess(array $config, $key, $expectedValue)
  {
    try
    {
      $transformer = $this->getTransformer();
      $params      = array($key => $expectedValue);
      $rightClass  = $transformer->transform(new \stdClass(), $config, $params);

      $this->assertEquals($expectedValue, $rightClass->{$key});
    }
    catch (\Exception $e)
    {
      $this->fail('Expected value has not been returned.');
    }
  }



  /**
   * @param array   $config
   * @param string  $key
   * @param   mixed $unexpectedValue
   */
  protected function expectFailure(array $config, $key, $unexpectedValue)
  {
    try
    {
      $transformer = $this->getTransformer();
      $params      = array($key => $unexpectedValue);
      $transformer->transform(new \stdClass(), $config, $params);

      $this->fail('Invalid value does not force an exception.');
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }
} 