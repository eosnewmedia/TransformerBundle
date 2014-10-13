<?php
/**
 * CollectionTest.php
 * Date: 10.07.14
 *
 * @author Philipp Marien <marien@eosnewmedia.de>
 */

namespace Enm\TransformerBundle\Tests\Complex;

use Enm\TransformerBundle\Tests\BaseTransformerTestClass;

class CollectionTest extends BaseTransformerTestClass
{

  public function testCollection()
  {
    $manager = $this->getTransformer();

    $config = array(
      'list' => array(
        'children' => array(
          'test' => array(
            'type'    => 'string',
            'options' => array(
              'required' => true,
            )
          ),
        ),
        'type'     => 'collection',
        'options'  => array(
          'required'    => true,
          'returnClass' => '\stdClass',
        )
      )
    );

    $params = array(
      'list' => array(
        0 => array(
          'test' => 'hallo',
        ),
        1 => array(
          'test' => '123',
        ),
        2 => array(
          'test' => 'abc',
        ),
      )
    );

    $result = $manager->transform(new \stdClass(), $config, $params);

    $this->assertArrayHasKey(0, $result->list);
    $this->assertArrayHasKey(1, $result->list);
    $this->assertArrayHasKey(2, $result->list);
  }
}
