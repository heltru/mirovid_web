<?php

namespace app\modules\url;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
       /* $app->getUrlManager()->addRules(
            [
                [
                    'class' => 'modules\url\components\CardRule',
                ],
            ]
        );
       */
        /*
         *    $app->getUrlManager()->addRules(
            [
                // объявление правил здесь
                '' => 'site/default/index',
                '<_a:(about|contacts)>' => 'site/default/<_a>'
            ]
        );*/
      /*  $app->i18n->translations['modules/url/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'forceTranslation' => true,
            'basePath' => '@app/modules/main/messages',
            'fileMap' => [
                'modules/main/module' => 'module.php',
            ],
        ];*/
    }
}