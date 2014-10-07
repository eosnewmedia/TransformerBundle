<?php


namespace Enm\TransformerBundle;

class TransformerEvents
{

  /**
   * Throws \Enm\TransformerBundle\Event\ExceptionEvent
   */
  const ON_EXCEPTION = 'enm.transformer.event.on.exception';

  /**
   * Throws \Enm\TransformerBundle\Event\ConfigurationEvent
   */
  const BEFORE_CHILD_CONFIGURATION = 'enm.transformer.event.before.child_configuration';


  /**
   * Throws \Enm\TransformerBundle\Event\ConfigurationEvent
   */
  const AFTER_CHILD_CONFIGURATION = 'enm.transformer.event.after.child_configuration';

  /**
   * Throws \Enm\TransformerBundle\Event\ConfigurationEvent
   */
  const AFTER_CONFIGURATION = 'enm.transformer.event.after.configuration';

  const AFTER_GLOBAL_VALUES = 'enm.transformer.event.after.global.values';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const BEFORE_RUN = 'enm.transformer.event.before.run';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const AFTER_RUN = 'enm.transformer.event.after.run';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const BEFORE_VALIDATION = 'enm.transformer.event.before.validation';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const AFTER_VALIDATION = 'enm.transformer.event.after.validation';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_STRING = 'enm.transformer.event.validate.string';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_INTEGER = 'enm.transformer.event.validate.integer';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_FLOAT = 'enm.transformer.event.validate.float';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_ARRAY = 'enm.transformer.event.validate.array';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_BOOL = 'enm.transformer.event.validate.bool';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_COLLECTION = 'enm.transformer.event.validate.collection';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_DATE = 'enm.transformer.event.validate.date';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_INDIVIDUAL = 'enm.transformer.event.validate.individual';

  /**
   * Throws \Enm\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_OBJECT = 'enm.transformer.event.validate.object';


  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_INDIVIDUAL = 'enm.transformer.event.prepare.individual';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_ARRAY = 'enm.transformer.event.prepare.array';


  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_VALUE = 'enm.transformer.event.prepare.value';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_DEFAULT = 'enm.transformer.event.prepare.default';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_COLLECTION = 'enm.transformer.event.prepare.collection';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_OBJECT = 'enm.transformer.event.prepare.object';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_DATE = 'enm.transformer.event.prepare.date';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const REVERSE_TRANSFORM = 'enm.transformer.event.reverse.transform';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const REVERSE_COLLECTION = 'enm.transformer.event.reverse.collection';

  /**
   * Throws \Enm\TransformerBundle\Event\TransformerEvent
   */
  const REVERSE_OBJECT = 'enm.transformer.event.reverse.object';

  /**
   * Throws \Enm\TransformerBundle\Event\ClassBuilderEvent
   */
  const OBJECT_CREATE_INSTANCE = 'enm.transformer.event.object.create_instance';

  /**
   * Throws \Enm\TransformerBundle\Event\ClassBuilderEvent
   */
  const OBJECT_RETURN_INSTANCE = 'enm.transformer.event.object.return_instance';
}