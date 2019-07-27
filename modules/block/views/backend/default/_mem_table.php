<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 17.09.2018
 * Time: 1:34
 */

/*
 *
 * 'memTableSearchDp'=>$memTableSearchDp,
            'memTableSearch'=>$memTableSearch,
 * */
use app\components\grid\GridView;
use app\modules\block\models\Msg;
?>
<div class="box box-info grid-rk-user ">
    <div class="box-header with-border">
        <h3 class="box-title">Состав компании</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- -->
    <div  class="box-body table-responsive ">

        <?php
            echo GridView::widget([

    'dataProvider' => $memTableSearchDp,
    'filterModel' => $memTableSearch,
    'layout' => '{items}{pager}',
        'showHeader'=> false,
    'columns' => [
     //   ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute'=>'name',
            'format'=>'raw',
            'value' => function($model) {
              //  return $model->getContentFormat();


                if ( $model->type == Msg::T_I ){
                    return \yii\helpers\Html::img( '/'. $model->content,['width'=>256]);
                }
                if ( $model->type == Msg::T_T ){
                    return (is_file($model->content)) ?  file_get_contents( $model->content ) : '';
                }


            },

        ],


        [
            'label'=>'Операции',
            'format'=>'raw',
            'value'=>function ($model){
                if (is_object($model)) {
                    $ret = '<div class="btn-group" style="" role="group" aria-label="Операции">';


                    if (is_object($model->blockMsg_r)){



                        $ret .= '<a href="'. \yii\helpers\Url::to(['/admin/block/default/msg-remove-to-rk','id'=>$model->block_id,'id_msg'=>$model->id]).'" 
                                        title="Удалить из списка показа" class="btn btn-default msg-add-to-rk" aria-label="Удалить из списка показа"
                                         
                                        data-id="'.$model->id.'" 
                                        data-pjax="0" 
                                         >
                                         
                                         <span class="glyphicon glyphicon-film"></span>
                                        <span class="glyphicon glyphicon-minus"></span></a>';

                    } else {
                        $ret .= '<a href="'. \yii\helpers\Url::to(['/admin/block/default/msg-add-to-rk','id'=>$model->block_id,'id_msg'=>$model->id]).'" 
                                        title="Добавить в список показа" class="btn btn-default msg-add-to-rk" aria-label="Добавить в список показа"
                                         
                                        data-id="'.$model->id.'" 
                                        data-pjax="0" 
                                         >
                                         <span class="glyphicon glyphicon-film"></span>
                                        <span class="glyphicon glyphicon-plus"></span></a>';
                    }

                    $ret .= '<a href="'. \yii\helpers\Url::to(['/admin/block/default/msg-update','id'=>$model->id]).'" 
                                        title="Обновить" class="btn btn-default remove-company" aria-label="Обновить" 
                                        data-id="'.$model->id.'" 
                                        data-pjax="0" 
                                         >
                                        <span class="glyphicon glyphicon-edit"></span></a>';


                    $ret .= '<a href="'. \yii\helpers\Url::to(['/admin/block/default/delete-msg-from-rk','id'=>$model->block_id,'id_msg'=>$model->id]).'" 
                                        title="Удалить" class="btn btn-default remove-company" aria-label="Удалить" 
                                        data-id="'.$model->id.'" 
                                        data-pjax="0" 
                                         >
                                        <span class="glyphicon glyphicon-trash"></span></a>';





                    $ret .= '</div>';


                    return $ret;

                }

            }
        ],

    ]]);
        ?>

    </div>

</div>
