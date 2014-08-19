<?php


namespace ENM\TransformerBundle;

class TransformerEvents
{

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
  const BEFORE_CLASS_SET_VALUE = 'transformer.event.before.class_set_value';

  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const AFTER_CLASS_SET_VALUE = 'transformer.event.before.class_set_value';

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
  const BEFORE_NORMALIZATION = 'transformer.event.before.normalization';

  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const AFTER_NORMALIZATION = 'transformer.event.after.normalization';

  /**
   * Throws \ENM\TransformerBundle\Event\TransformerEvent
   */
  const PREPARE_INDIVIDUAL = 'transformer.event.prepare.individual';
}