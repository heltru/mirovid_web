<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\car\models\Car */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="car-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'number')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'online')->textInput() ?>
        <?= $form->field($model, 'num_tableled')->textInput() ?>



    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
