<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;

use app\modules\main\assets\one\AssetLandingIndex;
use app\modules\main\assets\one\AssetLandingEnd;

AssetLandingIndex::register($this);
AssetLandingEnd::register($this);

?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" data-test="434" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# product: http://ogp.me/ns/product#" >
    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />
        <meta name="format-detection" content="telephone=no">
        <meta http-equiv="x-rim-auto-match" content="none">

        <?= Html::csrfMetaTags() ?>
        <?= ( $this->title ) ? '<title>'.Html::encode($this->title).'</title>' : '' ?>
        <meta name="google-site-verification" content="0gKCpSSCzrXXR8msLiqatdtq2WkExrKywNjjzphJBX0" />
        <meta name="yandex-verification" content="60ae4dfb8a3cd3d6" />


        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-NTPG8P5');</script>
        <!-- End Google Tag Manager -->

        <!--[if lt IE 9]> <script src="scripts/html5.js"></script> <![endif]-->

        <?php $this->head() ?>
        <?php echo $this->render('//gtm/inhead') ?>

    </head>
    <body>

    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NTPG8P5"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php echo $this->render('//gtm/openbody') ?>
    <?php $this->beginBody() ?>

        <?= $content ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>