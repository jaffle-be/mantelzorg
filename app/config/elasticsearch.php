<?php

return [

    /**
     * the path to the directory containing all json query views.
     */
    'path'     => realpath(__DIR__ . '/../searches'),

    /**
     * The name of the index
     * Your app name might be a sane default value.
     */
    'index'    => 'app',

    /**
     * All the hosts that are in the cluster.
     */
    'hosts'    => [
        'local.mantelzorg:9200',
    ],

    /**
     * When adding a new type, add it here. This will allow you to easily rebuild the indexes using the build command.
     * Don't forget to add the new type as an argument when you execute the build command. You do not always want
     * to rebuild all your stored indexes.
     *
     * the key equals your database table. the value is either the class or a complex array :-)
     */
    'types'    => [

        'mantelzorgers'      => 'Mantelzorger\Mantelzorger',

        'beta_registrations' => [
            'class' => 'Beta\Registration',
            'with'  => []
        ],

        'users'              => [
            'class' => 'User',
            'with'  => [
                'organisation',
                'organisation_location',
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