<?php


namespace Ssc\ApiBundle\Tests\NonComplex;

use ENM\TransformerBundle\Tests\BaseTest;

class DateTest extends BaseTest
{

  public function testDate()
  {
    $config = array(
      'date' => [
        'type'    => 'date',
        'options' => [
          'date' => [
            'format'          => 'Y/m/d',
            'convertToObject' => true,
          ]
        ]
      ]
    );

    try
    {
      $date = $this->container->get('enm.transformer.service')->transform(
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
      $this->container->get('enm.transformer.service')->transform(
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
}