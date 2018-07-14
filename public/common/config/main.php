<?php

use yii\web\Response;

return [
    'name' => 'Contacts',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
//        'cache' => [
//            'class' => 'yii\caching\FileCache',
//        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
            'fileTransportPath' => '@common/runtime/mail',
        ],
    ],
    'bootstrap' => [
        [
            'class' => 'yii\filters\ContentNegotiator',
            'formats' => [
//                'text/html' => Response::FORMAT_HTML,
//                'application/json' => Response::FORMAT_JSON,
//                'application/xml' => Response::FORMAT_XML,
            ],
        ],
    ],
];
