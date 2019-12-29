<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 19.11.2018
 * Time: 22:03
 */

use yii\helpers\Html;

$this->title = 'Баланс';


?>

<div class="row">
    <div class="col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-rub"></i></span>

            <div class="info-box-content">
                <br>
                <span class="info-box-number"><?= Yii::$app->getModule('balance')->getBalance() ?></span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-xs-12">





        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title">Быстрый платеж</h3>
            </div>

            <!-- form start -->
            <?php
        echo Html::beginForm('/admin/pay/default/make-form-pay','post',['role'=>'form']);?>

                <div class="box-body">
                    <div class="form-group">
                        <label  >Сумма пополнения</label>
                        <div class="input-group">


                            <?php  echo Html::textInput('summ',null,['class'=>'form-control','placeholder'=>'Введите сумму']); ?>
                        </div>


                    </div>
                    <div class="form-group">
                        <div class="radio">
                            <label>
                                <input type="radio" name="paymentType"   value="PC" >
                                Яндекс.Деньгами
                            </label>
                        </div>
                        <div class="radio">
                            <label>
                                <input type="radio" name="paymentType"  checked value="AC">
                                Банковской картой
                            </label>
                        </div>

                    </div>

                </div>
                <!-- /.box-body -->

                <div class="box-footer">
                    <?php
                    echo Html::submitButton( Html::img('/images/ya-kassa.png',['style'=>'width: 150px; height: 60px;']) ,['style'=>' padding: 0;border: none;'] );
                    ?>

                </div>
          <?php   echo Html::endForm(); ?>
        </div>



    </div>
</div>
