<?php


namespace ENM\TransformerBundle;

class TransformerEvents
{

  /**
   * Throws \ENM\TransformerBundle\Event\ExceptionEvent
   */
  const ON_EXCEPTION = 'transformer.event.on.exception';

  /**
   * Throws \ENM\TransformerBundle\Event\ConfigurationEvent
   */
  const BEFORE_CHILD_CONFIGURATION = 'transformer.event.before.child_configuration';


  /**
   * Throws \ENM\TransformerBundle\Event\ConfigurationEvent
   */
  const AFTER_CHILD_CONFIGURATION = 'transformer.event.after.child_configuration';

  /**
   * Throws \ENM\TransformerBundle\Event\ConfigurationEvent
   */
  const AFTER_CONFIGURATION = 'transformer.event.after.configuration';

  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const BEFORE_RUN = 'transformer.event.before.run';

  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const AFTER_RUN = 'transformer.event.after.run';

  /**
   * Throws \ENM\TransformerBundle\Event\ValidatorEvent
   */
  const BEFORE_VALIDATION = 'transformer.event.before.validation';

  /**
   * Throws \ENM\TransformerBundle\Event\ValidatorEvent
   */
  const AFTER_VALIDATION = 'transformer.event.after.validation';

  /**
   * Throws \ENM\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_STRING = 'transformer.event.validate.string';

  /**
   * Throws \ENM\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_INTEGER = 'transformer.event.validate.integer';

  /**
   * Throws \ENM\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_FLOAT = 'transformer.event.validate.float';

  /**
   * Throws \ENM\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_ARRAY = 'transformer.event.validate.array';

  /**
   * Throws \ENM\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_BOOL = 'transformer.event.validate.bool';

  /**
   * Throws \ENM\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_COLLECTION = 'transformer.event.validate.collection';

  /**
   * Throws \ENM\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_DATE = 'transformer.event.validate.date';

  /**
   * Throws \ENM\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_INDIVIDUAL = 'transformer.event.validate.individual';

  /**
   * Throws \ENM\TransformerBundle\Event\ValidatorEvent
   */
  const VALIDATE_OBJECT = 'transformer.event.validate.object';


  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_INDIVIDUAL = 'transformer.event.prepare.individual';


  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_VALUE = 'transformer.event.prepare.value';

  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_DEFAULT = 'transformer.event.prepare.default';

  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_COLLECTION = 'transformer.event.prepare.collection';

  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_OBJECT = 'transformer.event.prepare.object';

  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_DATE = 'transformer.event.prepare.date';

  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const REVERSE_TRANSFORM = 'transformer.event.reverse.transform';

  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const REVERSE_COLLECTION = 'transformer.event.reverse.collection';

  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const REVERSE_OBJECT = 'transformer.event.reverse.object';

  /**
   * Throws \ENM\TransformerBundle\Event\ClassBuilderEvent
   */
  const OBJECT_CREATE_INSTANCE = 'transformer.event.object.create_instance';

  /**
   * Throws \ENM\TransformerBundle\Event\ClassBuilderEvent
   */
  const OBJECT_RETURN_INSTANCE = 'transformer.event.object.return_instance';
}