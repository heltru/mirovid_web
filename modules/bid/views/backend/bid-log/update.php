<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bid\models\BidLog */

$this->title = 'Update Bid Log: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bid Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bid-log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
