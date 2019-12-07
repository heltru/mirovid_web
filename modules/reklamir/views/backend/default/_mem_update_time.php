<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 18.12.2018
 * Time: 8:08
 */
use app\modules\block\models\Msg;
use app\modules\reklamir\models\TimeArea;
$items = [];


//echo $form->field('times')->checkboxList([]);
$c = 1;

?>
<style>

    .date_time_cell {
        text-align: center;
        color: black;
        font-weight: bold;
        border-style: solid;
        border-width: 3px;
        border-color: #4CAF50;

    }

    .date_time_cell_active {
        background: #4CAF50;
    }


    .day_cell {
        text-align: center;
        color: black;
        font-weight: bold;
        border-style: solid;
        border-width: 3px;
        border-color: #8bc34a;

    }

    .day_cell_active {
        background: #8bc34a;
    }
    .day_cell_red {
        border-color: #f44336;
    }



</style>
<div class="table table-bordered daytime_table">


<div class="row" style=" overflow-x: auto;
    white-space: nowrap;">
        <?php foreach ( TimeArea::$days_show as $num => $day )  { ?>
            <div class="col-xs-6 col-md-2">
                <div style=" "><p data-id="<?=$num?>" class="day_cell "><?=$day?></p></div>


                <?php foreach ( TimeArea::$times_show as $time )  { $sun_day =   $num == 6 || $num == 7  ? 'day_cell_red'  : '' ?>
                    <div>
                        <div style="white-space: nowrap;"><input type="checkbox" data-daynum="<?=$num?>" data-id="<?=$c?>" class="date_time_cell  <?= $sun_day?>">
                        <span><?=  $time[0] .'-'. $time[1]  ?>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        </div>
                    </div>
                <?php  $c ++;  } ?>



            </div>
        <?php  } ?>
</div>




</div>
<?php echo $form->field($model,'times')->hiddenInput(['id'=>'sel_times'])->label(false) ?>

<script>
    $(document).ready( function (){

        $('body').on('click','.date_time_cell',function (e) {
            var $cell =  $(this);

            if ( $cell.hasClass('date_time_cell_active')){
                $cell.removeClass('date_time_cell_active');
            } else {
                $cell.addClass('date_time_cell_active');
            };

            save_times();
        }) ;

        $('.day_cell').click( function (e) {
            var num_day = $(this).attr('data-id');


            var active = $(this).hasClass('day_cell_active');

            $('.daytime_table').find("input[data-daynum='"+num_day+"']").each (function(i,v) {
                if (active){
                    $(this).removeClass('date_time_cell_active');
                    $(this).prop('checked', false);

                } else {
                    $(this).addClass('date_time_cell_active');
                    $(this).prop('checked', true);
                };

            });


            if (active){
                $(this).removeClass('day_cell_active');
            } else {
                $(this).addClass('day_cell_active');
            };

            save_times();

        } );




        upload_save_times();
        check_days_and_active();

        function upload_save_times(){
            var ids = $('#sel_times').val().split(',');

            if (ids[0] === ''){
                ids = [];
            }


            for (var index = 0; index < ids.length; ++index) {

                $('.daytime_table').find("input[data-id='" + ids[index] + "']").addClass('date_time_cell_active');
                $('.daytime_table').find("input[data-id='" + ids[index] + "']").prop('checked', true);
            }


            if (ids.length == 0){
                console.log('activeaa all table');
                all_active_table();
            }
        }

        function check_days_and_active(){
            $('.daytime_table').find('th').each (function(i,v) {
                let  num_day = i + 1;



                let tot_count = $('.daytime_table').find("input[data-daynum='"+num_day+"']").length;
                let loc_count = 0;

                $('.daytime_table').find("input[data-daynum='"+num_day+"']").each (function(i,v) {

                    if ($(this).hasClass('date_time_cell_active')){
                        loc_count ++;
                    }
                    //
                });

                if (loc_count === tot_count){
                    let cell = $(this);
                    let active = cell.hasClass('day_cell_active');
                    if ( ! active){
                        cell.addClass('day_cell_active');
                    }

                }

            });

        }

        function all_active_table() {
            console.log('all_active_table');
            $('.day_cell').each(function () {
                $(this).click();
                console.log('click');
            });
        }



        function save_times(){
            var ids  = [];
            $('.date_time_cell_active').each(function (){

                ids.push( $(this).attr('data-id')  );
            });

            $("#sel_times").val(ids.join(','));

        }



    });
</script>
