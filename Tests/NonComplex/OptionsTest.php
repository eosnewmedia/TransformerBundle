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



  public function testRequiredIf()
  {
    $config = array(
      'testA' => [
        'type'    => 'string',
        'options' => [
          'required'               => false,
          'requiredIfNotAvailable' => array(
            'and' => array('testB')
          )
        ]
      ],
      'testB' => [
        'type'    => 'string',
        'options' => [
          'required'               => false,
          'requiredIfNotAvailable' => array(
            'and' => array('testB')
          )
        ]
      ]
    );

    $this->exceptionWithNullTest($config, 'testA');
    $this->exceptionWithNullTest($config, 'testB');
  }



  public function testForbiddenIf()
  {
    $config = array(
      'testA' => [
        'type'    => 'string',
        'options' => [
          'forbiddenIfAvailable' => array('testB')
        ]
      ],
      'testB' => [
        'type'    => 'string',
        'options' => [
          'forbiddenIfAvailable' => array('testA')
        ]
      ],
      'testC' => [
        'type'    => 'string',
        'options' => [
          'forbiddenIfNotAvailable' => array('testA')
        ]
      ],
      'testD' => [
        'type'    => 'string',
        'options' => [
          'forbiddenIfNotAvailable' => array('testB')
        ]
      ]
    );

    try
    {
      $array = array(
        'testB' => 'abc',
        'testD' => 'abc',
      );
      $this->container->get('enm.transformer.service')->transform(new \stdClass(), $config, $array);
      $this->assertTrue(true);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getMessage());
    }
    try
    {
      $array = array(
        'testA' => 'abc',
        'testC' => 'abc',
      );
      $this->container->get('enm.transformer.service')->transform(new \stdClass(), $config, $array);
      $this->assertTrue(true);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getMessage());
    }

    try
    {
      $array = array(
        'testA' => 'abc',
        'testD' => 'abc',
      );
      $this->container->get('enm.transformer.service')->transform(new \stdClass(), $config, $array);
      $this->fail('No Exception thrown with invalid parameters!');
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }

    try
    {
      $array = array(
        'testB' => 'abc',
        'testC' => 'abc',
      );
      $this->container->get('enm.transformer.service')->transform(new \stdClass(), $config, $array);
      $this->fail('No Exception thrown with invalid parameters!');
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }

    try
    {
      $array = array(
        'testA' => 'abc',
        'testB' => 'abc',
      );
      $this->container->get('enm.transformer.service')->transform(new \stdClass(), $config, $array);
      $this->fail('No Exception thrown with invalid parameters!');
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
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



  public function testDefaultValue()
  {
    $config = array(
      'test' => [
        'type'    => 'string',
        'options' => [
          'required'     => true,
          'defaultValue' => 'Hallo Welt!'
        ]
      ]
    );

    $class = $this->container->get('enm.transformer.service')->transform(new \stdClass(), $config, array());
    $this->assertEquals('Hallo Welt!', $class->test);
  }



  public function testRename()
  {
    $config = array(
      'test' => [
        'type'     => 'string',
        'renameTo' => 'abc',
        'options'  => [
          'required' => true,
        ]
      ]
    );

    $class = $this->container->get('enm.transformer.service')->transform(
                             new \stdClass(),
                               $config,
                               array('test' => 'Hallo Welt!')
    );
    $this->assertEquals('Hallo Welt!', $class->abc);
  }
} 