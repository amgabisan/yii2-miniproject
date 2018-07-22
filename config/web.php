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
                  'google' => [
                      'class' => 'yii\authclient\clients\Google',
                      'clientId' => '415176864674-ao4rc23gldv82ro0d8eaeoo0vul5m1ip.apps.googleusercontent.com',
                      'clientSecret' => 'lO2nlJW9Tbos1jLGKnlWIpIS',
                      'returnUrl'=>'http://amg.com/site/auth?authclient=google',
                  ],
                  'github' => [
                      'class' => 'yii\authclient\clients\GitHub',
                      'clientId' => 'ad9a79fd37437a47ce0a',
                      'clientSecret' => '9eec4291fbce4db3182cdf6b5aa13ab84266bf7b',
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
                /* Account Management */
                ''
                => '/site/login',
                '/'
                => '/site/login',
                '/<controller:site>/<action:(confirm|resetpassword)>/<auth:.+>'
                => '/<controller>/<action>',
                '/dashboard'
                => '/survey/manage/index',
                /* Survey Management */
                '/<module:survey>/<action:create>'
                => '/<module>/manage/<action>',
                '/<module:survey>/<action:(edit|delete)>/<id:\d+>'
                => '/<module>/manage/<action>',
                '/<id:\d+>'
                => '/survey/manage/answer',
                '/<action:thanks>'
                => '/survey/manage/<action>'
            ],
        ],
    ],
    'modules' => [
        'survey' => [
            'class' => 'app\modules\survey\module',
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
