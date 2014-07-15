<?php

namespace ENM\TransformerBundle\Tests;

abstract class BaseTest extends KernelAwareTest
{

  /**
   * @param $object
   * @param $method
   * @param $key
   */
  protected function exceptionWithObjectTest($object, $method, $key)
  {
    try
    {
      if (method_exists($object, $method))
      {
        $input  = new \stdClass('Invalid param');
        $actual = $object->{$method}(array($key => $input));
        $this->fail(sprintf('No exception thrown with invalid value (object) for %s.', $key));
      }
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param $object
   * @param $method
   * @param $key
   */
  protected function exceptionWithArrayTest($object, $method, $key)
  {
    try
    {
      if (method_exists($object, $method))
      {
        $input  = array('Invalid param');
        $actual = $object->{$method}(array($key => $input));
        $this->fail(sprintf('No exception thrown with invalid value (array) for %s.', $key));
      }
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param $object
   * @param $method
   * @param $key
   */
  protected function exceptionWithBooleanTest($object, $method, $key)
  {
    try
    {
      if (method_exists($object, $method))
      {
        $input  = true;
        $actual = $object->{$method}(array($key => $input));
        $this->fail(sprintf('No exception thrown with invalid value (boolean) for %s.', $key));
      }
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param $object
   * @param $method
   * @param $key
   */
  protected function exceptionWithStringTest($object, $method, $key)
  {
    try
    {
      if (method_exists($object, $method))
      {
        $input  = 'Invalid param';
        $actual = $object->{$method}(array($key => $input));
        $this->fail(sprintf('No exception thrown with invalid value (string) for %s.', $key));
      }
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param $object
   * @param $method
   * @param $key
   */
  protected function exceptionWithNegativeIntegerTest($object, $method, $key)
  {
    try
    {
      if (method_exists($object, $method))
      {
        $input  = -10;
        $actual = $object->{$method}(array($key => $input));
        $this->fail(sprintf('No exception thrown with invalid value (%d) for %s.', $key));
      }
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param $object
   * @param $method
   * @param $key
   */
  protected function exceptionWithPositiveIntegerTest($object, $method, $key)
  {
    try
    {
      if (method_exists($object, $method))
      {
        $input  = 10;
        $actual = $object->{$method}(array($key => $input));
        $this->fail(sprintf('No exception thrown with invalid value (%d) for %s.', $key));
      }
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param $object
   * @param $method
   * @param $key
   */
  protected function exceptionWithNullTest($object, $method, $key)
  {
    try
    {
      if (method_exists($object, $method))
      {
        $input  = null;
        $actual = $object->{$method}(array($key => $input));
        $this->fail(sprintf('No exception thrown with invalid value (null) for %s.', $key));
      }
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param $object
   * @param $method
   * @param $key
   */
  protected function exceptionWithZeroTest($object, $method, $key)
  {
    try
    {
      if (method_exists($object, $method))
      {
        $input  = 0;
        $actual = $object->{$method}(array($key => $input));
        $this->fail(sprintf('No exception thrown with invalid value (%d) for %s.', $key));
      }
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  /**
   * @param       $object
   * @param       $method
   * @param       $key
   * @param array $enum
   */
  protected function enumTest($object, $method, $key, array $enum)
  {
    foreach ($enum as $input)
    {
      $actual = $object->{$method}(array($key => $input));
      $this->assertEquals($input, $actual->{'get' . $key}());
    }

    try
    {
      $input  = 'fluchzeuch!';
      $actual = $object->{$method}(array($key => $input));
      $this->fail(sprintf('No exception thrown with invalid value (fluchzeuch!) for %s.', $key));
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }

    $this->exceptionWithArrayTest($object, $method, $key);
    $this->exceptionWithBooleanTest($object, $method, $key);
    $this->exceptionWithNegativeIntegerTest($object, $method, $key);
    $this->exceptionWithNullTest($object, $method, $key);
    $this->exceptionWithObjectTest($object, $method, $key);
    $this->exceptionWithPositiveIntegerTest($object, $method, $key);
    $this->exceptionWithZeroTest($object, $method, $key);
  }



  protected function positiveIntegerTest($object, $method, $key)
  {
    $inputs = array(0, 100, 1000);

    foreach ($inputs as $input)
    {
      $actual = $object->{$method}(array($key => $input));
      $this->assertEquals($input, $actual->{'get' . $key}());
    }

    $this->exceptionWithArrayTest($object, $method, $key);
    $this->exceptionWithBooleanTest($object, $method, $key);
    $this->exceptionWithNegativeIntegerTest($object, $method, $key);
    $this->exceptionWithNullTest($object, $method, $key);
    $this->exceptionWithObjectTest($object, $method, $key);
    $this->exceptionWithStringTest($object, $method, $key);
  }



  protected function negativeIntegerTest($object, $method, $key)
  {
    $inputs = array(-1, -100, -1000);

    foreach ($inputs as $input)
    {
      $actual = $object->{$method}(array($key => $input));
      $this->assertEquals($input, $actual->{'get' . $key}());
    }

    $this->exceptionWithArrayTest($object, $method, $key);
    $this->exceptionWithBooleanTest($object, $method, $key);
    $this->exceptionWithPositiveIntegerTest($object, $method, $key);
    $this->exceptionWithNullTest($object, $method, $key);
    $this->exceptionWithObjectTest($object, $method, $key);
    $this->exceptionWithStringTest($object, $method, $key);
    $this->exceptionWithZeroTest($object, $method, $key);
  }
}