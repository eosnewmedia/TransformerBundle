<?php


namespace ENM\TransformerBundle\Interfaces;

interface FullStructureInterface
{


  public static function createNewInstance();



  /**
   * @return FullStructureInterface
   */
  public static function getInstance();



  /**
   * @return array
   * @throws \ENM\TransformerBundle\Exceptions\TransformerConfigurationException
   */
  public function getArray();



  /**
   * @param array $full_array
   *
   * @throws \ENM\TransformerBundle\Exceptions\TransformerConfigurationException
   */
  public function setArray(array $full_array);
} 