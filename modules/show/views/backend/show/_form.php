<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\show\models\ShowRegister */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="show-register-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'file_id')->textInput() ?>

    <?= $form->field($model, 'date_sh')->textInput() ?>

    <?= $form->field($model, 'lat')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'long')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reklamir_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
