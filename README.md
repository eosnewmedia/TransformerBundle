# ENM\TransformerBundle

## What is it for?
This Bundle is used to transform an array, an object or a json string, which is following a defined structure, to a needed object.

## Requirements
The TransformerBundle requires PHP in a Version 5.5 or higher

## How to use?
You can use this Transformer through the following service:

    enm.transformer.service

the method to call is

    transform(NEEDED_OBJECT, CONFIGURATION, VALUES)

you can validate that the transformer is an instance of "ENM\TransformerBundle\TransformerInterface"

### NEEDED_OBJECT
has to be an instance of the object or a string containing the class name of the object you want to get.
#### Example
Your class:

    class User
    {
      // @var string
      protected $username;

      // @var string
      protected $email;

      // Getters and Setters...
    }

get Instance:

    $user = new User();

### CONFIGURATION
has to be an array which follows the defined structure of the TransformerConfiguration class
#### Example
    $config = array(
                'username' => [
                  'type' => 'string',
                  'options' => [
                    'required' => true
                  ]
                ],
                'email' => [
                  'type' => 'string',
                  'options' => [
                    'required' => true
                  ]
                ],
                'address' => [
                  'children' => [
                    'street' => [
                      // configuration array
                    ],
                    // other properties
                  ],
                  'options' => [
                    'required' => true
                  ]
                ],
             );

### VALUES
has to be an array, an object or a json string of the given values
#### Example
Your array:

    $values = array(
      'username' => 'Test User',
      'email' => 'test@user.de'
      'address' => array(
        'street' => 'Schanzenstraße 70',
        // other values
      ),
    );

Or your JSON:

    $values = '{"username":"Test User","email":"test@user.de","address":{"street":"Schanzenstraße 70"}}';

### Example how it works together:

    $object = $this->container->get('enm.transformer.service')->transform($user, $config, $values);

## The Configuration
### Configuration Array
The following array structure is needed for each parameter:

      '{KEY}' => array( // Description under "Key"
        'type' => '(bool|integer|string|float|array|collection|date|object|method)',
        'renameTo' => (string), // The property name in the object, if it differs to the key
        'children' => array(), // If 'type' is 'object' or 'collection',  Description under "Child Objects" or "Collections"
        'options' => array(
          'required' => (true|false),
          'requiredIfAvailable' => array({KEY_1}, {KEY_2}), // Required, if one of the given keys has a value
          'requiredIfNotAvailable' => array({KEY_1}, {KEY_2}), // Required, if one of the given keys has not a value
          'regex' => (string), // Only if type is 'string', has to be a valid Regex-Pattern
          'assoc' => (true|false) // Only if type is 'array'. Tells the transformer, whether the array is associative or not
          'min' => (int|float), // Only if type is 'integer' or 'float'
          'max' => (int|float), // Only if type is 'integer' or 'float'
          'expected' => array(), // Description under "Enumerations",
          'defaultValue' => (array|object|string|integer|float), // The default, if no value is set
          'date' => array( // Only if type is 'date'
            'format' => (string|array), // Expected Date Format, Default: Y-m-d, can be an array of formats like array('Y-m-d', 'd.m.Y')
            'convertToObject' => (true|false), // True, if Date should converted to DateTime-Object, Default: false
            'convertToFormat' => (null|string) // If not null, it has to be a valid date format, which is returned, default Outputs-Format is the same as the Input-Format
          ),
          'length' => array( // Length validation, only if type is 'string'
            'min' => (int),
            'max' => (int)
          ),
          'methodClass' => (string) If 'type' is 'method', Description under "Own Validation Methods"
          'methodClassParameter' => (array) If 'type' is 'method', Description under "Own Validation Methods"
          'method' => (string) // If 'type' is 'method', Description under "Own Validation Methods"
          'returnClass' => '{NAMESPACE\CLASS_NAME}' // If 'type' is 'object' or 'collection', Description under "Child Objects" or "Collections"
        )
      )

#### Key
The key for each Parameter must be exactly called the same as the property of the object you want to get.

#### Child Objects
If your object should contain sub-objects, you can write a configuration array for your sub-objects too.
This array should be the value of your 'children'-key.

You also have to set the 'type'-key of your configuration to 'object'

If you are using a complex structure with children, you must define a return class.
The return class is the class of the object, which is build from the children elements.

    $address_configuration = array(
      // normal configuration array
    );


    $main_configuration = array(
      'user_extra_data' => array(
        'children' => array(
          'address' => $address_configuration,
        ),
        'type' => 'object',
        'options' => array(
          'returnClass' => 'Own\Address' // Needed class including namespace
          // your needed options for the main configuration of 'user_extra_data'
        )
      )
    );


    $user = $this->container->get('enm.array.transformer.service')->transform(new User(), $main_configuration, $params);

### Collections
If you want to get a Collection, you can write a configuration array for your expected object, that should be in your collection.
This array you can set as the value of your 'children'-key. It needs the array-key 'dynamic', because the number of identical classes isn't limited.

You also have to set the 'type'-key of your configuration to 'collection'

If you are using a complex structure with children, you must define a return class.
The return class is the class of the object, which is build from the children element.

    $address_configuration = array(
      // normal configuration array
    );


    $main_configuration = array(
      'user_addresses' => array(
        'children' => array(
        // multiple addresses for one user
          'dynamic' => $address_configuration,
        ),
        'type' => 'collection',
        'options' => array(
          'returnClass' => 'Own\Address' // Needed class including namespace
          // your needed options for the main configuration of 'user_extra_data'
        )
      )
    );


    $user = $this->container->get('enm.array.transformer.service')->transform(new User(), $main_configuration, $params);


#### Own Validation Methods
If one property of your needed object is to complex for our validation or if it needs special validation,
you can write your own validation method for it and give it to the configuration array.
The method must return the value of the parameter which is validated.

Your validation method must be in a class, which you have to give into the configuration array.

'type'-Key must to be set to 'method'

    namespace Own\Validation;

    class UserValidation
    {
      /**
      * @param mixed $value
      *
      * @return $value
      */
      public function yourMethod($value)
      {
        // do something
      }
    }


    #########


    $configuration = array(
      'username' => array(
        'type' => 'method',
        'options' => array(
          'methodClass' => 'Own\Validation\UserValidation', // Class name, including namespace
          'methodClassParameter' => array(), // Parameters for the Constructor of the Method-Class
          'method' => 'yourMethod', // Method to call
        )
      )
    );


    $user = $this->container->get('enm.array.transformer.service')->transform(new User(), $configuration, $params);


#### Enumerations
If you want to allow only special values for a parameter, you can give an array with possible values to the 'expected' option of your configuration array.

    $configuration = array(
      'username' => array(
        'type' => 'string',
        'options' => array(
          'expected' => array('test', 'testuser', 'user'),
            // your needed options for the configuration of 'username'
        )
      )
    );

An other possibility is to use an enumeration class which should extend 'ENM\Enumeration\BaseEnumeration'.

For each value you want to allow, you need a public static function which contains the value.

Values can be of the types 'string', 'integer', 'float' and 'bool'

    OwnEnumeration extends ENM\Enumeration\BaseEnumeration
    {
      const TEST = 'test';
      const TESTUSER = 'testuser';
      const USER = 'user';
    }


Using the enumeration class in the configuration:

    $configuration = array(
      'username' => array(
        'type' => 'string',
        'options' => array(
          'expected' => OwnEnumeration::toArray(), // call the toArray-Method of the enumeration class
            // your needed options for the configuration of 'username'
        )
      )
    );


## The Parameter Array
The parameter array you give to the transform method is a simple array of key-value-pairs.
The key must be always the same as the property of the needed object and the configuration key.

# reverseTransform
With this Method, you're in a position to transform back your object to a stdClass, JSON-String or array.

Simply call the Transformer-Service and then

    $transformer->reverseTransform($object, $config, $type)

I think, object and config are clearly...

The type have to be a string. Possible values are:

  - array
  - string
  - json (same result as string)
  - object

## Questions?
Write an email to "marien@eosnewmedia.de"!



©2014 by eos new media GmbH & Co. KG