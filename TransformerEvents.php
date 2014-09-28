<?php


namespace Enm\TransformerBundle;

class TransformerEvents
{

  /**
   * Throws \Enm\TransformerBundle\Event\ExceptionEvent
   */
  const ON_EXCEPTION = 'transformer.event.on.exception';

  /**
   * Throws \Enm\TransformerBundle\Event\ConfigurationEvent
   */
  const BEFORE_CHILD_CONFIGURATION = 'transformer.event.before.child_configuration';


  /**
   * Throws \Enm\TransformerBundle\Event\ConfigurationEvent
   */
  const AFTER_CHILD_CONFIGURATION = 'transformer.event.after.child_configuration';

  /**
   * Throws \Enm\TransformerBundle\Event\ConfigurationEvent
   */
  const AFTER_CONFIGURATION = 'transformer.event.after.configuration';

  const AFTER_GLOBAL_VALUES = 'transformer.event.after.global.values';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const BEFORE_RUN = 'transformer.event.before.run';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const AFTER_RUN = 'transformer.event.after.run';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const BEFORE_VALIDATION = 'transformer.event.before.validation';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const AFTER_VALIDATION = 'transformer.event.after.validation';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_STRING = 'transformer.event.validate.string';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_INTEGER = 'transformer.event.validate.integer';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_FLOAT = 'transformer.event.validate.float';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_ARRAY = 'transformer.event.validate.array';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_BOOL = 'transformer.event.validate.bool';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_COLLECTION = 'transformer.event.validate.collection';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_DATE = 'transformer.event.validate.date';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_INDIVIDUAL = 'transformer.event.validate.individual';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_OBJECT = 'transformer.event.validate.object';


  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_INDIVIDUAL = 'transformer.event.prepare.individual';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_ARRAY = 'transformer.event.prepare.array';


  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_VALUE = 'transformer.event.prepare.value';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_DEFAULT = 'transformer.event.prepare.default';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_COLLECTION = 'transformer.event.prepare.collection';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_OBJECT = 'transformer.event.prepare.object';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_DATE = 'transformer.event.prepare.date';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const REVERSE_TRANSFORM = 'transformer.event.reverse.transform';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const REVERSE_COLLECTION = 'transformer.event.reverse.collection';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const REVERSE_OBJECT = 'transformer.event.reverse.object';

  /**
   * Throws \Enm\TransformerBundle\Event\ClassBuilderEvent
   */
  const OBJECT_CREATE_INSTANCE = 'transformer.event.object.create_instance';

  /**
   * Throws \Enm\TransformerBundle\Event\ClassBuilderEvent
   */
  const OBJECT_RETURN_INSTANCE = 'transformer.event.object.return_instance';
}