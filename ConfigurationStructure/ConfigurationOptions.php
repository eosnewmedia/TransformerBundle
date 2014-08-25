<?php


namespace ENM\TransformerBundle\ConfigurationStructure;

use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\ArrayOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\BoolOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\CollectionOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\DateOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\FloatOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\IndividualOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\IntegerOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\MethodOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\ObjectOptions;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\RequiredIfStructure;
use ENM\TransformerBundle\ConfigurationStructure\OptionStructures\StringOptions;

class ConfigurationOptions
{

  protected $required = false;

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\RequiredIfStructure
   */
  protected $requiredIfNotAvailable;

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\RequiredIfStructure
   */
  protected $requiredIfAvailable;

  /**
   * @var array
   */
  protected $forbiddenIfNotAvailable = array();

  /**
   * @var array
   */
  protected $forbiddenIfAvailable = array();

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\StringOptions
   */
  protected $stringOptions;

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\IntegerOptions
   */
  protected $integerOptions;

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\FloatOptions
   */
  protected $floatOptions;

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\BoolOptions
   */
  protected $boolOptions;

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\DateOptions
   */
  protected $dateOptions;

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\ObjectOptions
   */
  protected $objectOptions;

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\CollectionOptions
   */
  protected $collectionOptions;

  /**
   * @var \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\ArrayOptions
   */
  protected $arrayOptions;

  /**
   * @var OptionStructures\IndividualOptions
   */
  protected $individualOptions;



  public function __construct()
  {
    $this->requiredIfNotAvailable = new RequiredIfStructure();
    $this->requiredIfAvailable    = new RequiredIfStructure();
    $this->arrayOptions           = new ArrayOptions();
    $this->boolOptions            = new BoolOptions();
    $this->collectionOptions      = new CollectionOptions();
    $this->dateOptions            = new DateOptions();
    $this->floatOptions           = new FloatOptions();
    $this->integerOptions         = new IntegerOptions();
    $this->individualOptions      = new IndividualOptions();
    $this->objectOptions          = new ObjectOptions();
    $this->stringOptions          = new StringOptions();
  }



  /**
   * @param array $forbiddenIfAvailable
   */
  public function setForbiddenIfAvailable(array $forbiddenIfAvailable)
  {
    $this->forbiddenIfAvailable = $forbiddenIfAvailable;

    return $this;
  }



  /**
   * @return array
   */
  public function getForbiddenIfAvailable()
  {
    return $this->forbiddenIfAvailable;
  }



  /**
   * @param array $forbiddenIfNotAvailable
   */
  public function setForbiddenIfNotAvailable(array $forbiddenIfNotAvailable)
  {
    $this->forbiddenIfNotAvailable = $forbiddenIfNotAvailable;

    return $this;
  }



  /**
   * @return array
   */
  public function getForbiddenIfNotAvailable()
  {
    return $this->forbiddenIfNotAvailable;
  }



  /**
   * @param boolean $required
   */
  public function setRequired($required)
  {
    $this->required = $required;

    return $this;
  }



  /**
   * @return boolean
   */
  public function getRequired()
  {
    return $this->required;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\RequiredIfStructure $requiredIfAvailable
   */
  public function setRequiredIfAvailable(RequiredIfStructure $requiredIfAvailable)
  {
    $this->requiredIfAvailable = $requiredIfAvailable;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\RequiredIfStructure
   */
  public function getRequiredIfAvailable()
  {
    return $this->requiredIfAvailable;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\RequiredIfStructure $requiredIfNotAvailable
   */
  public function setRequiredIfNotAvailable(RequiredIfStructure $requiredIfNotAvailable)
  {
    $this->requiredIfNotAvailable = $requiredIfNotAvailable;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\RequiredIfStructure
   */
  public function getRequiredIfNotAvailable()
  {
    return $this->requiredIfNotAvailable;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\ArrayOptions $arrayOptions
   */
  public function setArrayOptions(ArrayOptions $arrayOptions)
  {
    $this->arrayOptions = $arrayOptions;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\ArrayOptions
   */
  public function getArrayOptions()
  {
    return $this->arrayOptions;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\BoolOptions $boolOptions
   */
  public function setBoolOptions(BoolOptions $boolOptions)
  {
    $this->boolOptions = $boolOptions;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\BoolOptions
   */
  public function getBoolOptions()
  {
    return $this->boolOptions;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\CollectionOptions $collectionOptions
   */
  public function setCollectionOptions(CollectionOptions $collectionOptions)
  {
    $this->collectionOptions = $collectionOptions;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\CollectionOptions
   */
  public function getCollectionOptions()
  {
    return $this->collectionOptions;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\DateOptions $dateOptions
   */
  public function setDateOptions(DateOptions $dateOptions)
  {
    $this->dateOptions = $dateOptions;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\DateOptions
   */
  public function getDateOptions()
  {
    return $this->dateOptions;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\FloatOptions $floatOptions
   */
  public function setFloatOptions(FloatOptions $floatOptions)
  {
    $this->floatOptions = $floatOptions;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\FloatOptions
   */
  public function getFloatOptions()
  {
    return $this->floatOptions;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\IntegerOptions $integerOptions
   */
  public function setIntegerOptions(IntegerOptions $integerOptions)
  {
    $this->integerOptions = $integerOptions;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\IntegerOptions
   */
  public function getIntegerOptions()
  {
    return $this->integerOptions;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\IndividualOptions $individualOptions
   */
  public function setIndividualOptions(IndividualOptions $individualOptions)
  {
    $this->individualOptions = $individualOptions;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\IndividualOptions
   */
  public function getIndividualOptions()
  {
    return $this->individualOptions;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\ObjectOptions $objectOptions
   */
  public function setObjectOptions(ObjectOptions $objectOptions)
  {
    $this->objectOptions = $objectOptions;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\ObjectOptions
   */
  public function getObjectOptions()
  {
    return $this->objectOptions;
  }



  /**
   * @param \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\StringOptions $stringOptions
   */
  public function setStringOptions(StringOptions $stringOptions)
  {
    $this->stringOptions = $stringOptions;

    return $this;
  }



  /**
   * @return \ENM\TransformerBundle\ConfigurationStructure\OptionStructures\StringOptions
   */
  public function getStringOptions()
  {
    return $this->stringOptions;
  }
}