<?php


namespace Enm\TransformerBundle\Interfaces;

interface FullStructureInterface
{


  public static function createNewInstance();



  /**
   * @return FullStructureInterface
   */
  public static function getInstance();



  /**
   * @return array
   * @throws \Enm\TransformerBundle\Exceptions\TransformerConfigurationException
   */
  public function getArray();



  /**
   * @param array $full_array
   *
   * @throws \Enm\TransformerBundle\Exceptions\TransformerConfigurationException
   */
  public function setArray(array $full_array);
} 