<?php


namespace Enm\TransformerBundle\Tests\NonComplex;

use Enm\TransformerBundle\Tests\BaseTransformerTestClass;

class DateTest extends BaseTransformerTestClass
{

  public function testDate()
  {
    $config = array(
      'date' => [
        'type'    => 'date',
        'options' => [
          'date' => [
            'expectedFormat'  => 'Y/m/d',
            'convertToObject' => true,
          ]
        ]
      ]
    );

    try
    {
      $date = $this->getTransformer()->transform(
        new \stdClass(),
        $config,
        array('date' => '2014/07/10')
      );
      $this->assertInstanceOf('DateTime', $date->date);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getMessage());
    }
    try
    {
      $this->getTransformer()->transform(
        new \stdClass(),
        $config,
        array('date' => '2014-07-10')
      );
      $this->fail('No Exception thrown with Invalid Parameter!');
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  public function testDateWithDefault()
  {
    $config = array(
      'date' => [
        'type'    => 'date',
        'options' => [
          'date' => [
            'convertToObject' => true,
          ]
        ]
      ]
    );

    try
    {
      $date = $this->getTransformer()->transform(
        new \stdClass(),
        $config,
        array('date' => '2014-07-10')
      );
      $this->assertInstanceOf('DateTime', $date->date);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getMessage());
    }
    try
    {
      $this->getTransformer()->transform(
        new \stdClass(),
        $config,
        array('date' => '2014/07/10')
      );
      $this->fail('No Exception thrown with Invalid Parameter!');
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }



  public function testDateWithFormatArray()
  {
    $config = array(
      'date' => [
        'type'    => 'date',
        'options' => [
          'date' => [
            'expectedFormat'  => ['Y-m-d', 'Y/m/d'],
            'convertToObject' => true,
          ]
        ]
      ]
    );

    try
    {
      $date = $this->getTransformer()->transform(
        new \stdClass(),
        $config,
        array('date' => '2014-07-10')
      );
      $this->assertInstanceOf('DateTime', $date->date);
      $date = $this->getTransformer()->transform(
        new \stdClass(),
        $config,
        array('date' => '2014/07/10')
      );
      $this->assertInstanceOf('DateTime', $date->date);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getMessage());
    }
    try
    {
      $this->getTransformer()->transform(
        new \stdClass(),
        $config,
        array('date' => '10.07.2014')
      );
      $this->fail('No Exception thrown with Invalid Parameter!');
    }
    catch (\Exception $e)
    {
      $this->assertTrue(true);
    }
  }
}