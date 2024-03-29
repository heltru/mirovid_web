<?php

$config = [
    'id' => 'app',
    'language'=>'ru-RU',
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Module',
         //   'layout' => '@app/views/layouts/admin',
            'defaultRoute'=>'admin/block/block-utils/product-of-pop',
            'layout' => '@app/views/layouts/adminlte/main',
            'as access' => [
                'class' => 'yii\filters\AccessControl',
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin','client'],
                    ]
                ]
            ],

            'modules' => [
                'zapros' => [
                    'class' => 'app\modules\zapros\ZaprosModule',
                    'controllerNamespace' => 'app\modules\zapros\controllers\backend',
                    'viewPath' => '@app/modules/zapros/views/backend',
                ],
                'bid' => [
                    'class' => 'app\modules\bid\BidModule',
                    'controllerNamespace' => 'app\modules\bid\controllers\backend',
                    'viewPath' => '@app/modules/bid/views/backend',
                ],
                'reklamir' => [
                    'class' => 'app\modules\reklamir\ReklamirModule',
                    'controllerNamespace' => 'app\modules\reklamir\controllers\backend',
                    'viewPath' => '@app/modules/reklamir/views/backend',
                ],
                'file' => [
                    'class' => 'app\modules\file\FileModule',
                    'controllerNamespace' => 'app\modules\file\controllers\backend',
                    'viewPath' => '@app/modules/file/views/backend',
                ],
                'user' => [
                    'class' => 'app\modules\user\Module',
                    'controllerNamespace' => 'app\modules\user\controllers\backend',
                    'viewPath' => '@app/modules/user/views/backend',
                ],
                'block' => [
                    'class' => 'app\modules\block\BlockModule',
                    'controllerNamespace' => 'app\modules\block\controllers\backend',
                    'viewPath' => '@app/modules/block/views/backend',
                ],
                'test' => [
                    'class' => 'app\modules\test\TestModule',
                    'controllerNamespace' => 'app\modules\test\controllers\backend',
                    'viewPath' => '@app/modules/test/views/backend',
                ],
                'app' => [
                    'class' => 'app\modules\app\AppModule',
                    'controllerNamespace' => 'app\modules\block\controllers\backend',
                    'viewPath' => '@app/modules/block/views/backend',
                ],
                'car' => [
                    'class' => 'app\modules\car\CarModule',
                    'controllerNamespace' => 'app\modules\car\controllers\backend',
                    'viewPath' => '@app/modules/car/views/backend',
                ],
                'account' => [
                    'class' => 'app\modules\account\AccountModule',
                    'controllerNamespace' => 'app\modules\account\controllers\backend',
                    'viewPath' => '@app/modules/account/views/backend',
                ],
                'api' => [
                    'class' => 'app\modules\api\ApiModule',
                    'controllerNamespace' => 'app\modules\api\controllers\backend',
                    'viewPath' => '@app/modules/api/views/backend',
                ],
                'show' => [
                    'class' => 'app\modules\show\ShowModule',
                    'controllerNamespace' => 'app\modules\show\controllers\backend',
                    'viewPath' => '@app/modules/show/views/backend',
                ],
                'pay' => [
                    'class' => 'app\modules\pay\PayModule',
                    'controllerNamespace' => 'app\modules\pay\controllers\backend',
                    'viewPath' => '@app/modules/pay/views/backend',
                ],
                'balance' => [
                    'class' => 'app\modules\balance\PreviewModule',
                    'controllerNamespace' => 'app\modules\balance\controllers\backend',
                    'viewPath' => '@app/modules/balance/views/backend',
                ],


            ]
        ],
        'preview' => [
            'class' => 'app\modules\preview\PreviewModule',
            'controllerNamespace' => 'app\modules\preview\controllers\frontend',
            'viewPath' => '@app/modules/preview/views/frontend',
        ],
        'bid' => [
            'class' => 'app\modules\bid\BidModule',
            'controllerNamespace' => 'app\modules\bid\controllers\backend',
            'viewPath' => '@app/modules/bid/views/backend',
        ],
        'balance' => [
            'class' => 'app\modules\balance\BalanceModule',
            'controllerNamespace' => 'app\modules\balance\controllers\frontend',
            'viewPath' => '@app/modules/balance/views/frontend',
        ],
        'pay' => [
            'class' => 'app\modules\pay\PayModule',
            'controllerNamespace' => 'app\modules\pay\controllers\frontend',
            'viewPath' => '@app/modules/pay/views/frontend',
        ],

        'reklamir' => [
            'class' => 'app\modules\reklamir\ReklamirModule',
            'controllerNamespace' => 'app\modules\reklamir\controllers\frontend',
            'viewPath' => '@app/modules/reklamir/views/frontend',
        ],

        'file' => [
            'class' => 'app\modules\file\FileModule',
            'controllerNamespace' => 'app\modules\file\controllers\frontend',
            'viewPath' => '@app/modules/file/views/frontend',
        ],
        'test' => [
            'class' => 'app\modules\test\TestModule',
            'controllerNamespace' => 'app\modules\test\controllers\frontend',
            'viewPath' => '@app/modules/test/views/frontend',
        ],
        'payment' => [
            'class' => 'app\modules\payment\PaymentModule',
            'controllerNamespace' => 'app\modules\payment\controllers\frontend',
            'viewPath' => '@app/modules/payment/views/frontend',
        ],
        'helper' => [
            'class' => 'app\modules\helper\HelperModule',
        ],
        'main' => [
            'class' => 'app\modules\main\Module',
              'controllerNamespace' => 'app\modules\main\controllers\frontend',
            'viewPath' => '@app/modules/main/views/frontend',
        ],
        'user' => [
            'class' => 'app\modules\user\Module',
            'controllerNamespace' => 'app\modules\user\controllers\frontend',
            'viewPath' => '@app/modules/user/views/frontend',
        ],
        'telegram' => [
            'class' => 'onmotion\telegram\Module',
            'API_KEY' => '547897329:AAGm0-yJQJgl5m-zpcW4a_XOvoBfXF9lOjg',
            'BOT_NAME' => 'hlynovonline_bot',
            'hook_url' => 'https://hlynovonline.ru/telegram/default/hook', //
            //  be https! (if not prettyUrl https://yourhost.com/index.php?r=telegram/default/hook)
            'PASSPHRASE' => '9113',
            // 'db' => 'db2', //db file name from config dir
            'userCommandsPath' => '@app/components/telegram/UserCommands',
            // 'timeBeforeResetChatHandler' => 60
        ],
        'app' => [
            'class' => 'app\modules\app\AppModule',
            'controllerNamespace' => 'app\modules\user\controllers\frontend',
            'viewPath' => '@app/modules/user/views/frontend',
        ],
        'api' => [
            'class' => 'app\modules\api\ApiModule',
            'controllerNamespace' => 'app\modules\api\controllers\frontend',
            'viewPath' => '@app/modules/api/views/frontend',
        ],
        'account' => [
            'class' => 'app\modules\account\AccountModule',
        ],
        'zapros' => [
            'class' => 'app\modules\zapros\ZaprosModule',
            'controllerNamespace' => 'app\modules\zapros\controllers\frontend',
            'viewPath' => '@app/modules/zapros/views/frontend',
        ],
        'url' => [
            'class' => 'app\modules\url\UrlModule',
            'controllerNamespace' => 'app\modules\url\controllers\frontend',
            'viewPath' => '@app/modules/url/views/frontend',
        ],
        'sitemap' => [
            'class' => 'app\modules\sitemap\SitemapModule',
            'controllerNamespace' => 'app\modules\sitemap\controllers\frontend',
            'viewPath' => '@app/modules/sitemap/views/frontend',
        ],
    ],
    'components' => [

        'assetManager' => [
            'bundles' => [
                'dmstr\web\AdminLteAsset' => [
                    'skin' => 'skin-yellow-light',
                ],
            ],
        ],
        'formatter' => [
            'timeZone' => 'Europe/Moscow',
            'dateFormat' => 'dd.m.yyyy h:m',
            'decimalSeparator' => '.',
            'thousandSeparator' => ' ',
        ],

        'user' => [
            'identityClass' => 'app\modules\user\models\User',
            'enableAutoLogin' => true,
        //  'loginUrl' => ['/'],
            'loginUrl' => ['user/default/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'main/default/error',
        ],
        'request' => [
            'cookieValidationKey' => 'fgdfilgkdfgjk4ljt8923rj32ork2j90rf2iur2',
            'csrfParam' => '_csrffe',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
        ],
    ],
];

if ( $_SERVER['HTTP_HOST'] != 'mirovid.ru' /*YII_ENV_DEV*/) {
    
    // configuration adjustments for 'dev' environment

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug']= [
        'class'=>'yii\debug\Module',
        'allowedIPs'=>['*'],

    ];
  //

    $config['bootstrap'][] = 'gii';

    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => [
            '*',
            '127.0.0.1', '::1', '192.168.0.*', '192.168.178.20'
        ],
        'generators' => [ //here
            'crud' => [
                'class' => 'yii\gii\generators\crud\Generator',
                'templates' => [
                    'adminlte' => '@vendor/dmstr/yii2-adminlte-asset/gii/templates/crud/simple',
                ]
            ]
        ],
    ];

}

return $config;
