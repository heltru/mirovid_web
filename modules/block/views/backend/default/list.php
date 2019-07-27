<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 15.09.2018
 * Time: 15:40
 */
use yii\widgets\Pjax;
use yii\grid\GridView;
?>

<div class="row">
    <div class="col-md-12 col-xs-12">
        <div class="box box-info grid-rk-user ">
            <div class="box-header with-border">
                <h3 class="box-title">РК <?=$model->name?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- -->
            <div class="box-body table-responsive ">
                <?php
                Pjax::begin([
                    'id'=>'msg-grid-ajax',
                ]);
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
                        [
                            'attribute'=>'name',
                            'format'=>'raw',
                            'value' => function($model) {
                                return '<a class="name-row">'.$model->name.'</a>';
                            },

                        ],
                        /*       [
                                   'attribute'=>'account_r.name',

                                   'value' => function($model) {
                                       return (is_object($model->cat_r) ) ?  $model->cat_r->name : '';
                                   },
                                   'filter' =>
                                       Html::dropDownList('ProductSearch[cat_id]',
                                           (isset(Yii::$app->request->queryParams['ProductSearch']['cat_id'] )) ?
                                               Yii::$app->request->queryParams['ProductSearch']['cat_id']  : '',
                                           \yii\helpers\ArrayHelper::map( Cat::find()->where([ '!=',  'parent_id',0])->all() ,'id','name') ,['prompt'=>'нет', 'class'=>'form-control']),

                               ],*/

                        [
                            'attribute'=>'status',
                            'format'=>'raw',
                            'value' => function($model) {
                                return \app\modules\block\models\Block::$arrTxtStatus[ $model->status ] ;
                            },
                            'filter' => false

                        ],

                        //      'date_cr',
                        //     'type',

                        [
                            'label'=>'Операции',
                            'format'=>'raw',
                            'value'=>function ($model){
                                if (is_object($model)) {
                                    $ret = '<div class="btn-group" style="" role="group" aria-label="Операции">';
                                    $url = '';
                                    $ret .= ' <div class="btn-group" role="group">';
                                    $ret .= '<a href="'.$url.'" 
                                        title="Просмотреть" class="btn btn-default btn_view-rk" aria-label="Просмотреть" 
                                        data-id="'.$model->id.'" 
                                        data-pjax="0" 
                                         >
                                        <span class="glyphicon glyphicon-eye-open"></span></a>';
                                    $ret .= '</div>';
                                    $url = '';//\yii\helpers\Url::to(['badge/delete','id'=>$model->id]);
                                    $ret .= ' <div class="btn-group" role="group">';
                                    $ret .= '<a href="'.$url.'" 
                                        title="Удалить" class="btn btn-default remove-company" aria-label="Удалить" 
                                        data-id="'.$model->id.'" 
                                        data-pjax="0" 
                                         >
                                        <span class="glyphicon glyphicon-trash"></span></a>';
                                    $ret .= '</div>';



                                    $ret .= '</div>';
                                    return $ret;

                                }

                            }
                        ],
                    ],
                ]);
                Pjax::end();
                ?>
            </div>

        </div>

    </div>
</div>
