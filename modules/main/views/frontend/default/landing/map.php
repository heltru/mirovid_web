<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 27.11.2019
 *
 *Time: 10:53
 */

?>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=68531ae1-9ce3-44cf-95f9-a2a922bf7358" type="text/javascript"></script>
<script src="/js/ymaps-master/car/car.js" type="text/javascript"></script>
<link rel="shortcut icon" href="/js/ymaps-master/car/car.css">
<input type="hidden" id="tracks" value='<?=$tracks?>'>
<input type="hidden" id="colors" value='<?=$colors?>'>
<input type="hidden" id="tracks_session" value='<?=$tracks_session?>'>

<div style="width: auto;height: 500px" id="map"></div>

<script>
    $(document).ready( function () {


        var tracks_pm = [];
        var tracks_session = [];

        ymaps.ready(init);

        const tracks = JSON.parse($("#tracks").val());
        const colors = JSON.parse($("#colors").val());
        tracks_session = JSON.parse($("#tracks_session").val());



        function init () {

            myMap = new ymaps.Map('map', {
                center: [58.603269, 49.636136],
                zoom: 12
            }, {
                searchControlProvider: 'yandex#search'
            });




            $.getScript('car.js', function () {
                car = new Car({
                    iconLayout: ymaps.templateLayoutFactory.createClass(
                        '<div class="b-car b-car_blue b-car-direction-$[properties.direction]"></div>'
                    )
                });

                // Прокладывание маршрута от станции метро "Смоленская"
                // до станции Третьяковская (маршрут должен проходить через метро "Кропоткинская").
                // Точки маршрута можно задавать 3 способами:  как строка, как объект или как массив геокоординат.
                ymaps.route([
                    [58.6557, 49.6170]
                    ,[58.5389, 49.6893]
                ], {
                    // Опции маршрутизатора
                    mapStateAutoApply: true // автоматически позиционировать карту
                }).then(function (route) {
                    // Задание контента меток в начальной и конечной точках
                    var points = route.getWayPoints();

                    points.get(0).properties.set("iconContent", "А");
                    points.get(1).properties.set("iconContent", "Б");

                    // Добавление маршрута на карту
                    map.geoObjects.add(route);
                    // И "машинку" туда же
                    map.geoObjects.add(car);

                    // Отправляем машинку по полученному маршруту простым способом
                    // car.moveTo(route.getPaths().get(0).getSegments());
                    // или чуть усложненным: с указанием скорости,
                    car.moveTo(route.getPaths().get(0).getSegments(), {
                        speed: 10,
                        directions: 4
                    }, function (geoObject, coords, direction) { // тик движения
                        // перемещаем машинку
                        geoObject.geometry.setCoordinates(coords);
                        // ставим машинке правильное направление - в данном случае меняем ей текст
                        geoObject.properties.set('direction', direction.t);

                    }, function (geoObject) { // приехали
                        geoObject.properties.set('balloonContent', "Приехали!");
                        geoObject.balloon.open();
                    });

                }, function (error) {
                    console.error("Возникла ошибка: " + error.message);
                });
            });



/*
            tracks.forEach(function(item, i) {
                tracks_pm[i] = {active_index:0,item:item,curr_pm:undefined};
                let color = colors[i];
                let polyline = new ymaps.Polyline(item.points, { hintContent: i},
                    {strokeColor: color,strokeWidth: 8});
                myMap.geoObjects.add(polyline);
            });



            var intervalID = setInterval(updateCarPosition,250,myMap);

            function updateCarPosition (myMap) {


                tracks.forEach(function(item, j, arr) {


                    let active_index = tracks_pm[j].active_index;


                    let save_item = tracks_session[j];
                    if ((save_item['points'].length-1) === save_item['point_index']){
                        tracks_session[j]['point_index'] = 0;
                    } else {
                        let speed =  Math.round( Math.random() * 4);
                        if (speed === 0) speed = 1;

                        let calcIndex = save_item['point_index']+speed;
                        if (save_item['points'].length-1 >=calcIndex){
                            tracks_session[j]['point_index'] = tracks_session[j]['point_index'] + speed;
                        } else {
                            tracks_session[j]['point_index'] = tracks_session[j]['point_index'] + 1;
                        }

                    }

                    let point =  tracks_session[j]['points'][tracks_session[j]['point_index']];
                    let data = {};
                    data.active_index = tracks_session[j]['point_index'];
                    data.lat = point[0];data.long = point[1];


                    tracks_pm[j].active_index = data.active_index;

                    if (tracks_pm[j].curr_pm !== undefined ){
                        myMap.geoObjects.remove(tracks_pm[j].curr_pm);
                    }

                    let pm = new ymaps.Placemark([data.lat,data.long],{hasBalloon:false}, {iconLayout: 'default#image',
                        iconImageHref: '/images/map_car/car/Stinger-GTA2_7.png', iconImageSize: [56, 27]});
                    tracks_pm[j].curr_pm = pm;
                    myMap.geoObjects.add(pm);



                });



            }
*/

        }


    } );



</script>
