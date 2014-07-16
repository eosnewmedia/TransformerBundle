<?php


namespace ENM\TransformerBundle\Interfaces;

interface TransformerInterface
{

  /**
   * @param object|string       $returnClass
   * @param array               $config
   * @param array|object|string $values
   *
   * @return object
   * @throws \ENM\TransformerBundle\Exceptions\InvalidArgumentException
   */
  public function transform($returnClass, array $config, $values);
} 