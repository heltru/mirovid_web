<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\url\models\UrlRedirect */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="url-redirect-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'url_in')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'url_out')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
