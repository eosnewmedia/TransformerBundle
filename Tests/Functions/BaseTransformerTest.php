<?php


namespace Enm\TransformerBundle\Tests\Functions;

use Enm\TransformerBundle\ConfigurationStructure\Configuration;
use Enm\TransformerBundle\ConfigurationStructure\ConfigurationOptions;
use Enm\TransformerBundle\ConfigurationStructure\OptionStructures\CollectionOptions;
use Enm\TransformerBundle\ConfigurationStructure\Parameter;
use Enm\TransformerBundle\Exceptions\TransformerException;
use Enm\TransformerBundle\Listener\ExceptionListener;
use Enm\TransformerBundle\Manager\BaseTransformerManager;
use Enm\TransformerBundle\Tests\BaseTest;
use Enm\TransformerBundle\TransformerEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Stopwatch\Stopwatch;

class BaseTransformerTest extends BaseTest
{

  protected $transformer;



  protected function getInstanz()
  {
    if (!$this->transformer instanceof BaseTransformerManager)
    {
      $dispatcher = new EventDispatcher();
      $validator  = $this->container->get('validator');
      $paramBag   = $this->container->getParameter('transformer.config');
      $stopwatch  = new Stopwatch();

      $this->transformer = new BaseTransformerManager($dispatcher, $validator, $paramBag, $stopwatch);
    }

    return $this->transformer;
  }



  protected function getMethod($name)
  {
    $class  = new \ReflectionClass('Enm\TransformerBundle\Manager\BaseTransformerManager');
    $method = $class->getMethod($name);
    $method->setAccessible(true);

    return $method;
  }



  public function testProcess()
  {
    try
    {
      $config = array(
        'user'     => [
          'type'     => 'string',
          'renameTo' => 'username',
          'options'  => [
            'required' => true,
            'expected' => array('testUser'),
            'regex'    => '([a-zA-Z0-9])',
            'length'   => [
              'min' => 3,
              'max' => 12
            ]
          ]
        ],
        'birthday' => [
          'type'    => 'date',
          'options' => [
            'requiredIfAvailable' => [
              'or' => array('user')
            ],
            'date'                => [
              'expectedFormat' => 'Y-m-d'
            ]
          ]
        ],
        'address'  => [
          'type'     => 'collection',
          'renameTo' => 'TEST',
          'options'  => [
            'returnClass' => '\stdClass'
          ],
          'children' => [
            'street' => [
              'type' => 'string',
            ]
          ]
        ]
      );
      $params = array(
        'user'     => 'testUser',
        'birthday' => '1990-01-01',
      );
      $method = $this->getMethod('process');
      $result = $method->invokeArgs($this->getInstanz(), array('\stdClass', $config, $params));
      $this->assertEquals('testUser', $result->username);
      $this->assertEquals('1990-01-01', $result->birthday);
    }
    catch (TransformerException $e)
    {
      $this->fail($e->getMessage());
    }
  }



  public function testInit()
  {
    try
    {
      $method = $this->getMethod('init');
      $method->invoke($this->getInstanz());
      $this->assertTrue(true);
    }
    catch (TransformerException $e)
    {
      $this->fail($e->getMessage());
    }
  }



  public function testPrepareCollection()
  {
    $childrenConfiguration = new Configuration('street');
    $childrenConfiguration->setType('string');
    $childrenConfiguration->setEvents(array('listeners' => array(), 'subscribers' => array()));

    $configuration = new Configuration('address');
    $configuration->setType('collection');
    $configuration->setRenameTo('TEST');
    $options          = new ConfigurationOptions();
    $collectionOption = new CollectionOptions();
    $collectionOption->setReturnClass('\stdClass');
    $options->setCollectionOptions($collectionOption);
    $configuration->setOptions($options);
    $configuration->setChildren(array($childrenConfiguration));

    try
    {
      $method = $this->getMethod('prepareCollection');
      $method->invokeArgs($this->getInstanz(), array($configuration, new Parameter('address', array())));

      $method->invokeArgs(
             $this->getInstanz(),
               array($configuration, new Parameter('address', array(['street' => 'bla'])))
      );
      $this->assertTrue(true);
    }
    catch (TransformerException $e)
    {
      $this->fail($e->getMessage());
    }
  }



  public function testDestroy()
  {
    try
    {
      $method = $this->getMethod('destroy');
      $method->invoke($this->getInstanz());
      $this->assertTrue(true);
    }
    catch (TransformerException $e)
    {
      $this->fail($e->getMessage());
    }
  }
} 