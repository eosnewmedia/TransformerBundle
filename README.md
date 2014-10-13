# Enm / TransformerBundle
(https://insight.sensiolabs.com/projects/c1376c5f-eb5a-4c4d-95de-0071384bc33a/big.png)](https://insight.sensiolabs.com/projects/c1376c5f-eb5a-4c4d-95de-0071384bc33a)
## What can the bundle be used for?
The Bundle can be used for validating an array, an object or a json-string and get an array, an object or a json-string back with the validated values.

This will be useful for example with a REST-API. You could give a JSON string in and out and the transformer can secure that all values are valid.

## Basic Usage
The Transformer is reachable through a symfony service.
    
    enm.transformer
    
For the auto-complete the "TransformerInterface" can be used for typification:

    __construct(\Enm\TransformerBundle\Interfaces\TransformerInterface $transformer){
      $this->transformer = $transformer;
    }

The Interface will offer different Methods to you:

  - transform($returnClass, $config, $values, $local_config = null, $result_type = 'object') : array | object | string

  - reverseTransform($object, $config, $local_config = null, $result_type = 'object') : array | \stdClass | string

  - getEmptyObjectStructureFromConfig($config, $result_type = 'object') : array | object | string

  - convert($value, $to) : array | object | string

### Method: transform()
This method will validate the given values and build the result from the correct values (including property renaming, value normalizing and value converting if configured).

Parameters:

  - returnClass : object or class name (with namespace) of the object you will get back from the transformer
  
  - config : configuration array for validating and building the output
  
  - values : object or array or json string with values to validate
  
  - local_config : configuration name from the global configuration or configuration array or configuration object or configuration json with transformer settings
  
  - result_type : string with the type which should be returned by this method ("array" or "object" or "json")
  
Result:

  - object or array or json
  
### Method: reverseTransform()
This method returns the values of the object in the original structure (original naming before the transformation), but in the chosen format (object or array or json)

Parameters:

  - object : object or array or json
  
  - config : configuration array
  
  - local_config : configuration name from the global configuration or configuration array or configuration object or configuration json with transformer settings
  
  - result_type : string with the type which should be returned by this method ("array" or "object" or "json")
  
Result:

  - object or array or json

### Method: getEmptyObjectStructureFromConfig()
This Method will build a structure of the needed object from your configuration array.

Parameters:

  - config : configuration array
  
  - result_type : string with the type which should be returned by this method ("array" or "object" or "json")

Result:

  - object or array or json

### Method: convert()
This value will convert an object, an array or a json string to a standard object, an array or a json string.

Parameters:

  - value : array or object or json
  
  - to : string with the type which should be returned by this method ("array" or "object" or "json")

Result:

  - object or array or json

## Validation
For validation you can use a configuration array, which have to be given to the transform or reverseTransform method.

The config array has some parameters for all types of validation and some special type validation parameters.

Some of the parameters are required, some are optional.

The base configuration looks like:

    $config = array(
      'key' => array(
        'type' => '', // required
        'renameTo' => '', // optional
        'children' => array(), // only in use with types "object", "collection" and (if you want it) "individual"
        'options' => array() // optional
      )
    )

#### type
This option is the only always required option.

With this option you have to give the data type for validation to the transformer.

Possible Values:

  - string
  - integer
  - float
  - bool
  - array
  - object
  - collection // array of equal objects
  - date
  - individual // can be any type. validation will not be performed by default, but you can add own validation (see later)

#### renameTo
This option is optional, but if it is used, it needs a string given.

This option can be used for renaming the key to a different property name.

#### children
This option is only possible if type is object, collection or individual.
If type is object or collection, this option is required.

This option needs a complete configuration array for child elements (see object and collection validation)

### Default Options
possible for all types and always optional:

    $config['key']['options'] => array(
      'required' => true,
      'requiredIfAvailable' => array(
        'and' => array(), 
        'or' => array()
      ),
      'requiredIfNotAvailable' => array(
        'and' => array(),
        'or' => array()
      ),
      'forbiddenIfAvailable' => array(),
      'forbiddenIfNotAvailable' => array()
    )

#### required
This option needs true or false. Default value is false.

If set to true, this option requires the transformer to validate that the current value is not NULL

#### requiredIfAvailable
This option have to be an array, which requires a sub configuration if set.

Generally this option tells the transformer to set a current value required.

Sub Configuration:

    array(
      'and' => array(),
      'or' => array()
    )

  - and: all keys given here have to be available to set the current value required
  - or: one of the keys given here has to be available to set the current value required
 
#### requiredIfNotAvailable
This option have to be an array, which requires a sub configuration if set.

Generally this option tells the transformer to set a current value required.

Sub Configuration:

    array(
      'and' => array(),
      'or' => array()
    )

  - and: all keys given here must not be available to set the current value required
  - or: one of the keys given here must not be available to set the current value required
 
#### forbiddenIfAvailable
This option needs an array of config key names.

It requires the transformer to validate that the current key will not have a value or a value equal to NULL if one of the given keys has a value.

#### forbiddenIfNotAvailable
This option needs an array of config key names.

It requires the transformer to validate that the current key will not have a value or a value equal to NULL if one of the given keys does not have a value.

### String Validation
Base configuration:

    $config = array(
      'key' => array(
        'type' => 'string',
      )
    )
 
Possible options, all optional:

    $config['key']['options'] = array(
      'stringValidation' => '' // email|url|ip
    )
    
