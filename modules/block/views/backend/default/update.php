<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\block\models\Block */

$this->title = 'Мем обновить';
$this->params['breadcrumbs'][] = ['label' => 'Mem Обновить', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="block-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
