<?php


namespace ENM\TransformerBundle\Tests\Complex;

use ENM\TransformerBundle\Resources\TestClass\TestConfiguration;
use ENM\TransformerBundle\Resources\TestClass\UserTestClass;
use ENM\TransformerBundle\Tests\BaseTest;

class ReverseTransformTest extends BaseTest
{

  public function testReverse()
  {
    $config = TestConfiguration::getConfig();

    $params = array(
      'user'     => 'testUser',
      'address'  => [
        'street'  => 'Test Straße 3a',
        'plz'     => '21031',
        'place'   => 'Test Place',
        'country' => 'Germany'
      ],
      'birthday' => '1992-10-14',
      'hobbys'   => [
        [
          'abc'          => 'lalala',
          'name'         => 'Musik',
          'years'        => 4,
          'days_in_week' => 1
        ],
        [
          'name'         => 'Fitness',
          'years'        => 1.4,
          'days_in_week' => 3
        ]
      ]
    );

    $transformer = $this->container->get('enm.transformer.service');

    try
    {
      $object = $transformer->transform(new UserTestClass(), $config, $params);
      $this->assertArrayHasKey('user', $transformer->reverseTransform($object, $config, 'array'));
      $this->assertObjectHasAttribute('user', $transformer->reverseTransform($object, $config, 'object'));
      $this->assertJson($transformer->reverseTransform($object, $config, 'string'));
      $this->assertJson($transformer->reverseTransform($object, $config, 'json'));

      $this->assertTrue(true);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getFile() . ' - ' . $e->getLine() . ': ' . $e->getMessage());
    }
  }
}