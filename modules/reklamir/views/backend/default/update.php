<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\reklamir\models\Reklamir */

$this->title = 'Обновить рекламу: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Моя реклама', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="reklamir-update">

    <h1><?= Html::encode($model->name) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'bid'=>$bid,
        'bid_minute'=>$bid_minute,
        'bid_hour'=>$bid_hour,
        'model' => $model,

    ]) ?>

</div>
