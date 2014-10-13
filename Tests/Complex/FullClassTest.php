<?php


namespace Enm\TransformerBundle\Tests\Complex;

use Enm\TransformerBundle\Tests\BaseTransformerTestClass;
use Enm\TransformerBundle\Resources\TestClass\TestConfiguration;
use Enm\TransformerBundle\Resources\TestClass\UserTestClass;

class FullClassTest extends BaseTransformerTestClass
{

  public function testTestCase()
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
      'birthday' => '1990-01-01',
      'hobbys'   => [
        [
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

    $transformer = $this->getTransformer();

    try
    {
      $transformer->transform(new UserTestClass(), $config, $params);
      $this->assertTrue(true);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getMessage());
    }
  }
}
