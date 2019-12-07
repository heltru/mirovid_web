<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\reklamir\models\Place */

$this->title = 'Обновить: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Места', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="place-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
