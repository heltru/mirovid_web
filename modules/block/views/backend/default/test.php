<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 24.10.2018
 * Time: 20:57
 */
?>

<div class="row">
    <div class="col-xs-12">
        <?php echo \yii\helpers\Html::radioList('mod_draw', 'point' ,['point'=>'point','line'=>'line'],['id'=>'mod_draw']) ?>
    </div>
    <div class="col-xs-12">
        <canvas id="holst" width="1280" height="640">

        </canvas>
    </div>
</div>

<script>
    $(document).ready( function (){

        var canvas = document.getElementById("holst");
        var canvasLeft = canvas.offsetLeft;
        var canvasTop = canvas.offsetTop;

        var ctx = canvas.getContext("2d");

        var a  = 10;

        var h = 64;
        var w = 128;

        var pw = w * a;
        var ph = h * a;

        var first_coord = {x:0,y:0};
        var btn_down = false;

        ctx.lineWidth=1;
        ctx.strokeStyle = "#d6d4d4";

        ctx.save();

        for (var x=0; x <= w; x++ ){
            ctx.moveTo(x*a,0);
            ctx.lineTo(x*a,h*a);
            ctx.stroke();
        }


        for (var y=0; y <= h; y++ ){
            ctx.moveTo(0,y*a);
            ctx.lineTo(w*a,y*a);
            ctx.stroke();
        }


        var mod = $('input[name=mod_draw]:checked').val();

        $('input[type=radio][name=mod_draw]').change(function() {
            mod = $(this).val();
        });


        canvas.addEventListener('mouseup', function(e) {
            var rect = canvas.getBoundingClientRect();

            var x = Math.ceil( (e.clientX - rect.left) / a );
            var y = Math.ceil( (e.clientY - rect.top) / a );

            var color_r = [ Math.round(  Math.random()*255 ), Math.round(  Math.random()*255 ), Math.round(  Math.random()*255 ) ];
            var color = "rgb("+ color_r.join(',') +")";





            if (mod === 'point'){
                ctx.fillStyle = color;
                ctx.fillRect( (x-1)*a, (y-1)*a, 10, 10);
            }

            if (mod === 'line'){
                ctx.lineWidth = 10;
                ctx.beginPath();
                ctx.strokeStyle = color;
                ctx.moveTo(first_coord.x*a,first_coord.y*a);
                ctx.lineTo(x*a,y*a);
                ctx.stroke();
            }

            btn_down = false;
        }, false);

        canvas.addEventListener('mousedown', function(e) {

            var rect = canvas.getBoundingClientRect();

            var x = Math.ceil( (e.clientX - rect.left) / a );
            var y = Math.ceil( (e.clientY - rect.top) / a );

            first_coord = {x:x,y:y};

            if (mod === 'line'){

                ctx.lineWidth = 10;
                ctx.beginPath();
                ctx.strokeStyle = 'rgb(125,178,212)';
                ctx.moveTo(first_coord.x*a,first_coord.y*a);
            }

            btn_down = true;
        }, false);

        ctx.save();

        canvas.addEventListener('mousemove', function(e) {
            /*
            if (mod !== 'line'){
                return false;
            }
            if (!btn_down){
                return false;
            }
            */

            var rect = canvas.getBoundingClientRect();

            var x = Math.ceil( (e.clientX - rect.left) / a );
            var y = Math.ceil( (e.clientY - rect.top) / a );




            ctx.beginPath();
            ctx.fillStyle = "#2639d6";
            ctx.fillRect( x*a, y*a, 10, 10);
            ctx.restore();

            /*
            ctx.moveTo(first_coord.x*a,first_coord.y*a);

            ctx.lineTo(x*a,y*a);
            ctx.stroke();
            ctx.restore();

            console.log(x,y);
*/
        }, false);




    });
</script>
