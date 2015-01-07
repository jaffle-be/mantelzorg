<?php

return [

    /**
     * The name of the index
     * Your app name might be a sane default value.
     */
    'index'    => 'app',

    /**
     * All the hosts that are in the cluster.
     */
    'hosts'    => [
        'localhost:9200',
    ],

    /**
     * When adding a new type, add it here. This will allow you to easily rebuild the indexes using the build command.
     * Don't forget to add the new type as an argument when you execute the build command. You do not always want
     * to rebuild all your stored indexes.
     *
     * the key equals your database table. the value is either the class or a complex array :-)
     */
    'types'    => [

        'mantelzorgers'      => [
            'class' => 'Mantelzorger\Mantelzorger',
            'with'  => [
                'oudere'       => [
                    'class' => 'Mantelzorger\Oudere',
                    'key'   => 'oudere_id'
                ],
                'hulpverlener' => [
                    'class' => 'User',
                    'key'   => 'hulpverlener_id'
                ]
            ]
        ],

        'beta_registrations' => [
            'class' => 'Beta\Registration',
            'with'  => []
        ],

        'users'              => [
            'class' => 'User',
            'with'  => [
                'organisation'          => [
                    'class' => 'Organisation\Organisation',
                    'key'   => 'organisation_id'
                ],
                'organisation_location' => [
                    'class' => 'Organisation\Location',
                    'key'   => 'organisation_location_id',
                ],
            ]
        ],

        'surveys'            => [
            'class' => 'Questionnaire\Session',
            'with'  => [
                'mantelzorger'         => [
                    'class' => 'Mantelzorger\Mantelzorger',
                    'key'   => 'mantelzorger_id',
                ],
                'oudere'               => [
                    'class' => 'Mantelzorger\Oudere',
                    'key'   => 'oudere_id',
                ],
                'user'                 => [
                    'class' => 'User',
                    'key'   => 'user_id'
                ],
//                'questionnaire'        => [
//                    'class' => 'Questionnaire\Questionnaire',
//                    'key'   => 'questionnaire_id',
//                ],
//                'questionnaire.panels' => [
//                    'class' => 'Questionnaire\Panel',
//                    'key'   => 'panel_id'
//                ]
            ]
        ]
    ],

    'settings' => [
        'index' => [
            'analysis' => [
                'analyzer'  => [
                    'custom_analyzer'        => [
                        'type'      => 'custom',
                        'tokenizer' => 'nGram',
                        'filter'    => ['standard', 'asciifolding', 'lowercase', 'snowball', 'elision']
                    ],

                    'custom_search_analyzer' => [
                        'type'      => 'custom',
                        'tokenizer' => 'standard',
                        'filter'    => ['standard', 'asciifolding', 'lowercase', 'snowball', 'elision']
                    ],

                    'code'                   => [
                        'tokenizer' => 'pattern',
                        'filter'    => ['standard', 'lowercase', 'code']
                    ],

                    'email'                  => [
                        'tokenizer' => 'uax_url_email',
                        'filter'    => ['email', 'lowercase', 'unique']
                    ]
                ],

                'tokenizer' => [
                    'nGram' => [
                        'type'     => 'nGram',
                        'min_gram' => '2',
                        'max_gram' => 20
                    ],
                ],

                'filter'    => [
                    'snowball' => [
                        'type'     => 'snowball',
                        'language' => 'dutch',
                    ],

                    'code'     => [
                        'type'              => 'pattern_capture',
                        'preserve_original' => 1,
                        'patterns'          => [
                            "(\\p{Ll}+|\\p{Lu}\\p{Ll}+|\\p{Lu}+)",
                            "(\\d+)"
                        ]
                    ],

                    'email'    => [
                        'type'              => 'pattern_capture',
                        'preserve_original' => 1,
                        'patterns'          => [
                            "(\\w+)",
                            "(\\p{L}+)",
                            "(\\d+)",
                            "@(.+)"
                        ]
                    ]
                ]
            ]

        ],
    ]

];