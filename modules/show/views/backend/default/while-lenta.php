<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\car\models\CarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use app\modules\block\models\Msg;
$this->title = 'Главная Очередь';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-index box box-primary">

    <div class="box-body table-responsive no-padding">
        <?php
        Pjax::begin([
            'id'=>'car-grid-ajax',
        ]);
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,

            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute'=>'content',
                    'format'=>'raw',
                    'value'=> function ($model){
                      //  return $model->getContentFormat();
                        if ( $model['type'] == Msg::T_I ){
                            return \yii\helpers\Html::img( '/'. $model['content'],['width'=>256]);
                        }
                        if ( $model['type'] == Msg::T_T && file_exists($model['content']) ){
                            return file_get_contents(  $model['content']);
                        }

                    },
                    'filter'=>false
                ]
                ,
                'msg_id',
                'account_sort',
                'type',

//                'content'


            ],
        ]); Pjax::end(); ?>
    </div>
</div>


<script>
    $(document).ready( function (){



    });
</script>