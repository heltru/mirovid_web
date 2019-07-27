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

                myMap.geoObjects
                    .add(new ymaps.Placemark([item.lat,item.long], {
                        balloonContent: '<strong>'+item.id+'</strong>' + ' ' + item.time
                    }, {
                        preset: 'islands#icon',
                        iconColor: '#0095b6'
                    }))


            });



        }
    } );

</script>
