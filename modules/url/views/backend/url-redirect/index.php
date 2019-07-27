<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\url\models\UrlRedirectSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Авто.ридирект';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="url-redirect-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Добавить', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <p>
        <?= Html::a('Импорт', ['import'], ['class' => 'btn btn-default']) ?>
    </p>
    <?php
    Pjax::begin([
        'id'=>'url-redirect-grid-ajax',
    ]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'attribute'=>'url_in',
                'format' => 'raw',
                'value' => function($model) {

                    $str = '<div>';
                    $str .= '<span class="valFieldGrid">'.$model->url_in.'</span>';
                    $str .= Html::textInput('editField',$model->url_in,
                        [
                            'data-field'=>'url_in',
                            'data-entity'=>'url-redirect',
                            'data-identity'=>$model->id ,
                            'class'=>'editFieldGrid form-control']);
                    $str .= ' </div>';
                    return $str;
                }
            ],
            [
                'attribute'=>'url_out',
                'format' => 'raw',
                'value' => function($model) {
                    $str = '<div>';
                    $str .= '<span class="valFieldGrid">'.$model->url_out.'</span>';
                    $str .= Html::textInput('editField',$model->url_out,
                        [
                            'data-field'=>'url_out',
                            'data-entity'=>'url-redirect',
                            'data-identity'=>$model->id ,
                            'class'=>'editFieldGrid form-control']);
                    $str .= ' </div>';
                    return $str;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);  Pjax::end(); ?>
</div>
<script>
    $(document).ready(function () {

        $('body').on('click', '.valFieldGrid',  function (e) {
            e.preventDefault();
            $(this).hide();
            $(this).parent().find('.editFieldGrid').show().focus();

        });

        $('body').on('focusout', '.editFieldGrid',  function (e) {
            $(this).hide();
            $(this).parent().find('.valFieldGrid').show();
            if ($(this).data('identity')){
                var $this = $(this);
                $.ajax({
                    type:"POST",
                    url:"<?= \yii\helpers\Url::to(['/admin/helper/default/edit-field-entity'])?>",
                    data:{
                        identity:$(this).data('identity'),
                        entity:$(this).data('entity'),
                        field:$(this).data('field'),
                        val:$(this).val(),
                        _csrfbe:yii.getCsrfToken()
                    },
                    beforeSend : function(){
                        $this.parent().parent().parent().append('<span>проверка началась..</span>');
                    },
                    success:function (data) {
                        $.pjax.reload('#url-redirect-grid-ajax');

                    }
                });
            }
        });


    });
</script>