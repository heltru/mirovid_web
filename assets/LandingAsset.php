<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class LandingAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    /*    'place-theme/css/bootstrap/bootstrap.css',
        'place-theme/css/bootstrap/bootstrap-grid.css',
        'place-theme/css/bootstrap/bootstrap-reboot.css',

        'place-theme/css/animate.css',
        'place-theme/css/bootstrap-datepicker.css',
        'place-theme/css/helpers.css',
        'place-theme/css/bootstrap-datepicker.css',
        'place-theme/css/owl.carousel.min.css',
        'place-theme/css/owl.theme.default.css',
        'place-theme/css/select2.css',
        'style.css',*/
    ];
    public $js = [
    ];
    public $depends = [
     /*   'yii\web\YiiAsset',
        'raoul2000\bootswatch\BootswatchAsset',*/

    //    'yii\bootstrap\BootstrapAsset',
    ];
}
