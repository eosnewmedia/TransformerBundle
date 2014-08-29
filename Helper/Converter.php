<?php


namespace ENM\TransformerBundle\Helper;

use ENM\TransformerBundle\ConfigurationStructure\ConversionEnum;
use ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException;

class Converter
{

  /**
   * @param mixed $value
   *
   * @return array
   * @throws \ENM\TransformerBundle\Exceptions\TransformerException
   */
  protected function toArray($value)
  {
    switch (gettype($value))
    {
      case ConversionEnum::ARRAY_CONVERSION:
        return $value;
      case ConversionEnum::OBJECT_CONVERSION:
        return $this->objectToArray($value);
      case ConversionEnum::STRING_CONVERSION:
        return $this->jsonToArray($value);
    }
    throw new InvalidTransformerParameterException(sprintf(
      'Value of type %s can not be converted to array by this method.',
      gettype($value)
    ));
  }



  protected function toObject($value)
  {
    switch (gettype($value))
    {
      case ConversionEnum::ARRAY_CONVERSION:
        return json_decode(json_encode($value));
//        return $this->arrayToObject($value);
      case ConversionEnum::OBJECT_CONVERSION:
//        return $this->arrayToObject($value);
        return $value;
      case ConversionEnum::STRING_CONVERSION:
        return json_decode(json_encode($this->jsonToArray($value)));
    }
    throw new InvalidTransformerParameterException(sprintf(
      'Value of type %s can not be converted to object by this method.',
      gettype($value)
    ));
  }



  protected function toJson($value)
  {
    switch (gettype($value))
    {
      case ConversionEnum::ARRAY_CONVERSION:
      case ConversionEnum::OBJECT_CONVERSION:
        return json_encode($this->toObject($value));
      case ConversionEnum::STRING_CONVERSION:
        return json_encode($value);
    }
    throw new InvalidTransformerParameterException(sprintf(
      'Value of type %s can not be converted to JSON by this method.',
      gettype($value)
    ));
  }



  /**
   * Converts a JSON-String to an Array
   *
   * @param string $value
   *
   * @return array
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  protected function jsonToArray($value)
  {
    try
    {
      return $this->objectToArray(json_decode($value));
    }
    catch (\Exception $e)
    {
      throw new InvalidTransformerParameterException("The given Value isn't a valid JSON-String.");
    }
  }



  //  /**
  //   * @param $input
  //   *
  //   * @return \stdClass
  //   */
  //  protected function arrayToObject($input)
  //  {
  //    if (!$input instanceof \stdClass)
  //    {
  //      $array = $this->objectToArray($input);
  //
  //      $return = new \stdClass();
  //      foreach ($array as $key => $value)
  //      {
  //        if (is_array($value) || is_object($value))
  //        {
  //          $value = $this->arrayToObject($array);
  //        }
  //        $return->$key = $value;
  //      }
  //
  //      return $return;
  //    }
  //
  //    return $input;
  //  }

  /**
   * Converts an Object to an Array
   *
   * @param object|array $input
   *
   * @return array
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  protected function objectToArray($input)
  {
    if (!in_array(gettype($input), array(ConversionEnum::OBJECT_CONVERSION, ConversionEnum::ARRAY_CONVERSION)))
    {
      throw new InvalidTransformerParameterException(sprintf(
        "Value of type %s can't be converted by this method!",
        gettype($input)
      ));
    }
    // Rückgabe Array erstellen
    $final = array();
    // Object in Array umwandeln
    $array = (array) $input;

    foreach ($array as $key => $value)
    {
      // Protected und Private Properties des Objektes im Array erreichbar machen
      if (is_object($input))
      {
        // PHP Benennungen vom Konvertieren Rückgängigmachen
        $key = str_replace("\0*\0", '', $key);
        $key = str_replace("\0" . get_class($input) . "\0", '', $key);

        // Die Reflection-Klasse wird an dieser Stelle benötigt, da PHP Integer-Werte aus dem Objekt nicht in das Array übernimmts
        $reflectionClass = new \ReflectionClass(get_class($input));
        if ($reflectionClass->hasProperty($key))
        {
          $property = $reflectionClass->getProperty($key);
          $property->setAccessible(true);
          $value = $property->getValue($input);
        }
      }
      // Tiefer verschachtelt?
      if (is_object($value) || is_array($value))
      {
        $value = $this->objectToArray($value);
      }
      // Wert ins Array setzen
      $final[$key] = $value;
    }

    return $final;
  }



  /**
   * @param mixed  $value
   * @param string $result_type
   *
   * @return array|string|object
   * @throws \ENM\TransformerBundle\Exceptions\InvalidTransformerParameterException
   */
  public function convertTo($value, $result_type)
  {
    switch (strtolower($result_type))
    {
      case ConversionEnum::ARRAY_CONVERSION:
        return $this->toArray($value);
      case ConversionEnum::STRING_CONVERSION:
      case ConversionEnum::JSON_CONVERSION:
        return $this->toJson($value);
      case ConversionEnum::OBJECT_CONVERSION:
        return $this->toObject($value);
      default:
        throw new InvalidTransformerParameterException(sprintf(
          "The given Value can't be converted to %s by this method!",
          gettype($result_type)
        ));
    }
  }
} 