<?php


namespace Enm\TransformerBundle\Tests;

use Enm\Transformer\Interfaces\TransformerInterface;
use Enm\Transformer\Transformer;
use Enm\TransformerBundle\DependencyInjection\EnmTransformerExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class TransformerTest extends \PHPUnit_Framework_TestCase
{

    public function testTransformer()
    {
        try {
            $transformer = new Transformer(new EventDispatcher(), array());

            $config = array(
              'test' => [
                'type' => 'string',
              ],
            );

            static::assertEquals(
              'Hallo Welt!',
              $transformer->transform(
                'stdClass',
                $config,
                array('test' => 'Hallo Welt!')
              )->test
            );
        } catch (\Exception $e) {
            static::fail($e->getMessage());
        }
    }

    public function testServicesYaml()
    {
        try {
            $extension = new EnmTransformerExtension();
            $container = new ContainerBuilder();
            $extension->load(array(), $container);
            $transformer = $container->get('enm.transformer');
            static::assertTrue($transformer instanceof TransformerInterface);
            $dispatcher = $container->get('enm.transformer.event_dispatcher');
            static::assertTrue($dispatcher instanceof EventDispatcherInterface);
        } catch (\Exception $e) {
            static::fail($e->getMessage());
        }
    }
}
 