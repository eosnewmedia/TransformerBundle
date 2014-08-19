<?php


namespace ENM\TransformerBundle\Tests\V2;

use ENM\TransformerBundle\Manager\BaseTransformerManagerV2;
use ENM\TransformerBundle\Resources\TestClass\TestConfiguration;
use ENM\TransformerBundle\Resources\TestClass\UserTestClass;
use ENM\TransformerBundle\Tests\BaseTest;

class TransformerTest extends BaseTest
{

  public function testTransformer()
  {
    $manager = new BaseTransformerManagerV2($this->container->get('event_dispatcher'), $this->container->get(
                                                                                                       'validator'
    ));
    $params  = array(
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

    var_dump($manager->process(new UserTestClass(), TestConfiguration::getConfig(), $params));
  }
} 