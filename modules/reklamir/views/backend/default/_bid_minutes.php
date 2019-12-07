<?php
$c = 1;

$account_id = Yii::$app->getModule('account')->getAccount()->id;

?>
<div class="row">
    <?php   foreach( [ [1,20],[21,40],[41,59] ]  as $minute_block ){
        $c_success = 0;
        for ($i = $minute_block[0]; $i <= $minute_block[1]; $i++) {
            if (isset($bid_minute[$i])) {
                if ($bid_minute[$i]->reklamir_r->account_id == $account_id) {
                    $c_success++;
                }
            }
        }
        ?>
        <div class="col-xs-12 col-md-4">
            <table class="responsive table table-bordered">
              <tbody>


                    <tr >
                        <th class="table_header">Бронь <br>
                            <?=\yii\helpers\Html::checkbox('sel_all',$c_success == 20,['class'=>'brone_all'] ); ?>
                            <span style="text-align: left;color:#b1b2b6">Выбрать все</span>
                        </th>
                        <th class="table_header">Время</th>
                        <th class="table_header">Ставка</th>
                        <th class="table_header">Твоя цена</th>
                        <th class="table_header">Выкупить<br> время</th>
                    </tr>


                <?php

                for ($i=$minute_block[0];$i<=$minute_block[1];$i++){


                    $tomorrow  = mktime( 0 , $i, 0);
                    $bid_status = 'btn-default';
                    $myval = 10;
                    $rewrite_price = false;
                    $disable_rewriteprice = false;
                    $bid_status_color = '';
                    $rewrite_val = 0;
                    $bid_id = 0;
                    $time_id = '';
                    if (isset($bid_minute[$i])){

                        $time_id = date( 'Y-m-d H:i:s', $bid_minute[$i]->time_id);

                        $myval = $bid_minute[$i]->val;

                        $bid_id  = $bid_minute[$i]->id;

                        if ($bid_minute[$i]->reklamir_r->account_id == $account_id){
                            $bid_status = 'btn-success';
                            $bid_status_color = 'bid_status_main';
                            $disable_rewriteprice = true;

                            if ($bid_minute[$i]->val > 10){
                                $bid_status = ' btn-info';
                            }

                        } else {
                            $bid_status = 'btn-danger';
                            $bid_status_color = 'bid_status_brone';
                            $rewrite_val = $myval +1;

                            if ($bid_minute[$i]->val === 99){
                                $disable_rewriteprice = true;
                            }

                        }

                        if ($bid_minute[$i]->val === 99){
                            $bid_status = ' bg-purple';
                        }


                    }

                    ?>
                    <tr>
                        <td>

                            <button

                                    data-bid-id="<?=$bid_id?>"
                                    type="button"
                                    class="btn btn-block  btn-xs bid_status
                                    <?=$bid_status?> ">Бронь</button>

                        </td>
                        <td >
                            <span  style="    margin-left: 6px;"  data-bid-minute="<?=$c?>" class="bidtime"><?= date("H:i",$tomorrow) ?></span>
                        </td>
                        <td>
                            <span class="rate"><?=$myval?></span> руб.
                        </td>
                        <td>
                            <?php  echo \yii\helpers\Html::textInput('my_val',$rewrite_val,
                                ['data-minute_id'=>$i,'class'=>'myval','step'=>'1','type'=>'number',
                                    'style'=>['width'=>'60px','margin-left'=>'15px;'],'disabled' => $disable_rewriteprice]) ?>
                            <?php
                            echo \yii\helpers\Html::checkbox('rewrite_price',
                                $rewrite_price,['class'=>'rewrite_price','style'=>'margin-left: 5px;','disabled' => $disable_rewriteprice])  ?>
                        </td>
                        <td>
                            <?php
                                if ( $bid_status !== ' bg-purple'){
                                    echo '<button class="btn btn-block btn-default btn-xs bg-purple rewrite-full">99 руб.</button>';
                                }
                            ?>

                        </td>

                    </tr>

                <?php $c++;  } ?>
              </tbody>
            </table>
        </div>
    <?php } ?>
</div>


<div class="row">
    <div class="col-md-12">
        <!-- The time line -->
        <ul class="timeline">
            <li>
                <button  type="button"  style="padding: 5px 5px;width: 5%;
    float: left;" class="btn btn-block  btn-xs
                                    btn-success ">Бронь</button>
                <div class="timeline-item" style="width: 93%;
    float: right;    margin-left: 0px">


                    <h3 class="timeline-header no-border">Ваше забронированное время</h3>
                </div>
            </li>
            <li>
                <button  type="button" style="padding: 5px 5px;width: 5%;
    float: left;" class="btn btn-block  btn-xs
                                    btn-info ">Бронь</button>
                <div class="timeline-item" style="width: 93%;
    float: right;    margin-left: 0px">
                    <h3 class="timeline-header no-border"> Время по моей цене</h3>
                </div>
            </li>
            <li>
                <button  type="button" style="padding: 5px 5px;width: 5%;
    float: left;" class="btn btn-block  btn-xs
                                    btn-default bg-purple">Бронь</button>
                <div class="timeline-item" style="width: 93%;
    float: right;    margin-left: 0px">
                    <h3 class="timeline-header no-border"> Время выкуплено</h3>
                </div>
            </li>
            <li>
                <button  type="button" style="padding: 5px 5px;width: 5%;
    float: left;" class="btn btn-block  btn-xs
                                    btn-default ">Бронь</button>

                <div class="timeline-item" style="width: 93%;
    float: right;    margin-left: 0px">
                    <h3 class="timeline-header no-border"> Время свободно</h3>
                </div>
            </li>
            <li>
                <button  type="button" style="padding: 5px 5px;width: 5%;
    float: left;"class="btn btn-block  btn-xs
                                    btn-danger ">Бронь</button>

                <div class="timeline-item" style="width: 93%;
    float: right;    margin-left: 0px">
                    <h3 class="timeline-header no-border"> Время занято</h3>
                </div>
            </li>



            <li>
                <i class="fa fa-question bg-aqua"></i>

                <div class="timeline-item">


                    <h3 class="timeline-header no-border">Чтобы перекупить время в столбце "твоя цена" укажи новую цену и поставь галочку.<br> В данном случае вы не можете отменить бронь.</h3>
                </div>
            </li>
            <li>
                <i class="fa fa-exclamation bg-yellow"></i>

                <div class="timeline-item">


                    <h3 class="timeline-header no-border">Если Вашу цену перекупают, на вашу электронную почту приходит письмо с уведомлением. <br>
                        Вы можете войти в личный кабинет и предложить новую цену.
                    </h3>
                </div>
            </li>
            <li>
                <i class="fa fa-diamond bg-purple"></i>
                <div class="timeline-item">
                    <h3 class="timeline-header no-border">
                        Чтобы полностью выкупить время нажмите на кнопку в столбце "выкупить время".<br> В этом случае, Вы на 100% бронируете данное время.
                        И никто не может его перекупить.
                    </h3>
                </div>
            </li>
        </ul>
    </div>
</div>
<script>


</script>

