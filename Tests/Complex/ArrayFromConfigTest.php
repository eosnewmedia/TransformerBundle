<?php


namespace Enm\TransformerBundle\Tests\Complex;

use Enm\TransformerBundle\Resources\TestClass\TestConfiguration;
use Enm\TransformerBundle\Tests\BaseTest;

class ArrayFromConfigTest extends BaseTest
{

  public function testTestCase()
  {
    $config = TestConfiguration::getConfig();

    $transformer = $this->container->get('enm.transformer');

    try
    {
      $array = $transformer->getEmptyObjectStructureFromConfig($config, 'array');
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