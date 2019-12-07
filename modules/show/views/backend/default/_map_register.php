<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 19.12.2018
 * Time: 18:27
 */
?>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=68531ae1-9ce3-44cf-95f9-a2a922bf7358" type="text/javascript"></script>
<script src="/js/geodesy-master/latlon-spherical.js"></script>

<input type="hidden" id="points" value='<?=$points?>'>
<?php

?>
<div style="width: auto;height: 500px" id="map"></div>

<script>
    $(document).ready( function () {

        // Дождёмся загрузки API и готовности DOM.
        ymaps.ready(init);

        const points = JSON.parse($("#points").val());



        function init () {

            // Создание экземпляра карты и его привязка к контейнеру с
            // заданным id ("map").
            myMap = new ymaps.Map('map', {
                // При инициализации карты обязательно нужно указать
                // её центр и коэффициент масштабирования.
                center: [58.603269, 49.636136],
                zoom: 13
            }, {
                searchControlProvider: 'yandex#search'
            });



            points.forEach(function(item, i, arr) {
                let balloon = {
                    balloonContentHeader: item.date + ' ' + item.time ,

                };
                let bc = '<div>';

              //  bc += '<span>' +item.time + '</span>';
                bc +=  '<div style="float: left;width: 20%">';
                bc += '<div style="width:22px;height:22px; margin-top: 16px; border-radius: 10px;background:red;text-align: center;color: white; font-size: 12px;font-weight: bold;" ><span>'+item.num+'</span></div>';
                bc += '</div>';

                bc += '<div style="float: right; width: 80%;"><img style="width:96px" src="/'+item.src+'"></div>';

                bc += '</div>';

                balloon.balloonContent = bc;
                /*
                if (item.src){
                    balloon.balloonContent ='<img src="/'+item.src+'">'
                }*/
                myMap.geoObjects
                    .add(new ymaps.Placemark([item.lat,item.long],balloon, {
                        preset: 'islands#icon',
                        iconColor: '#0095b6'
                    }))


            });



        }
    } );

</script>
