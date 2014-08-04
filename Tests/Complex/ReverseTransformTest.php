<?php


namespace Ssc\ApiBundle\Tests\Complex;

use ENM\TransformerBundle\Resources\TestClass\TestConfiguration;
use ENM\TransformerBundle\Resources\TestClass\UserTestClass;
use ENM\TransformerBundle\Tests\BaseTest;

class ReverseTransformTest extends BaseTest
{

  public function testReverse()
  {
    $config = TestConfiguration::getConfig();

    $params = array(
      'username' => 'testUser',
      'address'  => [
        'street'  => 'Test Straße 3a',
        'plz'     => '21031',
        'place'   => 'Test Place',
        'country' => 'Germany'
      ],
      'birthday' => '1990-01-01',
      'hobbys'   => [
        [
          'name'       => 'Musik',
          'years'      => 4,
          'daysInWeek' => 1
        ],
        [
          'name'       => 'Fitness',
          'years'      => 1.4,
          'daysInWeek' => 3
        ]
      ]
    );

    $transformer = $this->container->get('enm.transformer.service');

    try
    {
      $object = $transformer->transform(new UserTestClass(), $config, $params);

      $this->assertArrayHasKey('username', $transformer->reverseTransform($object, $config, 'array'));
      $this->assertObjectHasAttribute('username', $transformer->reverseTransform($object, $config, 'object'));
      $this->assertJson($transformer->reverseTransform($object, $config, 'string'));
      $this->assertJson($transformer->reverseTransform($object, $config, 'json'));

      $this->assertTrue(true);
    }
    catch (\Exception $e)
    {
      $this->fail($e);
    }
  }
}