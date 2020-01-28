<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 23.01.2020
 * Time: 14:58
 */

?>

<script>
    $(document).ready(function () {

        setInterval(function (){

            $.ajax({
                url:  "/test/default/test-time",
                success:function (data){
                    console.log(data);
                }
            });

        }, 50000);



    });
    </script>