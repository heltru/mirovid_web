<?php
$c = 0;

?>
<div class="row" >

    <?php
    for ($i=1;$i<=24;$i++){

        $tomorrow_a  = mktime(  $i-1, 0, 0);
        $tomorrow  = mktime(  $i, 0, 0);
        $hour_full = 'warning';

        ?>

        <?php

         if ( in_array($i,[1,7,13,19])){
             echo '<div class="col-xs-3">';
         }
        ?>

        <button class="bid_hour  btn btn-block btn-<?=$hour_full?> btn-sm" data-hour-num="<?=$i?>" type="button" >
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
