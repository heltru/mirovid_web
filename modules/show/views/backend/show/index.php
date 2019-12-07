<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\modules\reklamir\models\Reklamir;
use kartik\date\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\show\models\ShowRegisterSearchTable */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Таблица показов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="show-register-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>



    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],





            [
                'attribute'=>'reklamir_r.name',

                'value' => function($model) {
                    return (is_object($model->reklamir_r) ) ?  $model->reklamir_r->name : '';
                },
                'filter' =>
                    Html::dropDownList(

                        'ShowRegisterSearchTable[reklamir_id]',

                        (isset(Yii::$app->request->queryParams['ShowRegisterSearchTable']['reklamir_id'] )) ?
                            Yii::$app->request->queryParams['ShowRegisterSearchTable']['reklamir_id']  : '',

                        ArrayHelper::map( Reklamir::find()
                            ->where(['account_id'=>Yii::$app->getModule('account')->getAccount()->id])->all(),
                            'id','name' )


                        ,['prompt'=>'нет', 'class'=>'form-control']),

            ],



            [
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'date_from',
                    'attribute2' => 'date_to',
                    'type' => DatePicker::TYPE_RANGE,
                    'separator' => '-',
                    'pluginOptions' => ['format' => 'yyyy-mm-dd']
                ]),
                'attribute' => 'date_sh',
                //'format' => 'datetime',
                'filterOptions' => [
                    'style' => 'max-width: 180px',
                ],
            ],

            'lat',
            'long',
            //'time:datetime',
            //'reklamir_id',


        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
