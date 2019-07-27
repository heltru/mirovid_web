<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\block\models\BlockSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
use yii\widgets\Pjax;
$this->title = 'Blocks';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('/js/dti/dom-to-image.min.js',  ['position' => yii\web\View::POS_END]);
$this->registerJsFile('/js/jimp/jimp.js',  ['position' => yii\web\View::POS_END]);
$this->registerJsFile('/js/emojionearea/dist/emojionearea.js',  ['position' => yii\web\View::POS_END]);
$this->registerCssFile(

        '/js/emojionearea/dist/emojionearea.css',
        ['position' => yii\web\View::POS_END]);

?>

<div class="row">

    <div class="col-md-12 col-xs-12 new-rk ">

        <?php
        echo $this->render('_rk_form_new',['model'=>$form]);
        ?>

    </div>

    <div class="col-md-12 col-xs-12">
        <div class="box box-info grid-rk-user ">
            <div class="box-header with-border">
                <h3 class="box-title">РК</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <!-- -->
            <div class="box-body table-responsive ">
                <?php
                Pjax::begin([
                    'id'=>'blocks-grid-ajax',
                ]);
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],

                        //    'id',


                        [
                            'attribute'=>'name',
                            'format'=>'raw',
                            'value' => function($model) {
                                return '<a class="name-row">'.$model->name.'</a>';
                            },

                        ],
                        /*       [
                                   'attribute'=>'account_r.name',

                                   'value' => function($model) {
                                       return (is_object($model->cat_r) ) ?  $model->cat_r->name : '';
                                   },
                                   'filter' =>
                                       Html::dropDownList('ProductSearch[cat_id]',
                                           (isset(Yii::$app->request->queryParams['ProductSearch']['cat_id'] )) ?
                                               Yii::$app->request->queryParams['ProductSearch']['cat_id']  : '',
                                           \yii\helpers\ArrayHelper::map( Cat::find()->where([ '!=',  'parent_id',0])->all() ,'id','name') ,['prompt'=>'нет', 'class'=>'form-control']),

                               ],*/

                        [
                            'attribute'=>'status',
                            'format'=>'raw',
                            'value' => function($model) {
                                return \app\modules\block\models\Block::$arrTxtStatus[ $model->status] ;
                            },
                            'filter' => false

                        ],

                        //      'date_cr',
                        //     'type',

                        [
                            'label'=>'Операции',
                            'format'=>'raw',
                            'value'=>function ($model){
                                if (is_object($model)) {
                                    $ret = '<div class="btn-group" style="" role="group" aria-label="Операции">';
                                    $url = '';
                                    $ret .= ' <div class="btn-group" role="group">';
                                    $ret .= '<a href="'.$url.'" 
                                        title="Просмотреть" class="btn btn-default btn_view-rk" aria-label="Просмотреть" 
                                        data-id="'.$model->id.'" 
                                        data-pjax="0" 
                                         >
                                        <span class="glyphicon glyphicon-eye-open"></span></a>';
                                    $ret .= '</div>';


                                    $url = '';//\yii\helpers\Url::to(['badge/delete','id'=>$model->id]);
                                    $ret .= ' <div class="btn-group" role="group">';
                                    $ret .= '<a href="'.$url.'" 
                                        title="Удалить" class="btn btn-default remove-company" aria-label="Удалить" 
                                        data-id="'.$model->id.'" 
                                        data-pjax="0" 
                                       
                                         >
                                        <span class="glyphicon glyphicon-trash"></span></a>';
                                    $ret .= '</div>';



                                    $ret .= '</div>';
                                    return $ret;

                                }

                            }
                        ],
                    ],
                ]);
                Pjax::end();
                ?>
            </div>

        </div>

    </div>

    <div class="col-md-12  col-xs-12 view-rk">
    </div>





</div>
<script>
    $(document).ready(function () {

        var $rescont = null;
        var $textareacont = null;
        var res = null;
        var msg_update_state = false;
        var parent = null;


        var textareacontent_active = false;
        var id_textarea_old = null;

        var fl = false;
        var workOk = false;

        var $old_cont,$old_text_area = null;

        $('body').on('mouseenter', '.msg-item', function() {
/*
            $(this).find('.msg-edit').parent().hide();
            $(this).find('.rmv-msg').parent().hide();
*/
         /*   $(this).find('.rmv-msg').parent().show();
            $(this).find('.rmv-msg').parent().show();*/

            }
        );

        $('body').on('enterleave', '.msg-item', function() {

        /*
            $(this).find('.msg-edit').parent().show();
            $(this).find('.rmv-msg').parent().show();
*/
      /*      $(this).find('.msg-edit').parent().hide();
            $(this).find('.rmv-msg').parent().hide();*/
                //  console.log($(this).html());
                //   $('.flyout').show();
            }
        );

        function activeEditor($this,$cont) {

            workOk = true;


            $this.emojioneArea({
                container: $cont,
                hideSource: true
            });
            console.log('activeEditor', $this.emojioneArea);
            $old_cont = $cont;
            $old_text_area = $this;

        }

        function destroyEditor() {



            if ($old_cont !== undefined){
                $old_cont.html('');
                $old_text_area.show();

                workOk = false;
            }


        }

        $('body').on('focusin', '.textareacontent', function (e) {
            var id_textarea_curr = $(this).attr('id');
            console.log('focusin',id_textarea_curr,id_textarea_old,workOk);


        //    console.log(workOk,id_textarea_old,id_textarea_curr);

            if (id_textarea_old !== id_textarea_curr){ // иниц новый

                if (workOk){ // убираем старый
                    destroyEditor();
                }

                activeEditor( $(this),
                    $(this).parent().parent().find('.emjcont'));


            }





            id_textarea_old = id_textarea_curr;
        });

        $('body').on('focusout', '.textareacontent',   function (e) {
            var id_textarea_curr = $(this).attr('id');

    //       console.log('focusout',id_textarea_curr,id_textarea_old);
      //      id_textarea_old = $(this).attr('id');



        });



        $('body').on('click', '.msg-edit',  function (e) {

            e.preventDefault();
            parent = $(e.currentTarget).parent().parent();
            var $ta =    $(e.currentTarget).parent().parent().find('textarea');
            var $cont =  $(e.currentTarget).parent().parent().find('.emjcont');
            var $imgcont = $(e.currentTarget).parent().parent().find('.imgcont');
            if ($ta.length == 0 || $cont.length == 0) {
                console.log(-4);
                return;
            }



            if (msg_update_state === false){
                msg_update_state = true;
                res =  $ta.emojioneArea({
                    container: $cont,
                    hideSource: false
                });

                $cont.show();

                if ($(this).hasClass('btn-default')){
                    $(this).removeClass('btn-default');
                    $(this).addClass('btn-info');
                    $(e.currentTarget).find('.caption-btn').text('😄 emoji');
                }

            } else {
                msg_update_state = false;


                domtoimage.toPng(
                    e.currentTarget.parentElement.parentElement.getElementsByClassName('textareacontent')[1]
                )
                    .then(function (dataUrl) {
                        var img = new Image();
                        img.src = dataUrl;

                      var imgPixels = [];
                      Jimp.read(dataUrl).then(function (lenna) {

                          lenna.quality(100)
                                  .getBase64(Jimp.AUTO, function (err, src) {
                              parent.find('.img_preview_320_160').val(src);
                              console.log(src);
                          }).
                            scale(0.5).
                                contrast(1).
                          //  lenna.resize(128,64)      // resize
                            background(0x2d2f44)
                                     // set JPEG quality
                                .greyscale().              // set greyscale
                          getBase64(Jimp.AUTO, function (err, src) {
                              parent.find('.img_exp').val(src);
                              console.log(src);
                          })

                        });


                        //document.body.appendChild(img);
                    })
                    .catch(function (error) {
                        console.error('oops, something went wrong!', error);
                    });



                $cont.hide();

                if ($(this).hasClass('btn-info')){
                    $(this).removeClass('btn-info');
                    $(this).addClass('btn-default');
                   // $(this).parent().parent().find('.emjcont').html('');
                    $(e.currentTarget).find('.caption-btn').text('😄 emoji');
                }

            }



        });


        $('body').on('click', '.rmv-msg',  function (e) {
            var id_m = $(this).attr('id-msg');
            var id_b = $(this).attr('id-block');
            var $this = $(this);
            if (! ( id_m && id_b)) {   $this.parent().parent().remove(); return; }
            $.ajax({
                type: "POST",
                url: "<?= \yii\helpers\Url::to(['/admin/block/default/rmv-msg'])?>",
                data: {_csrfbe:yii.getCsrfToken(),id_m:id_m,id_b:id_b},
                success:function (data) {
                    if (typeof data == 'object'){
                        if (data.status == 'success'){

                            $.pjax.reload('#blocks-grid-ajax');
                            $('section.content').before(data.response);
                        //    console.log($this.parent().parent().parent().html());
                            $this.parent().parent().remove();
                        }
                        if (data.status == 'error'){

                            $('section.content').before(data.response)

                        }


                    }

                }
            });
        });


        $('body').on('click', '.btn_view-rk',  function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');


            $('#blocks-grid-ajax').find('.grid-rk-row-select').removeClass();

            var row = $(this).parent().parent().parent().parent();
            row.addClass('grid-rk-row-select');


            $.ajax({
                type: "POST",
                url: "<?= \yii\helpers\Url::to(['/admin/block/default/view-rk'])?>",
                data: {_csrfbe:yii.getCsrfToken(),id:id},
                success:function (data) {
                    $('.view-rk').html( data.response ).find('div.box.box-info').boxWidget();
                    $('html, body').animate({ scrollTop: $('.view-rk').offset().top }, 500);
                }
            });

        });


        $('body').on('click', '.remove-company',  function (e) {
            e.preventDefault();
            if( !confirm('Вы уверены, что хотите удалить этот элемент?')) {
                return false;
            }

            var id = $(this).attr('data-id');



            $.ajax({
                type: "POST",
                url: "<?= \yii\helpers\Url::to(['/admin/block/default/del-rk-company'])?>",
                data: {_csrfbe:yii.getCsrfToken(),id:id},
                success:function (data) {
                    if (typeof data == 'object'){
                        if (data.status == 'success'){

                            $.pjax.reload('#blocks-grid-ajax');
                            $('section.content').before(data.response)

                        }
                        if (data.status == 'error'){

                            $('section.content').before(data.response)

                        }

                    }


                }

            });
        });


        $('body').on('click', '.update-company',  function (e) {

            $('#rk_form_old').find('.textcontent').each(function(a,b,c){

                  $(b).val(
                      $(  $('#rk_form_old').find('textarea.textareacontent')[a]  ).val()
                  );

            });

            e.preventDefault();
            var id = $(this).attr('data-id');


        /*    $(this).parent().parent().find('.msg-container').
            find('.msg-item').each(function(a,b,c){
              //  console.log( $(  $('#rk_form_old').find('.textcontent')[a]  ).val());
                console.log(  $(b).find('input[type="hidden"]') );
             //   console.log( $(b).find(' input[type="hidden"]').val($(b).find('textarea').val()));
           //    $(b).find('.textcontent input[type="hidden"]').val($(b).find('textarea').val());
            });*/

            $.ajax({
                url:"<?= \yii\helpers\Url::to(['/admin/block/default/update-rk-ajax'])?>",
                type:"POST",
                data:{ form:$('form#rk_form_old').serialize(),  _csrfbe:yii.getCsrfToken()},
                success:function (data) {

                    if (typeof data == 'object'){
                        if (data.status == 'success'){

                            $.pjax.reload('#blocks-grid-ajax');
                          //  $('section.content').before(data.response)

                       //     $('.view-rk').html('');
                        }
                        if (data.status == 'error'){

                            $('section.content').before(data.response)

                        }

                    }

                },
                beforeSend:function (e){
                  $('.view-rk').find('div.box-body').before( '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>' ) ;
                },
                complete:function () {
                    $('.view-rk').find('.overlay').remove();
                }
            });

        });


        $('body').on('click', '.add-company',  function (e) {
            e.preventDefault();
            /*$('#rk_form_new .msg-item').each(function(a,b,c){
                 $(b).find('input[type="hidden"]').val(
                    $(b).find('textarea').val()
                );
            });*/
            $.ajax({
                url:"<?= \yii\helpers\Url::to(['/admin/block/default/add-new-rk-ajax'])?>",
                type:"POST",
                data:{ form:$('form#rk_form_new').serialize(),  _csrfbe:yii.getCsrfToken()},
                success:function (data) {
                    console.log(data);
                    if (typeof data == 'object'){
                        if (data.status == 'success'){

                            $.pjax.reload('#blocks-grid-ajax');
                            $('section.content').before(data.response.alert);

                            $('.new-rk').html(data.response.newform);


                        }
                        if (data.status == 'error'){

                            $('section.content').before(data.response.alert);

                        }

                    }

                },
                beforeSend:function (e){
                    $('.new-rk').find('div.box-body').before( '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>' ) ;
                },
                complete:function () {
                    $('.new-rk').find('.overlay').remove();
                }
            });
        });


        $('body').on('click', '.add-new-msg',  function (e) {
            e.preventDefault();

            $.ajax({
                url:"<?= \yii\helpers\Url::to(['/admin/block/default/add-new-msg-form'])?>",
                type:"POST",
                data:{num:$('.msg-item').length+1,  _csrfbe:yii.getCsrfToken()},
                success:function (data) {
                //    console.log(data);
                    if (typeof data == 'object'){
                        if (data.status == 'success'){

                           $(e.currentTarget).parent().parent().parent().find('.msg-container').append(

                                '<div class="col-xs-12 col-lg-3 col-md-6 msg-item">' + data.response + '</div>'
                            );

                        }
                        if (data.status == 'error'){

                            $('section.content').before(data.response)

                        }
                    }

                }
            });
        });

        $('body').on('click', '.name-row',  function (e) {
            $(this).parent().parent().find('.btn_view-rk').click();
        });


        $('#blocks-grid-ajax').on('click','tr',function (e) {
            /*
             $('#blocks-grid-ajax').find('.grid-rk-row-all-select').removeClass();

             var row = $(this);
             row.addClass('grid-rk-row-all-select');*/

        });

        $('.new-rk h3,.grid-rk-user h3').click(function () {

            $(this).parent().find('button').click();
        });

    });
</script>
