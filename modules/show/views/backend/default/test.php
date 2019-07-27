<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 11.11.2018
 * Time: 19:06
 */
?>
<script>
    var msgs = [12,15,18];



    var i = 0;

    setInterval(
        function (){
            if (i > msgs.length ){
                i = 0;
            }
            $.ajax({
                type: "POST",
                url: "<?= \yii\helpers\Url::to(['/api/car/register-show'])?>",
                data: {_csrfbe:yii.getCsrfToken(),msg_id:msgs[i],
                    date_sh:Math.round((new Date()).getTime() / 1000),car_id:2},
                success:function (data) {
                    console.log(data);
                }
            });
            i ++;

        }
        ,2000);



</script>
