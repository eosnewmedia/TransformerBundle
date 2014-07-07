# ENM\TransformerBundle

## What is it for?
This Bundle is used to transform an array, which is following a defined structure, to a needed object.


## How to use?
You can use this Transformer through the following service:

    enm.array.transformer.service

the method to call is

    transform(NEEDED_OBJECT, PARAM_STUCTURE_CONFIGURATION, PARAMS)

#### JSON
If you want JSON instead of the param array, please use:

    enm.json.transformer.service

and give the JSON string instead of the param array to the transform-method:

  transform(NEEDED_OBJECT, PARAM_STUCTURE_CONFIGURATION, JSON_STRING)

### NEEDED_OBJECT
has to be an instance of the object you want to get.
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

### PARAM_STUCTURE_CONFIGURATION
has to be an array which follows the defined structure of the TransformerConfiguration class
#### Example
Your class:

    class UserParamStructureConfiguration implements ParamStructureConfigurationInterface
    {
      public static function getConfig()
      {
       return array(
        'username' => [
          'complex' => false,
          'type' => 'string',
          'options' => [
            'required' => true
          ]
        ],
        'email' => [
          'complex' => false,
          'type' => 'string',
          'options' => [
            'required' => true
          ]
        ],
        'address' => [
          'complex' => true,
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
      }
    }

get Array:

    $config = UserParamStructureConfiguration::getConfig();

### PARAMS
has to be an array of the given values
#### Example
Your array:

    $params = array(
      'username' => 'Test User',
      'email' => 'test@user.de'
      'address' => array(
        'street' => 'Schanzenstraße 70',
        // other values
      ),
    );

### Example how it works together:

    $object = $this->container->get('enm.array.transformer.service')->transform($user, $config, $params);

### JSON_STRING
Please note, that the JSON string must contain key-value-pairs, exactly as the param array.
#### Example
Your JSON:

    $json = '{"username":"Test User","email":"test@user.de","address":{"street":"Schanzenstraße 70"}}';

#### How it works together:

    $object = $this->container->get('enm.json.transformer.service')->transform($user, $config, $json);

## The ParamStructureConfiguration
### Configuration Array
The following array structure is needed for each parameter:

      '{KEY}' => array( // Description under "Key"
        'complex' => (true|false),
        'children' => array(), // Only if complex is true, Description under "Child Objects"
        'methodClass' => '{METHOD_HOLDER_CLASS}' // Only if complex is true, Description under "Own Validation Methods"
        'method' => '{METHOD}' // Only if complex is true, Description under "Own Validation Methods"
        'type' => '(bool|integer|string|float|array)', // Only if complex is false
        'options' => array(
          'required' => (true|false),
          'min' => (int|float), // Only if type is 'integer' or 'float' and complex is false
          'max' => (int|float), // Only if type is 'integer' or 'float' and complex is false
          'expected' => array(), // Only if complex is false, Description under "Enumerations"
          'returnClass' => '{NAMESPACE\CLASS_NAME}' // Only if complex is true, Description under "Child Objects"
        )
      )

#### Key
The key for each Parameter must be exactly called the same as the property of the object you want to get.

#### Child Objects
If your object should contain sub-objects, you can set the 'complex'-key to TRUE and write a
configuration array for your sub-objects too.
This array you can set as the value of your 'children'-key.

If you are using a complex structure with children, you must define a return class.
The return class is the class of the object, which is build from the children elements.

    $address_configuration = array(
      // normal configuration array
    );


    $main_configuration = array(
      'user_extra_data' => array(
        'complex' => true,
        'children' => array(
          'address' => $address_configuration,
        ),
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


    namespace Own\Validation;

    class UserValidation
    {
      // Method holder class must not have an constructor which needs parameters

      /**
      * @param array $params | original param array
      *
      * @return value
      */
      public function yourMethod(array $params)
      {
        // do something
      }
    }


    #########


    $configuration = array(
      'username' => array(
        'complex' => true,
        'methodClass' => 'Own\Validation\UserValidation', // Class name, including namespace
        'method' => 'yourMethod', // Method to call
        'options' => array(
            // your needed options for the configuration of 'username'
        )
      )
    );


    $user = $this->container->get('enm.array.transformer.service')->transform(new User(), $configuration, $params);


#### Enumerations
If you want to allow only special values for a parameter, you can give an array with possible values to the 'expected' option of your configuration array.

    $configuration = array(
      'username' => array(
        'complex' => false,
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
        'complex' => false,
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


## Questions?
Write an email to "marien@eosnewmedia.de"!



©2014 by eos new media GmbH & Co. KG