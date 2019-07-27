<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 18.12.2018
 * Time: 8:09
 */
use yii\helpers\Html;


echo $form->field($mem,'locales_cost')->hiddenInput(['id'=>'sel_locales_cost'])->label(false);

//return '';
?>


<div style="width: auto;height: 500px" id="map_cost"></div>

<p>Ставка района</p>
<table class="table table-bordered cost_table">
    <tbody>
    <tr>
        <th>Район</th>
        <th>Стоимость</th>
    </tr>
    </tbody>
</table>


<script>
    $(document).ready( function () {

        var myMap;
        var targets_select_p = [];
        var c =1;

        var items = JSON.parse( $('#sel_locales_cost').val() );

        var ids = [];

        var curr_bounds  = [];
        var draw_lat = 0;
        var draw_lon = 0;
        var count_rows = 25;
        var count_cols = 19;

        var init_lon = 49.545688;
        var init_lat = 58.649495;

        let sel_regions_cost = [];

        items.forEach(function(item, i, arr) {

            targets_select_p.push( String(item[0]));
            ids.push( String(item[0]));
            let  id = item[0];
            let  cost = item[1];
            $('.cost_table  tr:last').after('<tr data-region_id="'+id+'"><td>'+id+'</td><td><input class="input_cost" value="'+cost+'" type="text"></td></td></tr>');
        });

        ymaps.ready(init);

        function calc_point(i,j,draw_lat_lon,curr_bounds) {

            if (curr_bounds === 0){
                return draw_lat_lon;
            }
            if (curr_bounds.length === 0){
                return draw_lat_lon;
            }
            if ( i === 1 && j === 0 ){
                return draw_lat_lon;
            }

          //  console.log('i=',i,'j=',j);

            if (j === 1){

                var calc_lat = ( curr_bounds[0][0] - curr_bounds[1][0]) / 2 + curr_bounds[0][0];
                var calc_lon = (i % 2 === 0) ? init_lon : ((curr_bounds[1][1] - curr_bounds[0][1]) / 2) + init_lon ;
                return [calc_lat,calc_lon];

            } else {
                //def

                var calc_lat = draw_lat_lon[0];
                var calc_lon = (curr_bounds[1][1] - curr_bounds[0][1]) / 2 + curr_bounds[1][1];

                return [calc_lat,calc_lon];

            }




            return [draw_lat_lon];
        }

        function init () {

            draw_lon = init_lon;
            draw_lat = init_lat;

            var r = 250;

            myMap = new ymaps.Map('map_cost', {
                // При инициализации карты обязательно нужно указать
                // её центр и коэффициент масштабирования.
                center: [58.603269, 49.636136], // Москва //49.667978%2C58.60358
                zoom: 13
            }, {
                searchControlProvider: 'yandex#search'
            });


            for (var i=1;i<=count_rows;i++){

                for (var j=1;j<=count_cols;j++){


                    var color_fill = "#DB709333";

                    var sel_circle  = ids.indexOf( String(c));
                    if ( (sel_circle  != -1)){
                        color_fill = "#71e411c4";
                    }



                    var new_point = calc_point(i,j,[draw_lat,draw_lon],curr_bounds);

                    draw_lat = new_point[0];
                    draw_lon = new_point[1];

                    var myCircle = new ymaps.Circle( [ [draw_lat,draw_lon], r ], {
                    }, { draggable: false, fillColor: color_fill,strokeColor: "#990066",strokeOpacity: 0.6,strokeWidth: 2,idTarget:c });



                    myMap.geoObjects.add(myCircle);


                    curr_bounds = myCircle.geometry.getBounds();


                    var rectangle = new ymaps.Rectangle(curr_bounds, { hintContent:  c,}, {
                        coordRendering: "boundsPath",
                        fillColor: color_fill,
                        strokeWidth: 2,
                        strokeOpacity: 0.6,
                        idTarget:c
                    });

                    rectangle.events.add(['click',], function (e) {
                        var id = String(e.get('target').options.get('idTarget'));
                        var index  = targets_select_p.indexOf(String(id));

                        if ( ! (index  != -1)){

                            targets_select_p.push(id);
                            e.get('target').options.set('fillColor','#2578ffab');

                            $('.cost_table  tr:last').after('<tr data-region_id="'+id+'"><td>'+id+'</td><td><input class="input_cost" value="1" type="text"></td></td></tr>');


                        } else {

                            targets_select_p.splice(index, 1);
                            e.get('target').options.set('fillColor','#DB709333');

                            $('.cost_table').find("tr[data-region_id='" +id + "']").remove();

                        }

                        $('#sel_locales_cost').val(targets_select_p.join(','));



                        sel_regions_cost = [];
                        $('.cost_table tr:not(:has(th))').each (function(i,v) {
                            let id =  $(this).attr('data-region_id');
                            let cost = $(this).find('input').val();
                            sel_regions_cost.push([id,cost]);
                        });


                        $('#sel_locales_cost').val(JSON.stringify(sel_regions_cost));


                    });

                    myMap.geoObjects.add(rectangle);

                    myMap.geoObjects.remove(myCircle);




                    c ++;
                }

            }





        }




        $('body').on('change','.input_cost',function (e) {
            sel_regions_cost = [];
            $('.cost_table tr:not(:has(th))').each (function(i,v) {
                let id =  $(this).attr('data-region_id');
                let cost = $(this).find('input').val();
                sel_regions_cost.push([id,cost]);
            });


                $('#sel_locales_cost').val(JSON.stringify(sel_regions_cost));
        });


    } );

</script>
