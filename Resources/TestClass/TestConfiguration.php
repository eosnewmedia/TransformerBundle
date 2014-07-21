<?php


namespace ENM\TransformerBundle\Resources\TestClass;

class TestConfiguration
{

  public static function getConfig()
  {
    return array(
      'username' => [
        'type'    => 'string',
        'options' => [
          'required' => true,
          'length'   => [
            'min' => 3,
            'max' => 50
          ]
        ]
      ],
      'address'  => [
        'type'     => 'object',
        'children' => [
          'street'  => [
            'type'    => 'string',
            'options' => [
              'required' => true
            ]
          ],
          'plz'     => [
            'type'    => 'string',
            'options' => [
              'required' => true,
              'length'   => [
                'min' => 4,
                'max' => 10
              ]
            ]
          ],
          'place'   => [
            'type'    => 'string',
            'options' => [
              'required' => true
            ]
          ],
          'country' => [
            'type'    => 'string',
            'options' => [
              'required' => true,
              'expected' => CountryEnum::toArray()
            ]
          ],
        ],
        'options'  => [
          'required'    => true,
          'returnClass' => 'ENM\TransformerBundle\Resources\TestClass\AddressTestClass'
        ]
      ],
      'birthday' => [
        'type'    => 'date',
        'options' => [
          'date' => [
            'format'          => 'Y-m-d',
            'convertToObject' => true
          ],
        ]
      ],
      'hobbys'   => [
        'type'     => 'collection',
        'children' => [
          'dynamic' => [
            'name'       => [
              'type' => 'string'
            ],
            'years'      => [
              'type'    => 'float',
              'options' => [
                'min' => 0
              ]
            ],
            'daysInWeek' => [
              'type'    => 'integer',
              'options' => [
                'min' => 0,
                'max' => 7
              ]
            ]
          ]
        ],
        'options'  => [
          'returnClass' => 'ENM\TransformerBundle\Resources\TestClass\HobbyTestClass'
        ]
      ]
    );
  }
} 