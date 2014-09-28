<?php


namespace Enm\TransformerBundle\Tests\Configuration;

use Enm\TransformerBundle\ConfigurationStructure\TypeEnum;
use Enm\TransformerBundle\Helper\Configurator;
use Enm\TransformerBundle\Resources\TestClass\TestConfiguration;
use Enm\TransformerBundle\Tests\BaseTest;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ConfigurationTest extends BaseTest
{

  public function testConfiguration()
  {
    $manager = new Configurator(TestConfiguration::getConfig(), new EventDispatcher());

    try
    {
      $manager->getConfig();
      $this->assertTrue(true);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getMessage());
    }
  }
} 