<?php


namespace Ssc\ApiBundle\Tests\NonComplex;

use ENM\TransformerBundle\Tests\BaseTest;

class DateTest extends BaseTest
{

  public function testDate()
  {
    $config = array(
      'date' => [
        'complex' => false,
        'type'    => 'string',
        'options' => [
          'date' => 'date'
        ]
      ]
    );

    $this->expectSuccess($config, 'date', '2014-07-21');
    $this->expectFailure($config, 'date', '2014-07-45');

    $this->exceptionWithZeroTest($config, 'date');
    $this->exceptionWithPositiveIntegerTest($config, 'date');
    $this->exceptionWithObjectTest($config, 'date');
    $this->exceptionWithNegativeIntegerTest($config, 'date');
    $this->exceptionWithArrayTest($config, 'date');
    $this->exceptionWithBooleanTest($config, 'date');
    $this->exceptionWithStringTest($config, 'date');
  }



  public function testDateTime()
  {
    $config = array(
      'date' => [
        'complex' => false,
        'type'    => 'string',
        'options' => [
          'date' => 'datetime'
        ]
      ]
    );
    $this->expectSuccess($config, 'date', '2014-07-21 12:30:00');
    $this->expectFailure($config, 'date', '2014-07-45 12:30:00');

    $this->exceptionWithZeroTest($config, 'date');
    $this->exceptionWithPositiveIntegerTest($config, 'date');
    $this->exceptionWithObjectTest($config, 'date');
    $this->exceptionWithNegativeIntegerTest($config, 'date');
    $this->exceptionWithArrayTest($config, 'date');
    $this->exceptionWithBooleanTest($config, 'date');
    $this->exceptionWithStringTest($config, 'date');
    $this->exceptionWithNegativeFloatTest($config, 'test');
    $this->exceptionWithPositiveFloatTest($config, 'test');
  }



  public function testTime()
  {
    $config = array(
      'date' => [
        'complex' => false,
        'type'    => 'string',
        'options' => [
          'date' => 'time'
        ]
      ]
    );
    $this->expectSuccess($config, 'date', '12:45:34');
    $this->expectFailure($config, 'date', '25:12:00');

    $this->exceptionWithZeroTest($config, 'date');
    $this->exceptionWithPositiveIntegerTest($config, 'date');
    $this->exceptionWithObjectTest($config, 'date');
    $this->exceptionWithNegativeIntegerTest($config, 'date');
    $this->exceptionWithArrayTest($config, 'date');
    $this->exceptionWithBooleanTest($config, 'date');
    $this->exceptionWithStringTest($config, 'date');
    $this->exceptionWithNegativeFloatTest($config, 'test');
    $this->exceptionWithPositiveFloatTest($config, 'test');
  }
}