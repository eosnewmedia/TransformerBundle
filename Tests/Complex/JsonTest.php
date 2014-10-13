<?php


namespace Enm\TransformerBundle\Tests\Complex;

use Enm\TransformerBundle\Tests\BaseTest;

class JsonTest extends BaseTest
{

  public function testJson()
  {
    $transformer = $this->getTransformer();

    $json = '{"initialInput":{"geoRestriction":{"circle":{"center":{"longitude":9.179940819740295,"latitude":48.78247982110398},"radius":539}}},"Restrictions":{"Type":["stop","poi"]}}';

    $json_config = [
      'InitialInput' => [
        'type'     => 'object',
        'children' => [
          'LocationName'   => [
            'type' => 'string',
          ],
          'GeoPosition'    => [
            'type'     => 'object',
            'children' => [
              'Longitude' => [
                'type'    => 'float',
                'options' => [
                  'required' => true,
                ],
              ],
              'Latitude'  => [
                'type'    => 'float',
                'options' => [
                  'required' => true,
                ],
              ],
              'Altitude'  => [
                'type' => 'float',
              ],
            ],
            'options'  => [
              'returnClass' => '\stdClass',
            ],
          ],
          'GeoRestriction' => [
            'type'     => 'object',
            'children' => [
              'Circle'    => [
                'type'     => 'object',
                'children' => [
                  'Center' => [
                    'type'     => 'object',
                    'children' => [
                      'Longitude' => [
                        'type'    => 'float',
                        'options' => [
                          'required' => true,
                        ],
                      ],
                      'Latitude'  => [
                        'type'    => 'float',
                        'options' => [
                          'required' => true,
                        ],
                      ],
                      'Altitude'  => [
                        'type' => 'float',
                      ],
                    ],
                    'options'  => [
                      'returnClass' => '\stdClass',
                    ],
                  ],
                  'Radius' => [
                    'type'    => 'integer',
                    'options' => [
                      'min' => 0,
                    ],
                  ],
                ],
                'options'  => [
                  'returnClass' => '\stdClass',
                ],
              ],
              'Rectangle' => [
                'type'     => 'object',
                'children' => [
                  'UpperLeft'  => [
                    'type'     => 'object',
                    'children' => [
                      'Longitude' => [
                        'type'    => 'float',
                        'options' => [
                          'required' => true,
                        ],
                      ],
                      'Latitude'  => [
                        'type'    => 'float',
                        'options' => [
                          'required' => true,
                        ],
                      ],
                      'Altitude'  => [
                        'type' => 'float',
                      ],
                    ],
                    'options'  => [
                      'returnClass' => '\stdClass',
                    ],
                  ],
                  'LowerRight' => [
                    'type'     => 'object',
                    'children' => [
                      'Longitude' => [
                        'type'    => 'float',
                        'options' => [
                          'required' => true,
                        ],
                      ],
                      'Latitude'  => [
                        'type'    => 'float',
                        'options' => [
                          'required' => true,
                        ],
                      ],
                      'Altitude'  => [
                        'type' => 'float',
                      ],
                    ],
                    'options'  => [
                      'returnClass' => '\stdClass',
                    ],
                  ],
                ],
                'options'  => [
                  'returnClass' => '\stdClass',
                ],
              ],
            ],
            'options'  => [
              'returnClass' => '\stdClass',
            ],
          ],
        ],
        'options'  => [
          'returnClass'            => '\stdClass',
          'requiredIfNotAvailable' => [
            'or' => array('LocationRef')
          ]
        ]
      ],
      'LocationRef'  => [
        'type'     => 'individual',
        'children' => [],
        'options'  => [
          'returnClass'            => '\stdClass',
          'requiredIfNotAvailable' => [
            'or' => array('InitialInput')
          ]
        ]
      ],
      'Restrictions' => [
        'type'     => 'individual',
        'children' => [],
        'options'  => [
          'returnClass' => '\stdClass'
        ]
      ]
    ];

    try
    {
      $transformer->transform(new \stdClass(), $json_config, $json);
      $this->assertTrue(true);
    }
    catch (\Exception $e)
    {
      $this->fail($e->getMessage());
    }
  }
} 