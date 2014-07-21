<?php


namespace Ssc\ApiBundle\Tests\NonComplex;

use ENM\TransformerBundle\Tests\BaseTest;

class OptionsTest extends BaseTest
{

  public function testRequired()
  {
    $config = array(
      'test' => [
        'type'    => 'string',
        'options' => [
          'required' => true
        ]
      ]
    );

    $this->exceptionWithNullTest($config, 'test');
  }



  public function testMin()
  {
    $config = array(
      'test' => [
        'type'    => 'integer',
        'options' => [
          'min' => 0
        ]
      ]
    );
    $this->expectSuccess($config, 'test', 5);
    $this->exceptionWithNegativeIntegerTest($config, 'test');
  }



  public function testMax()
  {
    $config = array(
      'test' => [
        'type'    => 'integer',
        'options' => [
          'max' => 0
        ]
      ]
    );

    $this->expectSuccess($config, 'test', -5);
    $this->exceptionWithPositiveIntegerTest($config, 'test');
  }



  public function testLength()
  {
    $config = array(
      'test' => [
        'type'    => 'string',
        'options' => [
          'length' => [
            'min' => 2,
            'max' => 20
          ]
        ]
      ]
    );

    $this->expectSuccess($config, 'test', 'Hallo Welt');
    $this->expectFailure($config, 'test', 'avcbegasjguegadsdfbwndisfdshafdsffdsfsdfsfnehajsdsfffdff');
  }



  public function testRegex()
  {
    $config = array(
      'test' => [
        'type'    => 'string',
        'options' => [
          'regex' => '/^[0-9]+$/'
        ]
      ]
    );

    $this->expectSuccess($config, 'test', '123');
    $this->expectFailure($config, 'test', 'avcbegasjguegadsdfbwndisfdshafdsffdsfsdfsfnehajsdsfffdff');
  }
} 