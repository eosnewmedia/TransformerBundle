# Enm / TransformerBundle
## What can the bundle be used for?
The Bundle can be used for validating an array, an object or a json-string and get an array, an object or a json-string back with the validated values.

This will be useful for example with a REST-API. You could give a JSON string in and out and the transformer can secure that all values are valid.

## Basic Usage
The Transformer is reachable through a symfony service.
    
    enm.transformer.service
    
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
