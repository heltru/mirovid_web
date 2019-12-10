<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\reklamir\models\Reklamir;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\reklamir\models\ReklamirSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Моя реклама';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reklamir-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<div class="row">
    <div class="col-xs-6">
        <p>
            <?= Html::a('Загрузить рекламу', ['create'], ['class' => 'btn btn-success']) ?>
        </p>
    </div>
    <div class="col-xs-6">
        <p style="text-align: right;
    font-weight: bold;">10 руб/показ</p>
    </div>
</div>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'thing_r.name',
            'thing_r.place_r.name',
            [
                    'attribute'=>'file_id',
                    'format'=>'raw',
                    'value'=>function ($data){
                        if ( ! is_object($data->file_r)){
                            return '';
                        }

                        $pathinfo = pathinfo($data->file_r->path);
                        $ext = $pathinfo['extension'];

                        if (in_array($ext,['png','jpg','jpeg','gif','bmp'])){
                            return ($data->file_r->path) ? Html::img( '/'.$data->file_r->path,['width'=>'150px']) : $data->file_r->name;
                        } else {
                            return (   $data->file_r->path_preview) ? Html::img('/'.$data->file_r->path_preview,['width'=>'150px']) : $data->file_r->name;
                        }

                    }
]
           ,

             'show',
             [
                     'attribute'=>'status',
                     'value'=>function($model){
                            return Reklamir::$arrTxtStatus[$model->status];
                     },
                     'filter'=> Reklamir::$arrTxtStatus
             ],

            ['class' => 'yii\grid\ActionColumn','template'=>'{update} {delete}'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

