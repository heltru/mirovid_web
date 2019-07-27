<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 18.12.2018
 * Time: 8:08
 */
use app\modules\block\models\Msg;
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
<table class="table table-bordered daytime_table">
    <tbody>

    <tr>
        <?php foreach ( \app\modules\block\models\Msg::$days_show as $num => $day )  { ?>
            <th><p data-id="<?=$num?>" class="day_cell "><?=$day?></p></th>
        <?php  } ?>
    </tr>

    <?php foreach ( \app\modules\block\models\Msg::$times_show as $time )  { ?>
    <tr>
        <?php foreach ( \app\modules\block\models\Msg::$days_show as $num =>  $day )  { $sun_day =   $num == 6 || $num == 7  ? 'day_cell_red'  : '' ?>
        <td><p data-id="<?=$c?>" class="date_time_cell  <?= $sun_day?>"><?= $time[0].'<br>'.$time[1] ?></td></p>
        <?php  $c ++;  } ?>

    </tr>
    <?php  } ?>

    </tbody>
</table>
<?php echo $form->field($mem,'times')->hiddenInput(['id'=>'sel_times']) ?>

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

            $('.daytime_table').find('td:nth-child('+num_day+')').each (function(i,v) {
                var $cell =  $(this).find('p');
                if (active){
                    $cell.removeClass('date_time_cell_active');
                } else {
                    $cell.addClass('date_time_cell_active');
                };
                //
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

                $('.daytime_table').find('td').find("p[data-id='" + ids[index] + "']").addClass('date_time_cell_active');
            }


            if (ids.length == 0){
                console.log('activeaa all table');
                all_active_table();
            }
        }

        function check_days_and_active(){
            $('.daytime_table').find('th').each (function(i,v) {
                let  num_day = i + 1;

                let tot_count = $('.daytime_table').find('td:nth-child('+num_day+')').length;
                let loc_count = 0;

                $('.daytime_table').find('td:nth-child('+num_day+')').each (function(i,v) {

                    var $cell =  $(this).find('p');

                    if ($cell.hasClass('date_time_cell_active')){
                        loc_count ++;
                    }
                    //
                });

                if (loc_count === tot_count){
                    let cell = $(this).find('p');
                    let active = cell.hasClass('day_cell_active');
                    if (active){
                     //   $(this).removeClass('day_cell_active');
                    } else {
                        cell.addClass('day_cell_active');
                    };

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
                console.log(  $(this).attr('data-id') );
                ids.push( $(this).attr('data-id')  );
            });

            $("#sel_times").val(ids.join(','));

        }



    });
</script>
