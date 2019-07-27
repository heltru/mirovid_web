<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 18.12.2018
 * Time: 8:09
 */
use yii\helpers\Html;


 echo $form->field($mem,'locales_cost')->hiddenInput(['id'=>'sel_locales_cost'])->label(false);


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

        var items = JSON.parse( $('#sel_locales_cost').val() );

        ids = [];

        items.forEach(function(item, i, arr) {

            targets_select_p.push( String(item[0]));
            ids.push( String(item[0]))
            let  id = item[0];
            let  cost = item[1];
            $('.cost_table  tr:last').after('<tr data-region_id="'+id+'"><td>'+id+'</td><td><input class="input_cost" value="'+cost+'" type="text"></td></td></tr>');
        });

        let sel_regions_cost = [];

        // Дождёмся загрузки API и готовности DOM.
        ymaps.ready(init);

        function init () {
            lat0 = 58.665843;
            lon0 = 49.545688;
            var r = 250;
            // Создание экземпляра карты и его привязка к контейнеру с
            // заданным id ("map").
            myMap = new ymaps.Map('map_cost', {
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


                    var lat = lat0 + (180/Math.PI)*(dy/6378137);
                    var lon = lon0 + (180/Math.PI)*(dx/6378137)/Math.cos(lat0);


                    var color_fill = "#DB709333";
                  /*  if (in_circle){
                        color_fill = "#71e411c4"
                    }*/

                    var sel_circle  = ids.indexOf( String(c));
                    if ( (sel_circle  != -1)){
                        color_fill = "#71e411c4";
                    }

                    var circle_c = [lat, lon];
                    //circle_num_coords[c] = circle_c;
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
                        //  balloonContent: "Область №" + c,
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
                        var index  = targets_select_p.indexOf(String(id));
                        console.log('indexOf',id,targets_select_p,index);
                        if ( ! (index  != -1)){

                            targets_select_p.push(id);
                            e.get('target').options.set('fillColor','#2578ffab');

                            $('.cost_table  tr:last').after('<tr data-region_id="'+id+'"><td>'+id+'</td><td><input class="input_cost" value="1" type="text"></td></td></tr>');


                        } else {

                            targets_select_p.splice(index, 1);
                            e.get('target').options.set('fillColor','#DB709333');

                            $('.cost_table').find("tr[data-region_id='" +id + "']").remove();

                        }

                        $('#sel_locales').val(targets_select_p.join(','));



                        sel_regions_cost = [];
                        $('.cost_table tr:not(:has(th))').each (function(i,v) {
                            let id =  $(this).attr('data-region_id');
                            let cost = $(this).find('input').val();
                            sel_regions_cost.push([id,cost]);
                        });


                        $('#sel_locales_cost').val(JSON.stringify(sel_regions_cost));


                    });

                    // Добавляем круг на карту.
                    myMap.geoObjects.add(myCircle);
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
