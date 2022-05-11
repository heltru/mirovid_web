<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;



\app\modules\api\assets\AssetHtml::register($this);


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


        <?php $this->head() ?>

        <style>
            html, body {
                margin: 0;
                height: 100%;
                overflow: hidden;
            }

            img {
                min-height: 80%;
                min-width: 100%;
                height: auto;
                width: auto;
                position: absolute;
                top: -100%;
                bottom: -100%;
                left: -100%;
                right: -100%;
                margin: auto;
            }



        </style>
    </head>
    <body>

    <?php $this->beginBody() ?>

        <?= $content ?>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>