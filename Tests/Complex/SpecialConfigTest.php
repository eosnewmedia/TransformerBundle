<?php


namespace Enm\TransformerBundle\Tests\Complex;

use Enm\TransformerBundle\Tests\BaseTest;
use Enm\TransformerBundle\Resources\TestClass\TestConfiguration;
use Enm\TransformerBundle\Resources\TestClass\UserTestClass;

class SpecialConfigTest extends BaseTest
{

  public function testLocalTransformerConfigurationArray()
  {
    $config = array();
    $params = array(
      'user'     => 'testUser',
      'birthday' => '1990-01-01',
    );

    $transformer_config = array(
      'events' => [
        'listeners'   => array(),
        'subscribers' => array()
      ]
    );

    $transformer = $this->container->get('enm.transformer.service');

    try
    {
      $transformer->transform(new UserTestClass(), $config, $params, $transformer_config);
      $this->assertTrue(true);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getMessage());
    }
  }
} 