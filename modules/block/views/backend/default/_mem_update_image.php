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

                    <div class="panel box box-warning">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" class="sel_image">
                                    Мем картинка
                                </a>
                            </h4>
                        </div>
                        <div id="collapseOne" class="panel-collapse collapse  in" aria-expanded="true" style="">
                            <div class="box-body">
                                <div class="row cont_img" >

                                    <div class="col-xs-12">

                                        <h2>1. Выберите картинку</h2>
                                        <input id="file" type="file" />
                                        <h2>2. Выберите область показа</h2>
                                        <div id="jcrop"></div>
                                        <h2>Реальный размер</h2>
                                        <canvas data-oldurl="<?= '/'.$mem->content?>" id="canvas"></canvas>


                                    </div>
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
                                    <?=  $this->render('_mem_update_time',['mem'=>$mem,'form'=>$form]); ?>
                            </div>
                        </div>
                    </div>
                    <div class="panel box  primary_d_border-top">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour" class="collapsed" aria-expanded="false">
                                    Настроки района
                                </a>
                            </h4>
                        </div>
                        <div id="collapseFour" class="panel-collapse collapse" aria-expanded="false" >
                            <div class="box-body">
                                <?=  $this->render('_mem_update_locale',['mem'=>$mem,'form'=>$form]); ?>
                            </div>
                        </div>
                    </div>

                    <div class="panel box primary_c_border-top">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" class="collapsed" aria-expanded="false">
                                    Настроки привелигированных районов
                                </a>
                            </h4>
                        </div>
                        <div id="collapseFive" class="panel-collapse collapse" aria-expanded="false" >
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
        <?php echo Html::activeHiddenInput($mem,'content_update',['id'=>'content_update']) ?>
        <?php echo Html::activeHiddenInput($mem,'type',['id'=>'type_mem']) ?>
        <?php echo Html::activeHiddenInput($mem,'raw_data',['id'=>'raw_data']) ?>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>

    <div class="col-xs-12">
        <a class="btn btn-success btn_end" >Применить</a>

    </div>



</div>

<script>
    $(document).ready(function ( ) {

        var jcrop_api;
        var img;

        load_old_image();

        $("#file").change(function(){
            picture(this);
        });

        var picture_width;
        var picture_height;
        var crop_max_width = 512;
        var crop_max_height = 256;
        function picture(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#jcrop, #preview").html("").append("<img src=\""+e.target.result+"\" alt=\"\" />");
                    picture_width = $("#preview img").width();
                    picture_height = $("#preview img").height();
                    $("#jcrop  img").Jcrop({
                        onChange: canvas,
                        onSelect: canvas,
                        boxWidth: crop_max_width,
                        boxHeight: crop_max_height
                    },function(){
                        jcrop_api = this;

                        setRatio( '64_32' );
                    });
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function canvas(coords){
            var imageObj = $("#jcrop img")[0];
            var canvas = $("#canvas")[0];
            canvas.width  = coords.w;
            canvas.height = coords.h;
            var context = canvas.getContext("2d");
            context.drawImage(imageObj, coords.x, coords.y, coords.w, coords.h, 0, 0, canvas.width, canvas.height);
            png();
        }
        function png() {
            var png = $("#canvas")[0].toDataURL('image/png');
            $("#raw_data").val(png);
            $('#content_update').val(1);

        }


        $('.btn_end').click(function (e){


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
            $('#form').submit();
        });



        changeFiles = function (e) {

            /*var img = */
            if ( jcrop_api ){
                jcrop_api.destroy();
                $(img).removeAttr( 'style' );
                //   return;
            }
            img =  $(this).parent().find('.imgPrevUpload');

            //clearCont();
            var files = e.target.files; // FileList object

            // Loop through the FileList and render image files as thumbnails.
            for (var i = 0, f; f = files[i]; i++) {

                // Only process image files.
                if (!f.type.match('image.*')) {
                    continue;
                }
                var reader = new FileReader();

                // Closure to capture the file information.
                reader.onload = (function(theFile) {

                    return function (e) {

                        $(img).attr('src', e.target.result);


                        $(img).Jcrop({
                            onSelect: showCoords,
                            onChange: changeCoords,
                            boxWidth: 640, boxHeight: 320
                        },function(){
                            jcrop_api = this;

                            setRatio( '64_32' );
                        });


                    }

                })(f);
                reader.readAsDataURL(f);

            }
        };
        $('body').on('change','.imgFile',changeFiles );

        function setRatio(size) {
            var val = String( size ).split('_');

            var ratio = val[0] / val[1];
            if (jcrop_api) {

                jcrop_api.setOptions(
                    {
                        aspectRatio: ratio
                    });
            }
        }

        function showCoords(c) {
            /*
                        $(cont).find('.imgCropX').val( Math.floor( c.x ) );
                        $(cont).find('.imgCropY').val( Math.floor(c.y) );
                        $(cont).find('.imgCropWidth').val( Math.floor( c.w ) );
                        $(cont).find('.imgCropHeight').val( Math.floor( c.h) );
            */
        }

        function changeCoords(c) {
            /*
            $(cont).find('.imgCropX').val( Math.floor( c.x ) );
            $(cont).find('.imgCropY').val( Math.floor(c.y) );
            $(cont).find('.imgCropWidth').val( Math.floor( c.w ) );
            $(cont).find('.imgCropHeight').val( Math.floor( c.h) );
        */

        }

        function load_old_image() {

            var myCanvas = document.getElementById('canvas');
            var ctx = myCanvas.getContext('2d');

            var img_l = new Image;





            img_l.onload = function(){

                ctx.drawImage(img_l,0,0, 128*2,64*2); // Or at whatever offset you like
            };

            img_l.src = myCanvas.getAttribute('data-oldurl');
        }


});
</script>