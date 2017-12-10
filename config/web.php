<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'sourceLanguage'=>'pt-BR',
    'language'=>'pt-BR',
    'timezone' => 'America/Sao_Paulo',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules' => [
        '_adm' => [
            'class' => 'app\admin\Module',

        ],
        
        ],
    'components' => [
        'assetManager' => [
        'bundles' => [
              'yii\web\JqueryAsset' => [
                 'jsOptions' => [ 'position' => \yii\web\View::POS_HEAD], //'async' => ''
                 'js'=>['jquery.min.js']
             ],
             // 'yii\web\YiiAsset'=>[
             //  // 'js'=>['min/yii.js'],
             //  //'jsOptions'=>['async' => ''],
             // ],
             // 'yii\widgets\ActiveFormAsset'=>[
             //  // 'js'=>['min/yii.activeForm.js'],
             //  //'jsOptions'=>['async' => ''],
             // ],
             // 'yii\validators\ValidationAsset'=>[
             //  // 'js'=>['min/yii.validation.js'],
             //  //'jsOptions'=>['async' => ''],
             // ],
         ],
        'appendTimestamp' => true,     
   ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'XwvhuSR9zQDRnQ7BCtwpd3p5Qzm937T2',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        /*
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        */
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
