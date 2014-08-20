<?php


namespace ENM\TransformerBundle\Tests\Configuration;

use ENM\TransformerBundle\ConfigurationStructure\TypeEnum;
use ENM\TransformerBundle\Helper\Configurator;
use ENM\TransformerBundle\Resources\TestClass\TestConfiguration;
use ENM\TransformerBundle\Tests\BaseTest;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ConfigurationTest extends BaseTest
{

  public function testConfiguration()
  {
    $manager = new Configurator(new EventDispatcher());

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