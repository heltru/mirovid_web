<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\show\models\ShowRegisterSearchTable */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="show-register-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'file_id') ?>

    <?= $form->field($model, 'date_sh') ?>

    <?= $form->field($model, 'lat') ?>

    <?= $form->field($model, 'long') ?>

    <?php // echo $form->field($model, 'time') ?>

    <?php // echo $form->field($model, 'reklamir_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
