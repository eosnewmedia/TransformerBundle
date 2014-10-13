<?php


namespace Enm\TransformerBundle\Resources\TestClass;

class UserTestClass
{

  /**
   * @var string
   */
  protected $username;

  /**
   * @var AddressTestClass
   */
  protected $address;

  /**
   * @var \DateTime
   */
  protected $birthday;

  /**
   * @var array
   */
  protected $hobbys;



  /**
   * @param \Enm\TransformerBundle\Resources\TestClass\AddressTestClass $address
   */
  public function setAddress($address)
  {
    $this->address = $address;

    return $this;
  }



  /**
   * @return \Enm\TransformerBundle\Resources\TestClass\AddressTestClass
   */
  public function getAddress()
  {
    return $this->address;
  }



  /**
   * @param \DateTime $birthday
   */
  public function setBirthday($birthday)
  {
    $this->birthday = $birthday;

    return $this;
  }



  /**
   * @return \DateTime
   */
  public function getBirthday()
  {
    return $this->birthday;
  }



  /**
   * @param array $hobbys
   */
  public function setHobbys($hobbys)
  {
    $this->hobbys = $hobbys;

    return $this;
  }



  /**
   * @return array
   */
  public function getHobbys()
  {
    return $this->hobbys;
  }



  /**
   * @param string $username
   */
  public function setUsername($username)
  {
    $this->username = $username;

    return $this;
  }



  /**
   * @return string
   */
  public function getUsername()
  {
    return $this->username;
  }
}
