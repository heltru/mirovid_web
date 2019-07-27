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
<style>



    .emoji {
        font-family: "Segoe UI Emoji";
    }

    #preview {
        background: red;
        border: 1px solid green;
    }
    /* jquery.Jcrop.min.css v0.9.12 (build:20130126) */
    .jcrop-holder{direction:ltr;text-align:left;}
    .jcrop-vline,.jcrop-hline{background:#FFF url(Jcrop.gif);font-size:0;position:absolute;}
    .jcrop-vline{height:100%;width:1px!important;}
    .jcrop-vline.right{right:0;}
    .jcrop-hline{height:1px!important;width:100%;}
    .jcrop-hline.bottom{bottom:0;}
    .jcrop-tracker{-webkit-tap-highlight-color:transparent;-webkit-touch-callout:none;-webkit-user-select:none;height:100%;width:100%;}
    .jcrop-handle{background-color:#333;border:1px #EEE solid;font-size:1px;height:7px;width:7px;}
    .jcrop-handle.ord-n{left:50%;margin-left:-4px;margin-top:-4px;top:0;}
    .jcrop-handle.ord-s{bottom:0;left:50%;margin-bottom:-4px;margin-left:-4px;}
    .jcrop-handle.ord-e{margin-right:-4px;margin-top:-4px;right:0;top:50%;}
    .jcrop-handle.ord-w{left:0;margin-left:-4px;margin-top:-4px;top:50%;}
    .jcrop-handle.ord-nw{left:0;margin-left:-4px;margin-top:-4px;top:0;}
    .jcrop-handle.ord-ne{margin-right:-4px;margin-top:-4px;right:0;top:0;}
    .jcrop-handle.ord-se{bottom:0;margin-bottom:-4px;margin-right:-4px;right:0;}
    .jcrop-handle.ord-sw{bottom:0;left:0;margin-bottom:-4px;margin-left:-4px;}
    .jcrop-dragbar.ord-n,.jcrop-dragbar.ord-s{height:7px;width:100%;}
    .jcrop-dragbar.ord-e,.jcrop-dragbar.ord-w{height:100%;width:7px;}
    .jcrop-dragbar.ord-n{margin-top:-4px;}
    .jcrop-dragbar.ord-s{bottom:0;margin-bottom:-4px;}
    .jcrop-dragbar.ord-e{margin-right:-4px;right:0;}
    .jcrop-dragbar.ord-w{margin-left:-4px;}
    .jcrop-light .jcrop-vline,.jcrop-light .jcrop-hline{background:#FFF;filter:alpha(opacity=70)!important;opacity:.70!important;}
    .jcrop-light .jcrop-handle{-moz-border-radius:3px;-webkit-border-radius:3px;background-color:#000;border-color:#FFF;border-radius:3px;}
    .jcrop-dark .jcrop-vline,.jcrop-dark .jcrop-hline{background:#000;filter:alpha(opacity=70)!important;opacity:.7!important;}
    .jcrop-dark .jcrop-handle{-moz-border-radius:3px;-webkit-border-radius:3px;background-color:#FFF;border-color:#000;border-radius:3px;}
    .solid-line .jcrop-vline,.solid-line .jcrop-hline{background:#FFF;}
    .jcrop-holder img,img.jcrop-preview{max-width:none;}
    .btn-primary {
        border-color: #e65100;
        background-color: #e65100;
    }
    .btn-primary:hover {
        border-color: #e65100;
        background-color: #e65100;
    }

</style>
<div class="box box-warning grid-rk-user ">
    <div class="box-header with-border">
        <h3 class="box-title">Добавить Мем</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>
    </div>
    <!-- -->
    <div style="overflow-x: hidden;" class="box-body table-responsive ">

            <?php \yii\widgets\ActiveForm::begin(['options'=>['id'=>'form',
                'method'=>'post',
                'action'=>\yii\helpers\Url::to(['/admin/block/default/view','id'=>$block->id])
            ]]) ?>
        <div class="row" style="padding: 2em;">
            <div class="col-xs-12">
                <a class="btn btn-default sel_image" data-mod="cont_img">Картинка</a>
                <span style="padding: 1em">
                <a class="btn btn-primary sel_textemoji"  data-mod="cont_emji">Сообщение</a>

            </div>

        </div>
        <!---->
        <div class="row cont_img" style="padding: 2em;">

            <div class="col-xs-12">



                    <h2>Выберите картинку</h2>
                    <input id="file" type="file" />
                    <h2>Выберите область показа</h2>
                    <div id="jcrop"></div>
                    <h2>Реальный размер</h2>
                    <canvas id="canvas"></canvas>
                    <input id="png"  type="hidden" />




            </div>


        </div>


        <div class="row cont_emji" style="padding: 2em;">
            <div class="col-xs-12">
                <textarea id="emojiarea" cols="32" rows="6" ></textarea>
                <span id="counter_word"></span>
            </div>
        </div>

        <div class="row cont_canvas " style="padding: 2em;">
            <div class="col-xs-12">

            </div>
        </div>


        <div class="row cont_end" style="padding: 2em; margin-top: 2em">
            <div class="col-xs-12">
                <a class="btn btn-success btn_end" >Добавить</a>

            </div>
        </div>

            <?php echo Html::activeHiddenInput($mem,'type',['id'=>'type_mem']) ?>
            <?php echo Html::activeHiddenInput($mem,'content',['id'=>'content']) ?>

       <?php \yii\widgets\ActiveForm::end(); ?>

    </div>
        <!---->




</div>
<script>
    $(document).ready(function ( ) {



      //  $("#emojiarea").emojioneArea({pickerPosition:'right'});

        var count_words = 0;

        $('#emojiarea').keyup( function (e){
            count_words = parseInt( $(this).val().length);
            $('#counter_word').text( $(this).val().length  );


            if (! checkLength()){
                e.preventDefault();
                e.stopPropagation();
                e.cancelBubble=true;
                console.log('return FALSE');
                return false;
            } else {


                console.log('change');

            }

        } );

        $('#emojiarea').change( function (e){

            console.log('change');
            count_words = parseInt( $(this).val().length);
            if (! checkLength()){
                e.preventDefault();
                e.stopPropagation();
                e.cancelBubble=true;
                console.log('return FALSE');
                return false;
            } else {
                $('#counter_word').text( $(this).val().length  );

            }

        } );

        function checkLength() {
            var lmit = 150;
            console.log(count_words,count_words <= lmit);
            return (count_words <= lmit);
        }


        var jcrop_api;
        var img;

        var state = 'text'; //'text'

        $('.cont_img').hide();
        $('.cont_emji').show();


        $('#type_mem').val( "<?= \app\modules\block\models\Msg::T_T ?>" );

        $('.sel_textemoji').click(function (e) {
            state = 'text';
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');
            $('.sel_image').removeClass('btn-primary');
            $('.sel_image').addClass('btn-default');

            $('.cont_img').hide();
            $('.cont_emji').show();

            $('#type_mem').val( "<?= \app\modules\block\models\Msg::T_T ?>" );


        });


        $('.sel_image').click(function (e) {
            state = 'image';
            $(this).addClass('btn-primary');
            $(this).removeClass('btn-default');
            $('.sel_textemoji').removeClass('btn-primary');
            $('.sel_textemoji').addClass('btn-default');

            $('.cont_img').show();
            $('.cont_emji').hide();

            $('#type_mem').val( "<?= \app\modules\block\models\Msg::T_I ?>" );
        });



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
            $("#content").val(png);
        }


        $('.btn_end').click(function (e){
            console.log(state);
            if (state == 'text'){

                $("#content").val( $("#emojiarea").val() );
            }

            console.log($("#content").val(),state);


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


});
</script>