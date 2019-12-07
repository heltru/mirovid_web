<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\show\models\ShowRegister */

$this->title = 'Update Show Register: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Show Registers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="show-register-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
