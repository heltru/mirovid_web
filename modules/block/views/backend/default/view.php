<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $mem app\modules\block\models\Msg */
/* @var $model app\modules\block\models\Block */
/* @var $searchModel app\modules\block\models\BlockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\widgets\Pjax;
$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
//$this->registerJsFile('/js/dti/dom-to-image.min.js',  ['position' => yii\web\View::POS_END]);
//$this->registerJsFile('/js/jimp/jimp.js',  ['position' => yii\web\View::POS_END]);
$this->registerJsFile('/js/emojionearea/dist/emojionearea.js',  ['position' => yii\web\View::POS_END]);
$this->registerJsFile(
    '/js/jquery.Jcrop.min.js',
    ['position' => yii\web\View::POS_END]
);
$this->registerCssFile('/js/emojionearea/dist/emojionearea.css',['position' => yii\web\View::POS_END]);
$this->registerJsFile('/js/dti/dom-to-image.min.js',  ['position' => yii\web\View::POS_END]);
$this->registerCssFile('/css/jquery.Jcrop.css',['position' => yii\web\View::POS_END]);
$this->registerJsFile('/js/jimp/jimp.js',  ['position' => yii\web\View::POS_END]);
//$this->registerCssFile('/jcrop/jcrop.css',['position' => yii\web\View::POS_END]);
?>

<div class="row">

    <div class="col-md-12 col-xs-12 new-rk ">

        <?php echo $this->render('_mem_create',['mem'=>$mem,'block'=>$model]); ?>

    </div>

    <?php if (($memTableSearchDp->getCount())){ ?>
        <div class="col-md-12 col-xs-12">

            <?php echo $this->render('_mem_table',[

                'memTableSearchDp'=>$memTableSearchDp,
                'memTableSearch'=>$memTableSearch,

            ]); ?>

        </div>
    <?php } ?>




    <?php \yii\widgets\ActiveForm::begin() ?>
    <div class="col-md-12 col-xs-12">
        <div class="row">

            <div class="col-md-4 col-xs-6">
                <?php echo Html::activeTextInput($model,'name',['class'=>'form-control'])?>
            </div>
            <div class="col-md-4 col-xs-6">
                <?php echo Html::submitButton('Переименовать',['class'=>'btn btn-default'])?>
            </div>
            <div class="col-md-4 col-xs-12">
                <?php echo Html::a('Удалить компанию',\yii\helpers\Url::to(['/admin/block/default/del-rk-company','id'=>$model->id]),
                    [ /*'id'=>'rmvRkCompany',*/'attr-id'=>$model->id,'class'=>'btn btn-default']) ?>
            </div>
        </div>
    </div>
    <?php \yii\widgets\ActiveForm::end(); ?>


</div>

<script>
    $(document).ready( function (){
        $('#rmvRkCompany').click(function (e){
            var id = $(this).attr('attr-id');
            if ( id ){

                $.ajax({
                    type: "POST",
                    url: "<?= \yii\helpers\Url::to(['/admin/block/default/del-rk-company'])?>",
                    data: {_csrfbe:yii.getCsrfToken(),id:id},
                    success:function (data) {
                        if (typeof data == 'object'){
                            if (data.status == 'success'){

                               // $.pjax.reload('#blocks-grid-ajax');

                                $('section.content').before(data.response)

                            }
                            if (data.status == 'error'){

                                $('section.content').before(data.response)

                            }

                        }


                    }

                });
            }
        });





    } );
</script>
