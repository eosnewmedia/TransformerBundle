<?php


namespace Ssc\ApiBundle\Tests\Complex;

use ENM\TransformerBundle\Tests\BaseTest;

class ExternalMethodTest extends BaseTest
{

  public function testMethod()
  {
    $config = array(
      'test' => [
        'type'    => 'method',
        'options' => [
          'methodClass' => 'ENM\TransformerBundle\Resources\TestClass\SpecialMethodTestClass',
          'method'      => 'specialMethod'
        ]
      ]
    );

    $params = array(
      'test' => 'Hallo Welt'
    );

    try
    {
      $this->container->get('enm.transformer.service')->transform(new \stdClass(), $config, $params);
      $this->assertTrue(true);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getMessage());
    }
  }
} 