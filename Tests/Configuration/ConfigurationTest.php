<?php


namespace ENM\TransformerBundle\Tests\Configuration;

use ENM\TransformerBundle\ConfigurationStructure\TypeEnum;
use ENM\TransformerBundle\Resources\TestClass\TestConfiguration;
use ENM\TransformerBundle\Tests\BaseTest;

class ConfigurationTest extends BaseTest
{

  public function testConfiguration()
  {
    $manager = $this->container->get('enm.transformer.configuration.manager');

    try
    {
      $manager->getConfig(TestConfiguration::getConfig());
      $this->assertTrue(true);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getMessage());
    }
  }
} 