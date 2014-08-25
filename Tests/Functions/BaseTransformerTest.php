<?php


namespace ENM\TransformerBundle\Tests\Functions;

use ENM\TransformerBundle\ConfigurationStructure\Configuration;
use ENM\TransformerBundle\ConfigurationStructure\ConfigurationOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\CollectionOptions;
use ENM\TransformerBundle\ConfigurationStructure\Parameter;
use ENM\TransformerBundle\Exceptions\TransformerException;
use ENM\TransformerBundle\Listener\ExceptionListener;
use ENM\TransformerBundle\Manager\BaseTransformerManager;
use ENM\TransformerBundle\Tests\BaseTest;
use ENM\TransformerBundle\TransformerEvents;
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
      $paramBag   = $this->container->get('assetic.parameter_bag');
      $stopwatch  = new Stopwatch();

      $this->transformer = new BaseTransformerManager($dispatcher, $validator, $paramBag, $stopwatch);
    }

    return $this->transformer;
  }



  protected function getMethod($name)
  {
    $class  = new \ReflectionClass('ENM\TransformerBundle\Manager\BaseTransformerManager');
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
              'format' => 'Y-m-d'
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