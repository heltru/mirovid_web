<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 18.03.2018
 * Time: 5:51
 */

?>
<div class="form-group">
    <a class="btn  btn-default btn-xs rmv-msg" id-block="<?= $model->block_id ?>"
       id-msg="<?=$model->id?>" title="Удалить">
        <span class="glyphicon glyphicon-remove"></span>
        <span class="caption-btn">удалить</span>

    </a>
</div>

<div class="form-group">

    <div class="imgcont" style="display: none"></div>
</div>

<div class="form-group">
    <div class="emjcont"></div>
    <?= $form->field($model, "[$num]content")->textarea( [

      //  'style'=>'width:320px;height:160px',
                    'class'=>'form-control textareacontent',
                'placeholder'=>'',
                /*'pluginOptions'=>[
                        'tones'=>false,
                    'filtersPosition'=>'false',
                    'searchPosition'=>false
                ]*/


    ])->label(false); ?>
</div>

<div class="form-group">
    <?= $form->field($model , "[$num]content")->hiddenInput([
        'maxlength' => true,
     'value'=> $model->content,
        'class' =>  'textcontent'
     //   'template' => '<div class="form-group"><label class="control-label">{label}</label>{input}</div>'
    ])->label(false) ?>

    <?= $form->field($model , "[$num]img_preview_320_160")->hiddenInput([
        'value'=> $model->img_preview_320_160,
           'class'=>'img_preview_320_160'
    ])->label(false) ?>

    <?= $form->field($model , "[$num]img_exp")->hiddenInput([
        'value'=> $model->img_exp,
        'class'=>'img_exp'
    ])->label(false) ?>

    <?php
    if ($model->id){

        echo $form->field($model, "[$num]id")->hiddenInput([ 'class' =>  ''] )->label(false);
    }
    ?>

</div>

<div class="form-group">


    <a class="btn  btn-default btn-xs  msg-edit">

        <span class="caption-btn">😄 emoji <?=$model->id?></span>
    </a>


</div>

