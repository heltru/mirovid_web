<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 11.01.2020
 * Time: 12:56
 */
use yii\helpers\Html;

?>
<div class="image-form row">
    <div class="col-xs-12">
        <h1>Preview LED</h1>
    </div>

    <div class="col-xs-12">
<?php
echo Html::beginForm('','',  ['enctype' => 'multipart/form-data']);
echo Html::label('Выберите файл');
echo Html::fileInput('file',null,['id'=>'file_input']);
echo Html::submitButton('Отправить',['id'=>'btn_send']);
echo Html::endForm();

?>
    </div>
</div>

<div class="link_div" style="margin-top: 1em;">

</div>

<script>
    $(document).ready( function (){


        $('#btn_send').click(function (e) {
            e.preventDefault();

            let $input = $("#file_input");
            let fd = new FormData;
            fd.append('file',$input[0].files[0]);

            $.ajax({
                url : "<?=\yii\helpers\Url::to(['upload-file'])?>",
                data: fd,
                processData: false,
                contentType: false,
                type: 'POST',
                success: function (data) {
                    if (typeof data == 'object' ){


                        let a = '<a target="_blank" href="'+data.link+'">'
                            +data.name+'</a>';
                        $('.link_div').append(a);


                    }
                }
            });


        });

    });
</script>
