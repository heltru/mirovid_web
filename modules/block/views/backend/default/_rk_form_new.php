<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 18.03.2018
 * Time: 5:26
 */
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use app\modules\app\app\RkNewForm;
$style_border = 'warning';
$type_old  = (boolean) ($model->getScenario() == RkNewForm::SCENARIO_OLD );
$style_border = ($type_old ) ?
    'info'
    :
    'success collapsed-box';
?>

<div   class="box box-<?=$style_border?>">
    <div class="box-header with-border ">
        <h3 class="box-title"><?php
            echo   ($type_old ) ?
                'Обзор #'.$model->block->name
                :
                'Новая РК'
            ?></h3>

        <?php
        echo   ($type_old ) ?
            ' <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
            </button>
        </div>'
            :
            '  <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
            </button>
        </div>  ';
        ?>

    </div>
    <!-- -->



    <div class="box-body " >

        <?php
        $form = ActiveForm::begin(['id'=>
            ($type_old ) ?
                'rk_form_old'
                :
                'rk_form_new'
        ]);
        ?>


        <?php
        if ($model->block->id){
            echo $form->field($model->block, 'id')->hiddenInput([   'maxlength' =>  true ])->label(false);
        }
        ?>



        <?php
        $strEcho = ($type_old ) ?
            'Название РК'
            :
            'Название новой РК';

        echo  $form->field($model->block, 'name')->textInput([   'maxlength' =>  true ])

        ?>




        <div class="row msg-container form-group">
            <?php

            $i = 1;
            foreach (

                ($type_old ) ?
                    $model->msg
                    :
                    $model->newmsg

                as
                $num => $msg
            ){   ?>
                <div class="col-xs-12 col-lg-4 col-md-6 msg-item">
                    <?php echo $this->render('_msg',['model'=>$msg,'form'=>$form,'num'=>$num,'i'=>$i]); ?>
                </div>
            <?php
                $i ++;
            }
            ?>
        </div>
        <div class="row ">
            <div class="col-xs-12  ">
                <a class="btn btn-default add-new-msg">
                    + сообщение
                </a>
            </div>
        </div>





    </div>

    <div class="box-footer">
        <?= Html::submitButton(
            ($type_old ) ?
                'Сохранить'
                :
                'Добавить'
            , [
            'class' => ($type_old ) ?
                'btn btn-info update-company' :
                'btn btn-success add-company',
            'data-id'=> ($type_old ) ? $model->block->id : 0
        ]) ?>

    </div>

    <?php ActiveForm::end(); ?>

</div>
