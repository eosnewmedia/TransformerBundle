<?php


namespace Enm\TransformerBundle\Resources\TestClass;

class AddressTestClass
{

  /**
   * @var string
   */
  protected $street;

  /**
   * @var string
   */
  protected $plz;

  /**
   * @var string
   */
  protected $place;

  /**
   * @var CountryEnum
   */
  protected $country;



  /**
   * @param \Enm\TransformerBundle\Resources\TestClass\CountryEnum $country
   */
  public function setCountry($country)
  {
    $this->country = $country;

    return $this;
  }



  /**
   * @return \Enm\TransformerBundle\Resources\TestClass\CountryEnum
   */
  public function getCountry()
  {
    return $this->country;
  }



  /**
   * @param string $place
   */
  public function setPlace($place)
  {
    $this->place = $place;

    return $this;
  }



  /**
   * @return string
   */
  public function getPlace()
  {
    return $this->place;
  }



  /**
   * @param string $plz
   */
  public function setPlz($plz)
  {
    $this->plz = $plz;

    return $this;
  }



  /**
   * @return string
   */
  public function getPlz()
  {
    return $this->plz;
  }



  /**
   * @param string $street
   */
  public function setStreet($street)
  {
    $this->street = $street;

    return $this;
  }



  /**
   * @return string
   */
  public function getStreet()
  {
    return $this->street;
  }
}
