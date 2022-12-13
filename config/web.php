<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'name'=>'MedicalRecord',
    'language'=>'ru-RU',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '638-19',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'multipart/form-data' => 'yii\web\MultipartFormDataParser',
 ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        /*Добавьте этот компонент для формирования ответа
здесь формируется ответ если пользователь неавторизован
см. Методические указания стр. 21*/
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function ($event) {
                $response = $event->sender;
                if ($response->data !== null && $response->statusCode==401) {
                    $response->data = ['error'=>['code'=>403, 'message'=>'Unauthorized']];
                    header('Access-Control-Allow-Origin: *');
                    header('Content-Type: application/json');
                }
            },
        ],

        'user' => [
            'identityClass' => 'app\models\Patient', //тут возможно Patient (user|User)
            'enableSession' => false
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,

            'rules' => [
                ['class' => 'yii\rest\UrlRule', 'controller' => 'clinic'],
                //'POST product' => 'clinic/create'
                //['class' => 'yii\rest\UrlRule', 'controller' => 'patient'],
                'POST register' => 'patient/create',
                'POST login' => 'patient/login',
                'GET patient' => 'patient/account',

                ['class' => 'yii\rest\UrlRule', 'controller' => 'doctor'],
                //'POST doctor' => 'doctor/create'

                'GET doctor' => 'doctor/doctor',//+
                'POST doctor' => 'doctor/doctor',//+
                'GET alldoctor' => 'doctor/alldoctor',//+
                'POST doctor/add' => 'doctor/add',//+
                'PATCH doctor/red/<id_doctor>' => 'doctor/red',//+
                'DELETE doctor/del/<id_doctor>' => 'doctor/del',//+
                ['class' => 'yii\rest\UrlRule', 'controller' => 'appointment']
            ]
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
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '*'],
    ];
}

return $config;
