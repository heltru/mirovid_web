<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\reklamir\models\Reklamir;
use app\modules\helper\models\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\reklamir\models\Reklamir */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reklamir-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->errorSummary($model) ?>

    <?= $form->field($model, 'account_id')->hiddenInput(['value' => Yii::$app->getModule('account')->getAccount()->id])->label(false) ?>


    <?php
    if (!$model->isNewRecord) {
        echo Html::hiddenInput('reklamir_id', $model->id, ['id' => 'reklamir_id']);
    }
    ?>

    <?php

    if (Helper::getIsAdmin(Yii::$app->user->id)) {
        //echo $form->field($model, 'status')->dropDownList(Reklamir::$arrTxtStatus);
    } else {
//        if ($model->isNewRecord) {
//            $model->status = Reklamir::ST_OFF;
//        } else {
//            echo $form->field($model, 'status')->dropDownList([Reklamir::ST_ON => 'Идут показы', Reklamir::ST_OFF => 'Выключено']);
//        }
    }

    ?>
    <p>Выберите источник</p>

    <div class="box-group" id="accordion">
        <div class="panel box box-primary" style="margin-bottom: 0">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseVK" class="collapsed"
                       aria-expanded="true">
                        ВКонтакте
                    </a>
                </h4>
            </div>
            <div id="collapseVK" class="panel-collapse collapse in" aria-expanded="true">
                <div class="box-body">

                    <?php
                    echo Html::dropDownList('vk_type', null, ['wall' => 'Моя Стена'], ['prompt' => 'Выберите тип', 'class' => 'form-control']);
                    ?>
                    <div data-role="vk_content">

                    </div>

                </div>
            </div>
        </div>
        <div class="panel box box-success" style="margin-bottom: 0">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFile" class="collapsed"
                       aria-expanded="false">
                        Файл
                    </a>
                </h4>
            </div>
            <div id="collapseFile" class="panel-collapse collapse" aria-expanded="false">
                <div class="box-body">
                    <?php
                    echo $this->render('_file', ['model' => $model, 'form' => $form]);
                    ?>
                    <div data-role="file_content">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div data-role="preview">

    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $(document).ready(function () {

        class Add {
            constructor(el) {
                console.log('test');
                this.form = el.find('#w0');
                this.vk_type = this.form.find('select[name="vk_type"]');
                this.vk_content = this.form.find('[data-role="vk_content"]');
                this.preview = this.form.find('div[data-role="preview"]');
                this.file = this.form.find('#reklamir-uploadfile');

                this.content = {
                    wall:{}
                };

                this.file.change( (e) => {
                    this.preview.html('');
                });

                this.vk_content.on('click','[data-role="btn_add"]', (e) => {
                    let index = $(e.currentTarget).attr('data-index');
                    let type = $(e.currentTarget).attr('data-type');
                    if (type === 'wall'){
                        let content =  this.content[type][index];
                        if (content.type === 'img'){
                            let html = '<img src="'+content.img+'">';
                            html += '<input type="hidden" name="type" value="'+content.type+'">';
                            html += '<input type="hidden" name="img_download" value="'+content.img_download+'">';
                            this.preview.html(html);
                            $([document.documentElement, document.body]).animate({
                                scrollTop: this.preview.offset().top
                            },500);
                        }
                    }
                });

                this.vk_type.change((e) => {
                    let type = $(e.currentTarget).val();
                    if (type === 'wall') {
                        $.ajax({
                            type: "POST",
                            url: "/admin/reklamir/default/vk-load",
                            data: {_csrfbe: yii.getCsrfToken(),type:type},
                            success:  (data) => {
                                if (data.success){
                                    this.content[type] = {};
                                    let html = '';
                                    let i = 0;
                                    for(let item of data.items){
                                        this.content[type][i] = item;
                                        html +='<div data-index="'+i+'">';
                                        html += '<img src="'+item.img+'">';
                                        html +='<a style="display: block;" data-index="'+i+'" data-type="wall" data-role="btn_add">Использовать</a>';
                                        html +='</div>';
                                        i++;
                                    }
                                    this.vk_content.html(html);
                                    this.file.val('');

                                }
                            }
                        });
                    }
                });
            }


        }

        let add = new Add($('.reklamir-create'));
    });
</script>
