<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\reklamir\models\ThingCat */

$this->title = 'Обновить ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Категории устройств', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="thing-cat-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
