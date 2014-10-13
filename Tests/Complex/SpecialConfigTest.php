<?php


namespace Enm\TransformerBundle\Tests\Complex;

use Enm\TransformerBundle\Tests\BaseTransformerTestClass;
use Enm\TransformerBundle\Resources\TestClass\UserTestClass;

class SpecialConfigTest extends BaseTransformerTestClass
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

    $transformer = $this->getTransformer();

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
