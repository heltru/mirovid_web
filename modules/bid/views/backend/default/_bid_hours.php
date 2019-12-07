<?php
$c = 0;

?>
<div class="row" >

    <?php
    for ($i=1;$i<=24;$i++){

        $tomorrow_a  = mktime(  $i-1, 0, 0);
        $tomorrow  = mktime(  $i, 0, 0);
        $hour_status = '';
        $hour_full = 'warning';
        if (isset($bid_hour[$i])){
            $hour_status = 'bid_hour_my';
        }
        /*
        if (count($bid) >= 60){
            $hour_full = 'success';
        }*/
        ?>

        <?php

         if ( in_array($i,[1,7,13,19])){
             echo '<div class="col-xs-3">';
         }
        ?>

        <button class="bid_hour <?=$hour_status?>  btn btn-block btn-<?=$hour_full?> btn-sm" data-hour-num="<?=$i?>" type="button" >
            <?= date("H:i",$tomorrow_a) ?> -
            <?= date("H:i",$tomorrow) ?>
        </button>

        <?php

        if ( in_array($i,[6,12,18,24])){
            echo' </div>';
        }

    }
        ?>




</div>
