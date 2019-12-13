<?php

namespace app\modules\api\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class  AssetHtml extends AssetBundle
{
    public $basePath = '@webroot/themes/html';
    public $baseUrl = '@web/themes/html';

    public $css = [
    ];

    public $js = [
        'jquery-3.4.1.min.js'
    ];
    public $jsOptions = ['position' => \yii\web\View::POS_BEGIN];




}
