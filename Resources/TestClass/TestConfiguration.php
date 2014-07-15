<?php


namespace ENM\TransformerBundle\Resources\TestClass;

class TestConfiguration
{

  public static function getConfig()
  {
    return array(
      'username' => [
        'complex' => false,
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
        'complex'  => true,
        'children' => [
          'street'  => [
            'complex' => false,
            'type'    => 'string',
            'options' => [
              'required' => true
            ]
          ],
          'plz'     => [
            'complex' => false,
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
            'complex' => false,
            'type'    => 'string',
            'options' => [
              'required' => true
            ]
          ],
          'country' => [
            // 'complex' => false,
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
        'complex' => false,
        'type'    => 'date',
        'options' => [
          'date'              => [
            'format' => 'Y-m-d',
            'convertToObject' => true
          ],
        ]
      ],
      'hobbys'   => [
        'complex'  => true,
        'children' => [
          'dynamic' => [
            'name'       => [
              'complex' => false,
              'type'    => 'string'
            ],
            'years'      => [
              'complex' => false,
              'type'    => 'float',
              'options' => [
                'min' => 0
              ]
            ],
            'daysInWeek' => [
              'complex' => false,
              'type'    => 'integer',
              'options' => [
                'min' => 0,
                'max' => 7
              ]
            ]
          ]
        ],
        'type'     => 'collection',
        'options'  => [
          'returnClass' => 'ENM\TransformerBundle\Resources\TestClass\HobbyTestClass'
        ]
      ]
    );
  }
} 