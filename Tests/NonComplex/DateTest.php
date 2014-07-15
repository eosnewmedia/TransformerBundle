<?php


namespace Ssc\ApiBundle\Tests\NonComplex;

use ENM\TransformerBundle\Tests\BaseTest;

class DateTest extends BaseTest
{

  public function testDate()
  {
    $config = array(
      'date' => [
        'complex' => false,
        'type'    => 'date',
        'options' => [
          'date' => [
            'format' => 'Y/m/d',
            'convertToFormat' => 'c',
          ]
        ]
      ]
    );

    $date = $this->container->get('enm.array.transformer.service')->transform(
                            new \stdClass(),
                              $config,
                              array('date' => '2014/07/10')
    );
  }
}