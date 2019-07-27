<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\components\grid\GridView;
use yii\widgets\Pjax;
use app\modules\block\models\Msg;
use app\modules\app\app\AppPrice;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$app_price = new AppPrice();
$this->title = 'Порядок показов';
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('/js/jquery-ui.min.js',  ['position' => yii\web\View::POS_END]);
$this->registerJsFile('/js/jquery.ui.touch-punch.min.js',  ['position' => yii\web\View::POS_END]);
?>
<style>
    .editFieldGrid {
        display: none;
    }
</style>
<div class="row">


    <div class="col-xs-12">
        <div class="box box-warning  grid-rk-user ">

            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <!-- -->
            <?php
            Pjax::begin(['id'=>'prodIndTblGridView-ajax']);
            ?>
            <div class="box-body table-responsive ">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'layout' => '{items} {pager}',
                    'id'=>'prodIndTblGridView',
                    'idTBody'=>'prodIndRowView',
                    'columns' => [
                      //  ['class' => 'yii\grid\SerialColumn'],
                        [
                            'label'=>'Операции',
                            'format'=>'raw',
                            'value'=>function ($model){
                                if (is_object($model)) {
                                    $ret = '<div class="btn-group" style="" role="group" aria-label="Операции">';

                                    $ret .= ' <div class="btn-group" role="group">';
                                    $ret .= '<a class="btn btn-default ui-sortable-handle-imgv"> <span class="glyphicon glyphicon-sort"></span></a>';
                                    $ret .= '</div>';


                                    $ret .= ' <div class="btn-group" role="group">';
                                    $ret .= '<a href="'.Url::to(['/admin/block/default/msg-update','id'=>$model->id]).'" class="btn btn-default ui-sortable-handle-imgv"> <span class="glyphicon glyphicon-edit"></span></a>';
                                    $ret .= '</div>';


                                    $ret .= '</div>';
                                    return $ret;
                                }
                            }
                        ],
                        [
                            'attribute'=>'content',
                            'format'=>'raw',
                            'value'=> function ($model){
                                if ($model->type == Msg::T_I ){
                                    return Html::img( '/' .  $model->content,['style'=>'width:'.(164) . 'px' ]);
                                }
                                if ($model->type == Msg::T_T && file_exists($model->content)){
                                    return file_get_contents( $model->content);
                                }
                    /*
                                if ( $model->type == Msg::T_I ){
                                    return \yii\helpers\Html::img( '/'. $model->content,['width'=>256]);
                                }
                                if ( $model->type == Msg::T_T ){
                                    return file_get_contents(  $model->content);
                                }
*/
                            },
                            'filter'=>false
                        ]
                        ,

                         [
                            'attribute'=>'count_show',
                            'format'=>'raw',
                            'value'=> function ($model){
                                return  $model->count_show;
                            },
                            'filter'=>false
                        ],
                        [
                            'attribute'=>'count_limit',
                            'format'=>'raw',
                            'value'=> function ($model){
                                $fp = $model->count_limit;
                                $str = '<div>';
                                $str .= '<span class="valFieldGrid">'.$fp.'</span>';
                                $str .= Html::textInput('editField',$model->count_limit,
                                    [

                                        'data-identity'=>$model->id ,
                                        'class'=>'editFieldGrid form-control ']);
                                $str .= ' </div>';
                                return $str;

                                //return  $model->count_limit;
                            },
                            'filter'=>false
                        ],
                        [
                            'attribute'=>'count_total',
                            'format'=>'raw',
                            'value'=> function ($model){
                                return  $model->count_total;
                            },
                            'filter'=>false
                        ],
                        [
                            'attribute'=>'status',
                            'format'=>'raw',
                            'value'=> function ($model){
                                return  \app\modules\block\models\Msg::$arrTxtStatus[ $model->status];
                            },
                            'filter'=>false
                        ],

                        [
                            'attribute'=> 'block_r.name',
                            'label'=>'Компания',
                            'format'=>'raw',
                            'value'=> function ($model){
                                return   $model->block_r->name;
                            },
                            'filter'=>false
                        ],



                    ],
                ]); ?>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <p style="margin: 1em;">Итого запланированно показать сообщений: <span>  <?php echo  $count_limit_total?></span></p>
                    <p style="margin: 1em;">Итого на сумму: <span>   <?php echo Yii::$app->formatter->asCurrency(   ($count_limit_total * $app_price->getPriceMsg()) ,0)  ?> руб.</span></p>

                </div>
            </div>
            <?php  Pjax::end(); ?>

        </div>
</div>


<script>
    $(document).ready(function (e) {

        $( "#prodIndRowView" ).sortable({
            items: "tr",
            update:function () {
                var info = $(this).sortable("serialize",{'attribute':'idsort'});

                $.ajax({
                    type: "POST",
                    url: "<?= Url::to(['block-pop-sort'])?>",
                    data: {_csrfbe:yii.getCsrfToken(),info:info},
                    context: document.body
                });
            },
            placeholder: "ui-state-highlight-group",
            handle: $(".ui-sortable-handle-imgv")
        });
        $( "#prodIndRowView" ).disableSelection();





        $('body').on('click', '.valFieldGrid',  function (e) {
            e.preventDefault();
            $(this).hide();
            $(this).parent().find('.editFieldGrid').show().focus();

        });

        $('body').on('focusout', '.editFieldGrid',  function (e) {
            $(this).hide();
            $(this).parent().find('.valFieldGrid').show();



            if ($(this).data('identity')){

                $.ajax({
                    type:"POST",
                    url:"<?= \yii\helpers\Url::to(['/admin/block/block-utils/msg-update-count-limit'])?>",
                    data:{
                        msg_id:$(this).data('identity'),
                        value:$(this).val(),
                        _csrfbe:yii.getCsrfToken()
                    },
                    success:function (data) {
                        $.pjax.reload('#prodIndTblGridView-ajax');

                    }
                });
            }
        });






    });



</script>

