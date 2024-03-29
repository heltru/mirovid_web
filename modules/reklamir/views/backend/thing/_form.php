<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\reklamir\models\ThingCat;
use yii\helpers\ArrayHelper;
use app\modules\reklamir\models\Place;

/* @var $this yii\web\View */
/* @var $model app\modules\reklamir\models\Thing */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="thing-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cat_id')->dropDownList(ArrayHelper::map(ThingCat::find()->all(),'id','name')) ?>

    <?= $form->field($model, 'place_id')->dropDownList(ArrayHelper::map( Place::find()->all(),'id','name')) ?>

    <?= $form->field($model, 'width')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>

    <?php
    /*
    echo $form->field($model, 'data')->widget(
        JsonEditor::class,
        [
            'clientOptions' => ['modes' => ['code', 'tree']],
            'decodedValue' => $model->data,
        ]
    );*/
    echo $form->field($model, 'global_config_local')->textarea(['rows' => 10])->hint('--led-rgb-sequence=RBG') ?>

    <?= $form->field($model, 'active')->dropDownList(\app\modules\reklamir\models\Thing::$arrTxtActive) ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
