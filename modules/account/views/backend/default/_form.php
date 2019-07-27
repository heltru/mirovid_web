<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\account\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-form box box-primary">
    <?php $form = ActiveForm::begin(); ?>
    <div class="box-body table-responsive">

        <?= $form->field($model, 'balance')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'user_id')->textInput() ?>

        <?= $form->field($model, 'status')->textInput() ?>

        <?= $form->field($model, 'date_cr')->textInput() ?>

    </div>
    <div class="box-footer">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
