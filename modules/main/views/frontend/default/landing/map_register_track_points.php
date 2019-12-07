<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 27.11.2019
 *
 *Time: 10:53
 */
$data_dec = '';
$data_cont = [];
foreach ($data as $item){
    $data_cont[] = [ 'lat'=>$item->lat,
        'long'=>$item->long];

}

$data_dec = json_encode($data_cont);
?>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=68531ae1-9ce3-44cf-95f9-a2a922bf7358" type="text/javascript"></script>

<input type="hidden" id="points" value='<?=$data_dec?>'>


<div style="width: auto;height: 500px" id="map"></div>

<script>
    $(document).ready( function () {
/*

        //var cars = [{img:img,tracks_index:1}];
        cars = $("#car_value")
        var tracks = [{points:[car[0]],id:1}];

*/

        $(document).keydown(function(event){
            if(event.which=="17")
                cntrlIsPressed = true;
        });

        $(document).keyup(function(){
            cntrlIsPressed = false;
        });

        var cntrlIsPressed = false;




        ymaps.ready(init);

        const points = JSON.parse($("#points").val());



        var old_coord;

        function init () {

            myMap = new ymaps.Map('map', {
                center: [58.603269, 49.636136],
                zoom: 13
            }, {
                searchControlProvider: 'yandex#search'
            });


            points.forEach(function(item, i, arr) {

                pm = new ymaps.Placemark([item.lat,item.long],{hasBalloon:false}, {
                    preset: 'islands#icon',
                    iconColor: '#0095b6'
                });

                pm.events.add('click', function (e) {

                    var coords = e.get('coords');

                    if (cntrlIsPressed && old_coord !== undefined){



                        $.ajax({
                            url: "<?= \yii\helpers\Url::to(['/admin/show/default/add-track-point'])?>",
                            data: {_csrfbe:yii.getCsrfToken(),track_id:1,lat:coords[0].toPrecision(6),long: coords[1].toPrecision(6)},
                            success:function (data) {
                                console.log(data);

                            }
                        });



                        let polyline = new ymaps.Polyline( [
                            [old_coord[0], old_coord[1]],
                            [coords[0].toPrecision(6), coords[1].toPrecision(6)]
                        ], {
                            hintContent: "Ломаная"
                        }, {
                            draggable: true,
                            strokeColor: '#000000',
                            strokeWidth: 4,
                            // Первой цифрой задаем длину штриха.
                            // Второй — длину разрыва.
                        });

                        myMap.geoObjects.add(polyline);




                        var circleGeometry = new ymaps.geometry.Circle([coords[0].toPrecision(6), coords[1].toPrecision(6)], 100),
                            circleGeoObject = new ymaps.GeoObject({
                                geometry: circleGeometry
                            });

                        myMap.geoObjects
                            .add(circleGeoObject);




                    }

                    old_coord = [coords[0].toPrecision(6), coords[1].toPrecision(6)];


                });

                myMap.geoObjects
                    .add(pm)


            });



        }
    } );

</script>
