<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\reklamir\models\Reklamir;
use app\modules\helper\models\Helper;

/* @var $this yii\web\View */
/* @var $model app\modules\reklamir\models\Reklamir */
/* @var $form yii\widgets\ActiveForm */
//$this->registerJsFile('/js/jquery.Jcrop.min.js', ['position' => yii\web\View::POS_END]);
$this->registerJsFile('/plugins/jcrop/dist/jcrop.js', ['position' => yii\web\View::POS_END]);

//$this->registerCssFile('/css/jquery.Jcrop.css',['position' => yii\web\View::POS_END]);
$this->registerCssFile('/plugins/jcrop/dist/jcrop.css',['position' => yii\web\View::POS_END]);
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
    $options = [];
    $cats = ArrayHelper::map(\app\modules\reklamir\models\ThingCat::find()->all(), 'id', 'name');
    $places = ArrayHelper::map(\app\modules\reklamir\models\Place::find()->all(), 'id', 'name');
    $things = \app\modules\reklamir\models\Thing::find()->all();
    $things_list = [];
    foreach ($things as $thing){
        $things_list[$thing->id] = $thing->name;
        $options[$thing->id] = [
                'data-place_id'=>$thing->place_id,
                'data-cat_id'=>$thing->cat_id,
                'data-width'=>$thing->width,
                'data-height'=>$thing->height,
        ];
    }





    ?>
    <p>Выберите источник</p>

    <div class="box-group" id="accordion">
        <div class="panel box box-primary" style="margin-bottom: 0">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#place" class="collapsed"
                       aria-expanded="true">
                        Расположение
                    </a>
                </h4>
            </div>
            <div id="place" class="panel-collapse collapse " aria-expanded="true">
                <div class="box-body">
                    <?php
                    echo $form->field($model, 'thing_cat')->dropDownList($cats, ['prompt' => '- Сделай выбор -']);

                    echo $form->field($model, 'place_id')->dropDownList($places,['prompt' => '- Сделай выбор -']);


                    echo $form->field($model, 'thing_id')
                        ->dropDownList($things_list,
                            ['prompt' => '- Сделай выбор -',
                                'options' => $options
                            ]
                        );
                    ?>
                </div>
            </div>
        </div>
        <div class="panel box box-primary" style="margin-bottom: 0">
            <div class="box-header with-border">
                <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseVK" class="collapsed"
                       aria-expanded="true">
                        ВКонтакте
                    </a>
                </h4>
            </div>
            <div id="collapseVK" class="panel-collapse collapse " aria-expanded="true">
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
            <div id="collapseFile" class="panel-collapse collapse in" aria-expanded="false">
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
        <img id="preview_img" data-role="preview_img" >
        <img id="preivew_img_cropped">

        <input type="hidden" name="img_crop_name">
        <input type="hidden" name="img_crop">

        <input type="hidden" data-role="img_download" name="img_download" value="">
        <input type="hidden" data-role="type" name="type" value="">
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script>
    $(document).ready(function () {

        class Add {
            oninit(e){
                console.log(e,this);
            }
            constructor(el) {
                console.log('test');
                this.form = el.find('#w0');
                this.vk_type = this.form.find('select[name="vk_type"]');
                this.vk_content = this.form.find('[data-role="vk_content"]');
                this.preview = this.form.find('div[data-role="preview"]');
                this.preview_img = this.form.find('img[data-role="preview_img"]');
                this.preview_img_cropped = this.form.find('#preivew_img_cropped');
                this.preview_type = this.form.find('[data-role="type"]');
                this.preview_img_download = this.form.find('[data-role="img_download"]');
                this.img_crop = this.form.find('[name="img_crop"]');
                this.img_crop_name = this.form.find('[name="img_crop_name"]');

                this.file = this.form.find('#reklamir-uploadfile');
                this.select_cat = this.form.find('#reklamir-thing_cat');
                this.select_thing = this.form.find('#reklamir-thing_id');
                this.select_place = this.form.find('#reklamir-place_id');

                this.content = {
                    wall:{}
                };
                this.thing = {height:null,width:null,ratio:1600/900};
                this.jcrop = null;
                this.jcrop_options = {};

                this.select_thing.change((e)=> {
                    let thing_id = $(e.currentTarget).val();
                    let option = this.select_thing.find( 'option[value="'+thing_id+'"]');
                    let width = option.attr('data-width');
                    let height = option.attr('data-height');
                    this.thing.width = width;
                    this.thing.height = height;
                    this.thing.ratio = width/height;
                    this.jcrop_options = {aspectRatio: this.thing.ratio};
                    if (this.jcrop){
                        this.jcrop.setOptions({ aspectRatio: this.thing.ratio});
                    }
                });

                this.select_cat.change((e)=>{

                    let cat_id = $(e.currentTarget).val();
                    let place_id = this.select_place.val();

                    if (place_id){
                        this.select_thing.find('option').hide();
                        this.select_thing.find(
                            'option[data-place_id="'+place_id+'"]option[data-cat_id="'+cat_id+'"]').show();
                    } else {
                        this.select_thing.find('option').hide();
                        this.select_thing.find('option[data-cat_id="'+cat_id+'"]').show();
                    }



                });
                this.select_place.change((e)=>{

                    let place_id = $(e.currentTarget).val();
                    let cat_id = this.select_cat.val();
                    if (cat_id){
                        this.select_thing.find('option').hide();
                        this.select_thing.find(
                            'option[data-place_id="'+place_id+'"]option[data-cat_id="'+cat_id+'"]').show();
                    } else {
                        this.select_thing.find('option').hide();
                        this.select_thing.find('option[data-place_id="'+place_id+'"]').show();
                    }
                });


                this.file.change( (e) => {

                    if (this.file.prop('files') && this.file.prop('files')[0]) {

                        this.img_crop_name.val(e.target.files[0].name);
                        let reader = new FileReader();
                        reader.onload = (e_reader) => {



                            console.log( this.preview_img);
                           this.preview_img.attr('src',e_reader.target.result);
                           if (this.jcrop){
                               this.jcrop.destroy();
                           }

                           this.jcrop = Jcrop.attach('preview_img',{aspectRatio:this.thing.ratio});

                           this.jcrop.listen('crop.change',(widget,e)=>{
                                const pos = widget.pos;
                                console.log(pos.x,pos.y,pos.w,pos.h);

                               let canvas = document.createElement("canvas");
                               let context = canvas.getContext('2d');

                               canvas.width  = pos.w;
                               canvas.height = pos.h;

                               let imageObj = new Image();
                               imageObj.src = this.preview_img.attr('src');

                               context.drawImage(imageObj,
                                   pos.x, pos.y,
                                   pos.w, pos.h,

                                   0, 0,
                                   pos.w, pos.h
                               );

                               this.preview_img_cropped.attr('src',canvas.toDataURL());
                               this.img_crop.val(canvas.toDataURL());

                            });

                           this.preview_img.show();



                        }
                        reader.readAsDataURL(this.file.prop('files')[0]);
                    }
                });

                this.vk_content.on('click','[data-role="btn_add"]', (e) => {
                    this.file.val('');
                    let index = $(e.currentTarget).attr('data-index');
                    let type = $(e.currentTarget).attr('data-type');
                    if (type === 'wall'){
                        let content =  this.content[type][index];
                        if (content.type === 'img'){
                           //  let html = '<img id="preview_img" style="max-height: 200px;" src="'+content.img+'">';
                           //  html += '<input type="hidden" name="type" value="'+content.type+'">';
                           //  html += '<input type="hidden" name="img_download" value="'+content.img_download+'">';
                           // this.preview.html(html);

                             this.preview_img.attr('src',content.img);
                             this.preview_type.val(content.type);
                             this.preview_img_download.val(content.img_download);

                            $([document.documentElement, document.body]).animate({
                                scrollTop: this.preview.offset().top
                            },500);
                            //
                            // if (this.jcrop){
                            //     this.jcrop.destroy();
                            // }
                            //
                            // this.jcrop = Jcrop.attach('preview_img',{aspectRatio:this.thing.ratio});
                            //
                            // this.jcrop.listen('crop.change',(widget,e)=>{
                            //     const pos = widget.pos;
                            //     console.log(pos.x,pos.y,pos.w,pos.h);
                            //
                            //     let canvas = document.createElement("canvas");
                            //     let context = canvas.getContext('2d');
                            //
                            //     canvas.width  = pos.w;
                            //     canvas.height = pos.h;
                            //
                            //     let imageObj = new Image();
                            //     imageObj.src = this.preview_img.attr('src');
                            //
                            //     context.drawImage(imageObj,
                            //         pos.x, pos.y,
                            //         pos.w, pos.h,
                            //
                            //         0, 0,
                            //         pos.w, pos.h
                            //     );
                            //
                            //     console.log(canvas.toDataURL());
                            //    // this.preview_img_cropped.attr('src',canvas.toDataURL());
                            // //    this.img_crop.val(canvas.toDataURL());
                            //
                            // });
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
                                        html += '<img style="max-height: 200px;" src="'+item.img+'">';
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
