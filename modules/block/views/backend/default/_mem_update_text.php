<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 17.09.2018
 * Time: 1:33
 */
use yii\helpers\Html;
$imgSrc =  '/'. \app\modules\image\models\Img::NOIMG;
$imgClass = 'imgCropNew imgCropCommon';

$this->registerJsFile(
    '/js/jquery.Jcrop.min.js',
    ['position' => yii\web\View::POS_END]
);
$this->registerCssFile('/js/emojionearea/dist/emojionearea.css',['position' => yii\web\View::POS_END]);
$this->registerJsFile('/js/dti/dom-to-image.min.js',  ['position' => yii\web\View::POS_END]);
$this->registerCssFile('/css/jquery.Jcrop.css',['position' => yii\web\View::POS_END]);
?>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=68531ae1-9ce3-44cf-95f9-a2a922bf7358" type="text/javascript"></script>
<script src="/js/geodesy-master/latlon-spherical.js"></script>
<div class="row">
    <div class="col-md-12">
        <?php $form = \yii\widgets\ActiveForm::begin(['options'=>['id'=>'form',
            'method'=>'post',
            'action'=>\yii\helpers\Url::to(['/admin/block/default/msg-update','id'=>$mem->id])
        ]]) ?>
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Мем ID <?=$mem->id?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="box-group" id="accordion">
                    <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->


                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix" class="collapsed" aria-expanded="false">
                                    Настроки Мема
                                </a>
                            </h4>
                        </div>
                        <div id="collapseSix" class="panel-collapse collapse in" aria-expanded="false" >
                            <div class="box-body">
                                <?= $this->render('_mem_update_locale_common',['mem'=>$mem,'form'=>$form]); ?>
                            </div>
                        </div>
                    </div>


                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed sel_textemoji" aria-expanded="false">
                                    Мем текст
                                </a>
                            </h4>
                        </div>
                        <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="row cont_emji" style="padding: 2em;">
                                <div class="col-xs-12">

                                    <textarea id="emojiarea" cols="32" rows="6" ><?=$mem->getContentFormat()?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel box box-success">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="false">
                                    Настроки времени
                                </a>
                            </h4>
                        </div>
                        <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="box-body">
                                <?= $this->render('_mem_update_time',['mem'=>$mem,'form'=>$form]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel box primary_d_border">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour" class="collapsed" aria-expanded="false">
                                    Настроки района
                                </a>
                            </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                            <div class="box-body">
                                <?= $this->render('_mem_update_locale',['mem'=>$mem,'form'=>$form]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel box primary_c_border">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" class="collapsed" aria-expanded="false">
                                    Настроки привелигированных районов
                                </a>
                            </h4>
                        </div>
                        <div id="collapseFive" class="panel-collapse collapse in" aria-expanded="true" >
                            <div class="box-body">
                                <?= $this->render('_mem_update_locale_cost',['mem'=>$mem,'form'=>$form]); ?>
                            </div>
                        </div>
                    </div>

                </div>



            </div>



            <!-- /.box-body -->
        </div>
        <!-- /.box -->
        <?php echo Html::activeHiddenInput($mem,'type',['id'=>'type_mem']) ?>
        <?php echo Html::activeHiddenInput($mem,'raw_data',['id'=>'raw_data']) ?>
        <?php echo Html::activeHiddenInput($mem,'content_update',['id'=>'content_update']) ?>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>

    <div class="col-xs-12">
        <a class="btn btn-success btn_end" >Применить</a>

    </div>

<?php
//ex($mem->content);
?>


</div>

<script>
    $(document).ready(function ( ) {
      //  $("#emojiarea").emojioneArea({pickerPosition:'right'});

        var old_text = $("#emojiarea").val();

        $('#emojiarea').change(function (e) {


            if ($("#emojiarea").val() != old_text){
                $("#raw_data").val( $("#emojiarea").val() );
                $('#content_update').val(1);
            }

        });


        $('.btn_end').click(function (e){

            $('#form').submit();

          /*  if (state == 'text'){

                domtoimage.toPng(
                    document.getElementsByClassName('emojionearea-editor')[0]
                )
                    .then(function (dataUrl) {


                        var imgPixels = [];
                        Jimp.read(dataUrl).then(function (lenna) {

                            lenna.
                            quality(100).
                            resize(128,64, Jimp.RESIZE_BEZIER).
                            contrast(1).

                            background(0x000000).
                            // set JPEG quality
                            greyscale().              // set greyscale
                            getBase64(Jimp.AUTO, function (err, src) {

                                 $('#png').val(src);

                                $('#form').submit();

                            })

                        });


                        //document.body.appendChild(img);
                    })
                    .catch(function (error) {
                        console.error('oops, something went wrong!', error);
                    });
            }

            if (state =='image'){
                $('#form').submit();
            }
            */

        });





});
</script>