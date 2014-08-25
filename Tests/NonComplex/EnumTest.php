<?php


namespace ENM\TransformerBundle\Tests\NonComplex;

use ENM\TransformerBundle\Tests\BaseTest;

class EnumTest extends BaseTest
{

  public function testEnumWithString()
  {
    $config = array(
      'test' => [
        'type'    => 'string',
        'options' => [
          'expected' => array('Hallo', 'Welt')
        ]
      ]
    );

    $this->expectSuccess($config, 'test', 'Hallo');
    $this->expectSuccess($config, 'test', 'Welt');

    $this->expectFailure($config, 'test', 'Test');

    $this->exceptionWithArrayTest($config, 'test');
    $this->exceptionWithBooleanTest($config, 'test');
    $this->exceptionWithNegativeIntegerTest($config, 'test');
    $this->exceptionWithObjectTest($config, 'test');
    $this->exceptionWithPositiveIntegerTest($config, 'test');
    $this->exceptionWithZeroTest($config, 'test');
    $this->exceptionWithNegativeFloatTest($config, 'test');
    $this->exceptionWithPositiveFloatTest($config, 'test');
  }



  public function testEnumWithArray()
  {
    $config = array(
      'test' => [
        'type'    => 'array',
        'options' => [
          'expected' => array('Hallo', 'Welt')
        ]
      ]
    );

    $this->expectSuccess($config, 'test', array('Hallo'));
    $this->expectSuccess($config, 'test', array('Welt'));
    $this->expectSuccess($config, 'test', array('Hallo', 'Welt'));

    $this->expectFailure($config, 'test', 'Test');
    $this->expectFailure($config, 'test', array('Test'));

    $this->exceptionWithStringTest($config, 'test');
    $this->exceptionWithBooleanTest($config, 'test');
    $this->exceptionWithNegativeIntegerTest($config, 'test');
    $this->exceptionWithObjectTest($config, 'test');
    $this->exceptionWithPositiveIntegerTest($config, 'test');
    $this->exceptionWithZeroTest($config, 'test');
    $this->exceptionWithNegativeFloatTest($config, 'test');
    $this->exceptionWithPositiveFloatTest($config, 'test');
  }
} 