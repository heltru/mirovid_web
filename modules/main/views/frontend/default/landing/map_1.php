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


        }


    } );



</script>
