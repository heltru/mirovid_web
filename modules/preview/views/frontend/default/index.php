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
    <?php
    if ($link){
        echo Html::a('Файл готов ' . $id ,'/'.$link,['target'=>'_blank']);
    }
    if ($id && ! $link){
        echo '<p>Загружено, задание #'.$id.' ожидайте обработки 1-2 мин</p>';
    }
    ?>
</div>

<script>
    $(document).ready( function (){

        var id = "<?=$id?>";
        var link = "<?=$link?>";
        console.log(id,link);

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

/*
                        let a = '<a target="_blank" href="'+data.link+'">'
                            +data.name+'</a>';*/
                        $('.link_div').append('<p>Загружено, ожидайте обработки 1-2 мин</p>');
                        console.log( data.link);
                        setTimeout(function (){

                            document.location.href = data.link;
                        }, 3000);

                    }
                }
            });

        });

        if (id > 0 && link.length <= 0){
            setTimeout(function (){document.location.reload(true)}, 3000);
        }

    });
</script>
