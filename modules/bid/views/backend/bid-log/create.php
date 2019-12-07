<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\bid\models\BidLog */

$this->title = 'Create Bid Log';
$this->params['breadcrumbs'][] = ['label' => 'Bid Logs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bid-log-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
