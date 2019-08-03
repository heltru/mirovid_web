<?php
use yii\widgets\ActiveForm;
?>

    <p>Exsist Video:</p>
<?php
foreach ($video as $name ){
    echo "<span class='video_item'>$name </span>". '</br>';
}
?>

<br>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'videoFile')->fileInput() ?>

    <button>Submit</button>

<?php ActiveForm::end() ?>

<br>

<?=\yii\helpers\Html::a('update files to car',['/admin/api/default/update-files-car'])?>

<script>
    $(document).ready(function () {
        $('.video_item').click(function (){
            var name = $(this)
            $.ajax({
                type: "POST",
                url: "/admin/api/default/delete-video",
                data: {_csrfbe:yii.getCsrfToken(),name:name.html()},
                success:function (data) {
                    if (typeof data == 'object'){
                        if (data.status == 'success'){
                            $('section.content').before(data.response)
                            name.remove()
                        }
                        if (data.status == 'error'){
                            $('section.content').before(data.response)
                        }
                    }
                }
            });
        });
    });
</script>
