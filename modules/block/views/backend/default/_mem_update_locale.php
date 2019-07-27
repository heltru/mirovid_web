<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 18.12.2018
 * Time: 8:09
 */
use yii\helpers\Html;

echo $form->field($mem,'locales')->hiddenInput(['id'=>'sel_locales'])->label(false);

?>

<style>

    #map {
        width: 100%;
        height: 90%;
    }
</style>

<div style="width: auto;height: 500px" id="map"></div>

<script>
    $(document).ready( function () {

        var _matrix_arr = [];

        var myMap;
        var targets_select = [];
        var c = 1;
        var ids = $('#sel_locales').val().split(',');


        var curr_bounds  = [];

        var draw_lat = 0;
        var draw_lon = 0;
        var count_rows = 25;
        var count_cols = 19;

        var init_lon = 49.545688;
        var init_lat = 58.649495;


        if (ids[0] === ''){
            ids = [];
        }
        ids.forEach(function(item, i, arr) {
            targets_select.push(item);

        });


        // Дождёмся загрузки API и готовности DOM.
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
            myMap = new ymaps.Map('map', {
                center: [58.603269, 49.636136],
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

                   // _matrix_arr.push(curr_bounds);

                    var rectangle = new ymaps.Rectangle(curr_bounds, { hintContent:  c}, {
                        coordRendering: "boundsPath",
                        fillColor: color_fill,
                        strokeWidth: 2,
                        strokeOpacity: 0.6,
                        idTarget:c
                    });

                    rectangle.events.add(['click',], function (e) {
                        var id = String(e.get('target').options.get('idTarget'));
                        var index  = targets_select.indexOf(id);

                        if ( ! (index  != -1)){
                            targets_select.push(id);
                            e.get('target').options.set('fillColor','#2578ffab');
                        } else {
                            targets_select.splice(index, 1);
                            e.get('target').options.set('fillColor','#DB709333');
                        }
                        $('#sel_locales').val(targets_select.join(','));


                    });

                    myMap.geoObjects.add(rectangle);

                    myMap.geoObjects.remove(myCircle);


                    c ++;
                }
            }


         //   console.log(JSON.stringify(_matrix_arr));


        }
    } );

</script>
