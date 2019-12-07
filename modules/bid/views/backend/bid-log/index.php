<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\bid\models\BidLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Уведомления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bid-log-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],


            [
                'label'=>'Сообщение',
                'format'=>'raw',
                'value'=>function ($model){
                $str = ' <a target="_blank" href="'.
                    \yii\helpers\Url::to(['/admin/reklamir/default/update','id'=>$model->reklamir_id]) .'">

                                             <i class="fa fa-warning text-yellow"></i>
                                             Реклама: ' . $model->reklamir_r->name . ': '. $model->msg .

                                             'Вашу цену на <i class="fa fa-calendar"></i> '.
                    date('d.m.Y',$model->time_id) .'
                                             <i class="fa fa-clock-o" ></i>'
                      . date('H:i',$model->time_id).'  перекупили.</a>';
                return $str;
                }
            ],




            ['class' => 'yii\grid\ActionColumn','template'=>'{delete}'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
