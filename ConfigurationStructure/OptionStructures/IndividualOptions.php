<?php


namespace ENM\TransformerBundle\ConfigurationStructure\OptionStructures;

class IndividualOptions
{

  protected $options = array();



  /**
   * @param array $options
   */
  public function setOptions(array $options)
  {
    $this->options = $options;

    return $this;
  }



  /**
   * @return array
   */
  public function getOptions()
  {
    return $this->options;
  }
}