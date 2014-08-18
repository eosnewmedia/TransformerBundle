<?php


namespace Ssc\ApiBundle\Tests\Complex;

use ENM\TransformerBundle\Resources\TestClass\TestConfiguration;
use ENM\TransformerBundle\Tests\BaseTest;

class ArrayFromConfigTest extends BaseTest
{

  public function testTestCase()
  {
    $config = TestConfiguration::getConfig();

    $transformer = $this->container->get('enm.transformer.service');

    try
    {
      $array = $transformer->getEmptyObjectStructureFromConfig(new \stdClass(), $config, 'array');
      $this->assertArrayHasKey('user', $array);
      $this->assertArrayHasKey('address', $array);
      $this->assertArrayHasKey('street', $array['address']);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getMessage());
    }
  }
} 