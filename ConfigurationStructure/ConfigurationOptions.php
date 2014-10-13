<?php


namespace Enm\TransformerBundle\ConfigurationStructure;

use Enm\TransformerBundle\ConfigurationStructure\OptionStructures\ArrayOptions;
use Enm\TransformerBundle\ConfigurationStructure\OptionStructures\BoolOptions;
use Enm\TransformerBundle\ConfigurationStructure\OptionStructures\CollectionOptions;
use Enm\TransformerBundle\ConfigurationStructure\OptionStructures\DateOptions;
use Enm\TransformerBundle\ConfigurationStructure\OptionStructures\FloatOptions;
use Enm\TransformerBundle\ConfigurationStructure\OptionStructures\IndividualOptions;
use Enm\TransformerBundle\ConfigurationStructure\OptionStructures\IntegerOptions;
use Enm\TransformerBundle\ConfigurationStructure\OptionStructures\ObjectOptions;
use Enm\TransformerBundle\ConfigurationStructure\OptionStructures\RequiredIfStructure;
use Enm\TransformerBundle\ConfigurationStructure\OptionStructures\StringOptions;

class ConfigurationOptions
{

  protected $required = false;

  /**
   * @var \Enm\TransformerBundle\ConfigurationStructure\OptionStructures\RequiredIfStructure
   */
  protected $requiredIfNotAvailable;

  /**
   * @var \Enm\TransformerBundle\ConfigurationStructure\OptionStructures\RequiredIfStructure
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
   * @var \Enm\TransformerBundle\ConfigurationStructure\OptionStructures\StringOptions
   */
  protected $stringOptions;

  /**
   * @var \Enm\TransformerBundle\ConfigurationStructure\OptionStructures\IntegerOptions
   */
  protected $integerOptions;

  /**
   * @var \Enm\TransformerBundle\ConfigurationStructure\OptionStructures\FloatOptions
   */
  protected $floatOptions;

  /**
   * @var \Enm\TransformerBundle\ConfigurationStructure\OptionStructures\BoolOptions
   */
  protected $boolOptions;

  /**
   * @var \Enm\TransformerBundle\ConfigurationStructure\OptionStructures\DateOptions
   */
  protected $dateOptions;

  /**
   * @var \Enm\TransformerBundle\ConfigurationStructure\OptionStructures\ObjectOptions
   */
  protected $objectOptions;

  /**
   * @var \Enm\TransformerBundle\ConfigurationStructure\OptionStructures\CollectionOptions
   */
  protected $collectionOptions;

  /**
   * @var \Enm\TransformerBundle\ConfigurationStructure\OptionStructures\ArrayOptions
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
   *
   * @return $this
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
   *
   * @return $this
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
   * @param bool $required
   *
   * @return $this
   */
  public function setRequired($required)
  {
    $this->required = boolval($required);

    return $this;
  }



  /**
   * @return bool
   */
  public function getRequired()
  {
    return $this->required;
  }



  /**
   * @param RequiredIfStructure $requiredIfAvailable
   *
   * @return $this
   */
  public function setRequiredIfAvailable(RequiredIfStructure $requiredIfAvailable)
  {
    $this->requiredIfAvailable = $requiredIfAvailable;

    return $this;
  }



  /**
   * @return RequiredIfStructure
   */
  public function getRequiredIfAvailable()
  {
    return $this->requiredIfAvailable;
  }



  /**
   * @param RequiredIfStructure $requiredIfNotAvailable
   *
   * @return $this
   */
  public function setRequiredIfNotAvailable(RequiredIfStructure $requiredIfNotAvailable)
  {
    $this->requiredIfNotAvailable = $requiredIfNotAvailable;

    return $this;
  }



  /**
   * @return RequiredIfStructure
   */
  public function getRequiredIfNotAvailable()
  {
    return $this->requiredIfNotAvailable;
  }



  /**
   * @param ArrayOptions $arrayOptions
   *
   * @return $this
   */
  public function setArrayOptions(ArrayOptions $arrayOptions)
  {
    $this->arrayOptions = $arrayOptions;

    return $this;
  }



  /**
   * @return ArrayOptions
   */
  public function getArrayOptions()
  {
    return $this->arrayOptions;
  }



  /**
   * @param BoolOptions $boolOptions
   *
   * @return $this
   */
  public function setBoolOptions(BoolOptions $boolOptions)
  {
    $this->boolOptions = $boolOptions;

    return $this;
  }



  /**
   * @return BoolOptions
   */
  public function getBoolOptions()
  {
    return $this->boolOptions;
  }



  /**
   * @param CollectionOptions $collectionOptions
   *
   * @return $this
   */
  public function setCollectionOptions(CollectionOptions $collectionOptions)
  {
    $this->collectionOptions = $collectionOptions;

    return $this;
  }



  /**
   * @return CollectionOptions
   */
  public function getCollectionOptions()
  {
    return $this->collectionOptions;
  }



  /**
   * @param DateOptions $dateOptions
   *
   * @return $this
   */
  public function setDateOptions(DateOptions $dateOptions)
  {
    $this->dateOptions = $dateOptions;

    return $this;
  }



  /**
   * @return DateOptions
   */
  public function getDateOptions()
  {
    return $this->dateOptions;
  }



  /**
   * @param FloatOptions $floatOptions
   *
   * @return $this
   */
  public function setFloatOptions(FloatOptions $floatOptions)
  {
    $this->floatOptions = $floatOptions;

    return $this;
  }



  /**
   * @return FloatOptions
   */
  public function getFloatOptions()
  {
    return $this->floatOptions;
  }



  /**
   * @param IntegerOptions $integerOptions
   *
   * @return $this
   */
  public function setIntegerOptions(IntegerOptions $integerOptions)
  {
    $this->integerOptions = $integerOptions;

    return $this;
  }



  /**
   * @return IntegerOptions
   */
  public function getIntegerOptions()
  {
    return $this->integerOptions;
  }



  /**
   * @param IndividualOptions $individualOptions
   *
   * @return $this
   */
  public function setIndividualOptions(IndividualOptions $individualOptions)
  {
    $this->individualOptions = $individualOptions;

    return $this;
  }



  /**
   * @return IndividualOptions
   */
  public function getIndividualOptions()
  {
    return $this->individualOptions;
  }



  /**
   * @param ObjectOptions $objectOptions
   *
   * @return $this
   */
  public function setObjectOptions(ObjectOptions $objectOptions)
  {
    $this->objectOptions = $objectOptions;

    return $this;
  }



  /**
   * @return ObjectOptions
   */
  public function getObjectOptions()
  {
    return $this->objectOptions;
  }



  /**
   * @param StringOptions $stringOptions
   *
   * @return $this
   */
  public function setStringOptions(StringOptions $stringOptions)
  {
    $this->stringOptions = $stringOptions;

    return $this;
  }



  /**
   * @return StringOptions
   */
  public function getStringOptions()
  {
    return $this->stringOptions;
  }
}
