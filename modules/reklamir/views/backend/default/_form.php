<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\reklamir\models\Thing;
use app\modules\reklamir\models\Reklamir;
use app\modules\helper\models\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\reklamir\models\Reklamir */
/* @var $form yii\widgets\ActiveForm */

?>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=68531ae1-9ce3-44cf-95f9-a2a922bf7358" type="text/javascript"></script>

<div class="reklamir-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'thing_id')->dropDownList( ArrayHelper::map(Thing::find()->all(),'id',function ($model){
        return  $model->place_r->name . ' ' . $model->place_r->num . ' ' . $model->cat_r->name . ' ' . $model->name;
    }) ) ?>
    <?= $this->render('_file',['model'=>$model,'form'=>$form]); ?>


    <?= $form->field($model, 'account_id')->hiddenInput(['value'=>Yii::$app->getModule('account')->getAccount()->id])->label(false) ?>


    <?php
     if (! $model->isNewRecord){
    echo Html::hiddenInput('reklamir_id',$model->id,[ 'id' =>'reklamir_id']);

     }
    ?>

    <?php

    if (Helper::getIsAdmin(Yii::$app->user->id)){
        echo $form->field($model, 'status')->dropDownList(Reklamir::$arrTxtStatus);
    } else {
        if ($model->isNewRecord){
            $model->status = Reklamir::ST_OFF;
        } else {
            echo $form->field($model, 'status')->dropDownList([ Reklamir::ST_ON => 'Идут показы', Reklamir::ST_OFF =>'Выключено']);
        }

    }

    ?>

    <?php if ( !$model->isNewRecord) {  ?>

    <div class="box-group" id="accordion">


        <div class="panel box box-primary">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTime" class="collapsed" aria-expanded="false">
                        Настроки Time
                    </a>
                </h4>
            </div>
            <div id="collapseTime" class="panel-collapse collapse" aria-expanded="false" >
                <div class="box-body">

                    <?php

                     echo  $this->render('_mem_update_time',
                         ['model'=>$model,'form'=>$form, 
                         ]);
                    /* echo  $this->render('_bid_time',
                        ['model'=>$model,'form'=>$form, 'bid_hour' => $bid_hour,
                          ]);*/ ?>


                </div>
            </div>
        </div>


        <div class="panel box box-success">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseArea"   aria-expanded="false">
                        Настроки Area
                    </a>
                </h4>
            </div>
            <div id="collapseArea" class="panel-collapse collapse" aria-expanded="false" >
                <div class="box-body">
                    <?= $this->render('_mem_update_locale',['model'=>$model,'form'=>$form]); ?>
                </div>
            </div>
        </div>


    </div>
<?php } ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
