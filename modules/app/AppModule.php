<?php

namespace app\modules\app;

/**
 * app module definition class
 */
class AppModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\app\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }


    public static function getName()
    {
        return isset(\Yii::$app->params['name']) ? (string)\Yii::$app->params['name'] : '';
    }

    public static function getUserId()
    {
        $user = \Yii::$app->getModule('user')->getUser();
        return  ($user && $user->id ) ? (int)$user->id : 0;
    }

    public static function getVkAppId4auth($app_id = 0)
    {
        $app_id = intval($app_id);
        if (!$app_id) $app_id = self::getVkAppId4authDefault();
        return $app_id;
    }

    public static function getLinkVerifyCallback($group_id)
    {
        return isset(\Yii::$app->params['vk']['link_verify_callback']) ?
            (string)\Yii::$app->params['vk']['link_verify_callback'] . '/' . $group_id : '';
    }

    public static function getVkAppId4authDefault()
    {
        $app_id = 0;

        foreach (\Yii::$app->params['vk']['app4auth'] as $id => $value) {
            $app_id = $id;
            break;
        }
        return $app_id;
    }

    public static function getVkAppSecret4auth($app_id = 0)
    {
        $app_id = intval($app_id);
        if (!$app_id) $app_id = self::getVkAppId4authDefault();
        return isset(\Yii::$app->params['vk']['app4auth'][$app_id]['secret']) ?
            (string)\Yii::$app->params['vk']['app4auth'][$app_id]['secret'] : '';
    }

    public static function getVkAppRedirect($app_id = 0, $return = '')
    {
        $app_id = intval($app_id);
        if (!$app_id) $app_id = self::getVkAppId4authDefault();
        return (isset(\Yii::$app->params['vk']['redirect']) ?
                (string)\Yii::$app->params['vk']['redirect'] : '') .
            '?app_id=' . $app_id . ($return ? '&return=' . urlencode($return) : '');
    }

    public static function getUserVkToken()
    {
        $user = \Yii::$app->getModule('user')->getUser();
        return  ($user && $user->vk_token ) ? $user->vk_token : 0;
    }
    public static function getVkAppTech4user()
    {
        return isset(self::$config['vk']['app4subscribe']['tech']) ? (string)self::$config['vk']['app4subscribe']['tech'] : '';
    }

    public static function getUserVkId()
    {
        $user = \Yii::$app->getModule('user')->getUser();
        return  ($user && $user->vk_id ) ? (int)$user->vk_id : 0;
    }



}
