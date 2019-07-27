<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\car\models\Car */

$this->title = 'Update Car: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Cars', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="car-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
