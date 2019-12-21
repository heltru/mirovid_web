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
    <?php Pjax::begin([
        'id' => 'reklamir-grid-ajax',
    ]); ?>
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
            [
                'attribute' => 'file_id',
                'format' => 'raw',
                'value' => function ($data) {
                    if (!is_object($data->file_r)) {
                        return '';
                    }

                    $pathinfo = pathinfo($data->file_r->path);
                    $ext = $pathinfo['extension'];

                    if (in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'bmp'])) {
                        return ($data->file_r->path) ? Html::img('/' . $data->file_r->path, ['width' => '150px']) : $data->file_r->name;
                    } else {
                        return ($data->file_r->path_preview) ? Html::img('/' . $data->file_r->path_preview, ['width' => '150px']) : $data->file_r->name;
                    }

                }
            ]

            ,
            'name',
            'show',

            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => function ($model) {
                    return Html::dropDownList('reklamir_status', $model->status,
                        [ Reklamir::ST_ON => Reklamir::$arrTxtStatus[Reklamir::ST_ON ],
                            Reklamir::ST_OFF=> Reklamir::$arrTxtStatus[Reklamir::ST_OFF ],
                            ]
                        , ['class' => 'reklamir_status form-control','data-id'=>$model->id,'prompt'=>'---']);
                },
                'filter' => [ Reklamir::ST_ON => Reklamir::$arrTxtStatus[Reklamir::ST_ON ],
                    Reklamir::ST_OFF=> Reklamir::$arrTxtStatus[Reklamir::ST_OFF ],
                ]
            ],


            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],

            [
                'attribute' => 'thing_r.name',
                'value'=>function ($model){
                    return /*' id:' . $model->thing_r->id . ' ' .*/$model->thing_r->name ;
                },
                'filter' =>
                    Html::dropDownList(

                        'ReklamirSearch[thing_id]',

                        (isset(Yii::$app->request->queryParams['ReklamirSearch']['thing_id'])) ?
                            Yii::$app->request->queryParams['ReklamirSearch']['thing_id'] : '',
                        \yii\helpers\ArrayHelper::map(
                            \app\modules\reklamir\models\Thing::find()->innerJoin(
                                'reklamir', 'reklamir.thing_id = thing.id AND reklamir.account_id = :acc',
                                ['acc' => Yii::$app->getModule('account')->getAccount()->id]

                            )->all(), 'id', 'name'
                        )

                        , ['prompt' => 'нет', 'class' => 'form-control']),
            ],
            [
                'attribute' => 'thing_r.place_r.name',
                'value'=> function ($model){
                        return  $model->thing_r->place_r->name . ' ' . $model->thing_r->place_r->num;
                },
                'filter' =>
                    Html::dropDownList(

                        'ReklamirSearch[place_id]',

                        (isset(Yii::$app->request->queryParams['ReklamirSearch']['place_id'])) ?
                            Yii::$app->request->queryParams['ReklamirSearch']['place_id'] : '',
                        \yii\helpers\ArrayHelper::map(
                            \app\modules\reklamir\models\Place::find()
                                ->innerJoin('thing', 'thing.place_id=place.id')
                                ->innerJoin(
                                    'reklamir', 'reklamir.thing_id = thing.id AND reklamir.account_id = :acc',
                                    ['acc' => Yii::$app->getModule('account')->getAccount()->id]

                                )->all(), 'id', 'name'
                        )

                        , ['prompt' => 'нет', 'class' => 'form-control']),
            ],


            'id',


        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<script>
    $(document).ready(function () {

        $('body').on('change', '.reklamir_status', function (e) {
            let status = $(this).val();
            let id = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                url: "<?= \yii\helpers\Url::to(['/admin/reklamir/default/change-status'])?>",
                data: {id:id,status: status, _csrfbe: yii.getCsrfToken()},
                success: function (data) {
                    $.pjax.reload('#reklamir-grid-ajax');

                }
            });
        });


    });

</script>