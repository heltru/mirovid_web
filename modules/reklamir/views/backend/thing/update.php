<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\reklamir\models\Thing */

$this->title = 'Обновить ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Устройства', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="thing-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
