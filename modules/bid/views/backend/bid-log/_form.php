<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bid\models\BidLog */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bid-log-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reklamir_id')->textInput() ?>

    <?= $form->field($model, 'msg')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'read')->textInput() ?>

    <?= $form->field($model, 'time_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
