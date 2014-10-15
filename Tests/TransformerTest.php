<?php


namespace Enm\TransformerBundle\Tests;

use Enm\Transformer\Transformer;
use Symfony\Component\EventDispatcher\EventDispatcher;

class TransformerTest extends \PHPUnit_Framework_TestCase
{

  public function testTransformer()
  {
    $transformer = new Transformer(new EventDispatcher(), array());

    $config = array(
      'test' => [
        'type' => 'string'
      ]
    );

    $this->assertEquals(
      'Hallo Welt!',
      $transformer->transform('stdClass', $config, array('test' => 'Hallo Welt!'))->test
    );
  }
}
 