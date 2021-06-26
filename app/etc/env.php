<?php
return [
    'backend' => [
        'frontName' => 'bookmanager'
    ],
    'crypt' => [
        'key' => 'd020f75e9532904e59d42fb76cfb8d88'
    ],
    'db' => [
        'table_prefix' => '',
        'connection' => [
            'default' => [
                'host' => 'localhost',
                'dbname' => 'stpauls',
                'username' => 'root',
                'password' => '',
                'active' => '1'
            ]
        ]
    ],
    'resource' => [
        'default_setup' => [
            'connection' => 'default'
        ]
    ],
    'x-frame-options' => 'SAMEORIGIN',
    'MAGE_MODE' => 'developer',
    'session' => [
        'save' => 'files'
    ],
    'cache_types' => [
        'config' => 1,
        'layout' => 1,
        'block_html' => 1,
        'collections' => 1,
        'reflection' => 1,
        'db_ddl' => 1,
        'eav' => 1,
        'customer_notification' => 1,
        'config_integration' => 1,
        'config_integration_api' => 1,
        'full_page' => 1,
        'config_webservice' => 1,
        'translate' => 1,
        'compiled_config' => 1,
        'vertex' => 1
    ],
    'install' => [
        'date' => 'Fri, 11 Jan 2019 05:25:25 +0000'
    ],
    'system' => [
        'default' => [
            'dev' => [
                'debug' => [
                    'debug_logging' => '0'
                ]
            ]
        ]
    ],
    'downloadable_domains' => [
        'dev.stpaul.com'
    ]
];
