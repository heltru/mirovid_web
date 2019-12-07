<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\bid\models\Bid */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bid-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reklamir_id')->textInput() ?>

    <?= $form->field($model, 'val')->textInput() ?>

    <?= $form->field($model, 'minute_id')->textInput() ?>

    <?= $form->field($model, 'hour_id')->textInput() ?>

    <?= $form->field($model, 'mount_id')->textInput() ?>

    <?= $form->field($model, 'year_id')->textInput() ?>

    <?= $form->field($model, 'day_id')->textInput() ?>

    <?= $form->field($model, 'time_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
