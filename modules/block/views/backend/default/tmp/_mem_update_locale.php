<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 18.12.2018
 * Time: 8:09
 */
use yii\helpers\Html;

echo $form->field($mem,'locales')->hiddenInput(['id'=>'sel_locales'])->label(false);
return '';
?>

<style>

    #map {
        width: 100%;
        height: 90%;
    }
</style>
`
<div style="width: auto;height: 500px" id="map"></div>

<script>
    $(document).ready( function () {

        var ccord_str = [];

        var myMap;
        var targets_select = [];

        var ids = $('#sel_locales').val().split(',');

        if (ids[0] === ''){
            ids = [];
        }

        ids.forEach(function(item, i, arr) {
            targets_select.push(item);

        });


        var circle_num_coords =[];

        // Дождёмся загрузки API и готовности DOM.
        ymaps.ready(init);

        function init () {
            lat0 = 58.665843;
            lon0 = 49.545688;
            var r = 250;
            // Создание экземпляра карты и его привязка к контейнеру с
            // заданным id ("map").
            myMap = new ymaps.Map('map', {
                // При инициализации карты обязательно нужно указать
                // её центр и коэффициент масштабирования.
                center: [58.603269, 49.636136], // Москва //49.667978%2C58.60358
                zoom: 13
            }, {
                searchControlProvider: 'yandex#search'
            });

            myMap.events.add('click', function (e) {
                if (!myMap.balloon.isOpen()) {
                    var coords = e.get('coords');
                    console.log(
                        coords[0].toPrecision(8),
                        coords[1].toPrecision(8)
                    );

                }
                else {
                    myMap.balloon.close();
                }
            });

            var dx = 1;
            var dy = 1;

            var c = 1;
            for (var i=1;i<=30;i++){
                dy = dy - (r * 1.74 );

                dx = (i % 2 == 0) ? 1 :  r/1.02;

                for (var j=1;j<=18;j++){
//58.585614 49.614681

                    dx = dx - (r * 2);


                    var lat = ( lat0 + (180/Math.PI)*(dy/6378137)).toPrecision(8);
                    var lon = (lon0 + (180/Math.PI)*(dx/6378137)/Math.cos(lat0)).toPrecision(8);



                    var p1 = new LatLon(lat, lon);
                    var p2 = new LatLon(58.629602 ,49.573815);

                    var in_circle = p1.distanceTo(p2) <= r;



                    var color_fill = "#DB709333";
                  /*  if (in_circle){
                        color_fill = "#71e411c4"
                    }*/

                    var sel_circle  = ids.indexOf( String(c));
                    if ( (sel_circle  != -1)){
                        color_fill = "#71e411c4";
                    }

                    var circle_c = [ parseFloat( lat),  parseFloat(lon)];


                    circle_num_coords.push(circle_c);
                    //var circle_c = [58.603269, 49.636136];
                    // Создаем круг.
                    var myCircle = new ymaps.Circle([
                        // Координаты центра круга.
                        circle_c,
                        // Радиус круга в метрах.
                        r
                    ], {
                        // Описываем свойства круга.
                        // Содержимое балуна.
                          balloonContent: " №" + c,
                        // Содержимое хинта.
                    }, {
                        // Задаем опции круга.
                        // Включаем возможность перетаскивания круга.
                        draggable: false,
                        // Цвет заливки.
                        // Последний байт (77) определяет прозрачность.
                        // Прозрачность заливки также можно задать используя опцию "fillOpacity".
                        fillColor: color_fill,
                        // Цвет обводки.
                        strokeColor: "#990066",
                        // Прозрачность обводки.
                        strokeOpacity: 0.6,
                        // Ширина обводки в пикселях.
                        strokeWidth: 2,
                        idTarget:c
                    });

                    myCircle.events.add(['click',], function (e) {
                        var id = String(e.get('target').options.get('idTarget'));
                        var index  = targets_select.indexOf(id);
                        console.log('indexOf',id,targets_select,index);
                        if ( ! (index  != -1)){
                            targets_select.push(id);
                            e.get('target').options.set('fillColor','#2578ffab');
                        } else {
                            targets_select.splice(index, 1);
                            e.get('target').options.set('fillColor','#DB709333');
                        }
                        $('#sel_locales').val(targets_select.join(','));
// fillColor: "#DB709333", #2578ffab
                        var coords = e.get('coords');


                        console.log(
                            coords[0].toPrecision(8),
                            coords[1].toPrecision(8)
                        );

                    });

                    // Добавляем круг на карту.
                    myMap.geoObjects.add(myCircle);
                    c ++;
                }
            }


            console.log( JSON.stringify(circle_num_coords));

            function distance(lat1,lon1,lat2,lon2){
                var R =  6378137;//6371;// 6378137; // Earth's radius in Km
                return Math.acos(Math.sin(lat1)*Math.sin(lat2) +
                    Math.cos(lat1)*Math.cos(lat2) *
                    Math.cos(lon2-lon1)) * R;
            }



        }
    } );

</script>
