<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\car\models\CarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cars';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="car-index box box-primary">
    <div class="box-header with-border">
        <?= Html::a('Create Car', ['create'], ['class' => 'btn btn-success btn-flat']) ?>
    </div>
    <div class="box-body table-responsive no-padding">
        <?php
        Pjax::begin([
            'id'=>'car-grid-ajax',
        ]);
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'layout' => "{items}\n{summary}\n{pager}",
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'name',
                'number',
                'online',
               /* [
                        'label'=>'logs',
                        'format'=>'raw',
                        'value'=> function ($model){
                            return Html::tag('div','', [ 'class' =>'log']);
                        }
                ],*/

                // 'num_tableled',
                // 'date_cr',

                [
                    'label'=>'Операции',
                    'format'=>'raw',
                    'value'=>function ($model){
                        if (is_object($model)) {

                            $ret = '<div class="btn-group" style="" role="group" aria-label="Операции">';
                            $ret .= '<input type="hidden" class="car_id" value="'.$model->id.'">';



                            $ret .= ' <div class="btn-group" role="group">';
                            $ret .= '<a data-id="'.$model->id.'" class="btn info_car btn-default control_btn "  data-action="info" title="Info " aria-label="Info статус" >
                            <span class="glyphicon glyphicon-info-sign"></span></a>';
                            $ret .= '</div>';



                            /*
                            $ret .= ' <div class="btn-group" role="group">';
                            $ret .= '<a data-id="'.$model->id.'" class="btn start_car btn-default control_btn " data-action="play" title="Play " aria-label="Start статус" >
                            <span class="glyphicon glyphicon-play"></span></a>';
                            $ret .= '</div>';
*/
                            /*
                            $ret .= ' <div class="btn-group" role="group">';
                            $ret .= '<a data-id="'.$model->id.'" class="btn stop_car btn-default control_btn " data-action="stop" title="Stop " aria-label="Stop статус" >
                            <span class="glyphicon glyphicon-stop"></span></a>';
                            $ret .= '</div>';
*/



                            $ret .= ' <div class="btn-group" role="group">';
                            $ret .= '<a data-id="'.$model->id.'" class="btn update_app_car btn-default control_btn " data-action="update" title="Update " aria-label="Update App статус" >
                            <span class="glyphicon glyphicon-tint"></span></a>';
                            $ret .= '</div>';



                            $ret .= ' <div class="btn-group" role="group">';
                            $ret .= '<a data-id="'.$model->id.'" class="btn restart_car control_btn btn-default " data-action="restart" title="Restart " aria-label="Restart статус" >
                            <span class="glyphicon glyphicon-refresh"></span></a>';
                            $ret .= '</div>';



                            $url = \yii\helpers\Url::to(['delete','id'=>$model->id]);
                            $ret .= ' <div class="btn-group" role="group">';
                            $ret .= '<a href="'.$url.'" 
                                    title="Удалить" class="btn btn-default" aria-label="Удалить" 
                                    data-method="POST"
                                    data-pjax="0" 
                                    data-confirm="Вы уверены, что хотите удалить этот элемент?" >
                                    <span class="glyphicon glyphicon-trash"></span></a>';
                            $ret .= '</div>';

                            $ret .= ' <div class="btn-group" role="group">';
                            $url = \yii\helpers\Url::to(['update','id'=>$model->id]);
                            $ret .= '<a
                        href="'.$url.'"
title="Обновить" data-idimg="'.$model->id.'"  class="updateImg btn btn-default"
 aria-label="Обновить" data-pjax="0" 
><span class="glyphicon glyphicon-edit"></span></a>';
                            $ret .= '</div>';

                            $ret .= '<div class="log" >';
                            $ret .= '</div>';

                            $ret .= '</div>';

                            return $ret;
                        }
                    }
                ],
            ],
        ]); Pjax::end(); ?>
    </div>
</div>


<script>
    $(document).ready( function (){

        $('.control_btn').click( function ( e ) {

            console.log('tbn');

            var action = $(this).attr('data-action');
            var car_id = $(this).parent().parent().find('.car_id').val();
            var $this = $(this);

            $.ajax({
                type:"POST",
                url:"<?= \yii\helpers\Url::to(['/admin/car/default/car-action'])?>",
                data:{
                    action:action,
                    car_id:car_id,
                    _csrfbe:yii.getCsrfToken()
                },

                success:function (data) {
                    if (typeof data == 'object'){
                        if (data.status == 'success'){

                         console.log(    $this.parent().parent().parent().parent().find('.log').text(data.data.data) );
                            //$this.parent().parent().parent().parent().find('.log').html(data.data);

                         //   $.pjax.reload('#car-grid-ajax');
                        }

                    }


                }

            });
            });

    });
</script>