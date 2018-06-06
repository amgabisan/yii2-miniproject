<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'IyXbtFTLzUaSnq4ZnZzj-jgX1Oys7LLY',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\Account',
            //'enableAutoLogin' => true,
        ],
         'authClientCollection' => [
              'class' => 'yii\authclient\Collection',
              'clients' => [
                'facebook' => [
                      'class' => 'yii\authclient\clients\Facebook',
                      'clientId' => '2122751807937692',
                      'clientSecret' => 'aa1bb25f92c6c4af6aec4346683e6311',
                      'returnUrl'=>'http://amg.com/site/auth?authclient=facebook',
                  ],
                  'google' => [
                      'class' => 'yii\authclient\clients\Google',
                      'clientId' => '1074815305288-2ckf1tjdut4j58v1ikgjmpi9kb95lmgn.apps.googleusercontent.com',
                      'clientSecret' => 'oFn6x7Qf_ALdEloDiv4x65EJ',
                      'returnUrl'=>'http://amg.com/site/auth?authclient=google',
                  ],
                  'github' => [
                      'class' => 'yii\authclient\clients\GitHub',
                      'clientId' => '637c73f3a8eeff19cc2b',
                      'clientSecret' => 'e75b08107467b426753c156734ac33e3f8876c64',
                      'returnUrl'=>'http://amg.com/site/auth?authclient=github',
                  ],
                ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'Y/MM/dd',
            'datetimeFormat' => 'Y/MM/dd HH:mm:ss',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            // 'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                ''
                => '/site/login',
                '/'
                => '/site/login',
                '/<controller:site>/<action:(confirm|resetpassword)>/<auth:.+>'
                => '/<controller>/<action>'
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['*'],
    ];
}

return $config;
