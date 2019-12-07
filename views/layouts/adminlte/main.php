<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$bid_logs = \app\modules\bid\models\BidLog::find()->joinWith(['reklamir_r'])->where(['account_id'=>Yii::$app->getModule('account')->getAccount()->id])->limit(3)->all();
$bid_logs_total = \app\modules\bid\models\BidLog::find()->joinWith(['reklamir_r'])
    ->where(['account_id'=>Yii::$app->getModule('account')->getAccount()->id])->count();

if (Yii::$app->controller->action->id === 'login') { 
/**
 * Do not use this code in your template. Remove it. 
 * Instead, use the code  $this->layout = '//main-login'; in your controller.
 */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    /*
    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }
*/
    \app\assets\AdminLteAsset::register($this);
    \app\assets\AppMirovid::register($this);
    //dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-black sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset,'bid_logs'=>$bid_logs,'bid_logs_total'=>$bid_logs_total]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset,'bid_logs'=>$bid_logs,'bid_logs_total'=>$bid_logs_total]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
