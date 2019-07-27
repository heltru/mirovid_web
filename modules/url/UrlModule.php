<?php

namespace app\modules\url;
use Yii;
/**
 * url module definition class
 */
class UrlModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
   // public $controllerNamespace = 'app\modules\url\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/admin/' . $category, $message, $params, $language);
    }
}
