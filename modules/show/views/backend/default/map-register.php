<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use app\modules\image\models\Img;
use app\modules\image\models\ImgLinks;
use kartik\slider\Slider;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Зарегестрированные показы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="badge-index">


    <div class="row">





        <div class="col-xs-12">
            <?php
            echo $this->render('_map_register',['points'=>$points]);
            ?>
        </div>

        <div class="col-xs-12">
            <div class="block-search">

                <?php $form = ActiveForm::begin([
                    'action' => ['index'],
                    'method' => 'get',
                ]); ?>

                <?= $form->field($searchModel, 'date_from')->widget(\dosamigos\datetimepicker\DateTimePicker::className(), [
                    'language' => 'es',
                    'size' => 'ms',
                    'template' => '{input}',
                    'pickButtonIcon' => 'glyphicon glyphicon-time',
                    'inline' => true,
                    'clientOptions' => [
                        'startView' => 1,
                        'minView' => 0,
                        'maxView' => 1,
                        'autoclose' => true,
                        'linkFormat' => 'HH:ii P', // if inline = true
                        // 'format' => 'HH:ii P', // if inline = false
                        'todayBtn' => true
                    ]
                ]);?>

                <?= $form->field($searchModel, 'date_from')->widget(\dosamigos\datetimepicker\DateTimePicker::className(), [
                    'language' => 'es',
                    'size' => 'ms',
                    'template' => '{input}',
                    'pickButtonIcon' => 'glyphicon glyphicon-time',
                    'inline' => true,
                    'clientOptions' => [
                        'startView' => 1,
                        'minView' => 0,
                        'maxView' => 1,
                        'autoclose' => true,
                        'linkFormat' => 'HH:ii P', // if inline = true
                        // 'format' => 'HH:ii P', // if inline = false
                        'todayBtn' => true
                    ]
                ]);?>

                <?php

            echo  DatePicker::widget([
                'model' => $searchModel,
                'attribute' => 'date_from',
                'attribute2' => 'date_to',
                'type' => DatePicker::TYPE_RANGE,
                'separator' => '-',
                'pluginOptions' => ['format' => 'yyyy-mm-dd']
            ]);
/*
                echo $form->field($model, 'rating')->widget(Slider::classname(), [
                    'pluginOptions'=>[
                        'min'=>10,
                        'max'=>1000,
                        'step'=>5,
                        'range'=>true
                    ]
                ]);
*/


                ?>


                <div class="form-group">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>

        </div>

        <div class="col-xs-12">
            <?php
            /*GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',

                    // ['class' => 'yii\grid\ActionColumn'],
                ],
            ]);
            */
             ?>
        </div>

    </div>
</div>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>




</div>
