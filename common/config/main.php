<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
    'modules' => [
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => YII_ENV_DEV ? ['127.0.0.1', '::1', '192.168.0.*', '*'] : [],
            'generators' => [ //here
                'crud' => [ // generator name
                    'class' => 'yii\gii\generators\crud\Generator', // generator class
                    'templates' => [ //setting for out templates
                        'ferma' => '@common/gii/crud/ferma', // template name => path to template
                    ]
                ]
            ],
        ],
    ],
];
