<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 11.12.2019
 * Time: 19:51
 */
?>
<style>
    html,body{
        margin:0;
        height:100%;
        overflow:hidden;
    }
    img{
        min-height:100%;
        min-width:100%;
        height:auto;
        width:auto;
        position:absolute;
        top:-100%; bottom:-100%;
        left:-100%; right:-100%;
        margin:auto;
    }
</style>

<img id="viewer_img">
<div id="viewer_video"></div>


<script>
    const thing_id = "<?=Yii::$app->request->get('id') ? Yii::$app->request->get('id') : 3?>";
    const host = 'mirovid';
    var fs = null;
    const dir_web = 'mirovid';
    const dir_files = 'files';

    var playlist_new = [];
    var playlist_old = [];

    var comparelist_new = [];
    var comparelist_old = [];

    var img_types = ['png','gif','jpg'];
    var video_types = ['mp4'];

    $(document).ready(function () {



        var m_flag = false; // отслеживание ширины устройства
        var screen_width = $(window).width();
        if (screen_width <= 768) {
            m_flag = true;
        }

        var video_block = $('#viewer_video');


        const delay = (amount = number) => {
            return new Promise((resolve) => {
                setTimeout(resolve, amount);
            });
        };

        async function loop(playlist_new) {

            for (reklamir_id in playlist_new.reklamir) {


                if (playlist_new.reklamir.hasOwnProperty(reklamir_id)) {
                    let file = dir_web + '/' + dir_files + '/' + playlist_new.reklamir[reklamir_id].file;
                    let file_ext = file.split('.').pop();

                    if (img_types.indexOf(file_ext) !== -1) {
                        $('#viewer_img').attr('src', file);
                        $('#viewer_img').show();
                        console.log('show ',file);
                    }
                    if (video_types.indexOf(file_ext) !== -1) {
                        let video = '<video autoplay="autoplay" loop="" preload="auto" muted>' +
                            '<source src="/' + file + '" ></video>';
                        if (m_flag) {
                            let video = ' <video autoplay="autoplay" loop="" preload="auto" controls muted poster="/' + file + '"><source src="/' + file + '"></video>';
                        }
                        $(video_block).html(video);
                        $('#viewer_video').show();
                        console.log('show ',file);
                    }
                }
                await delay(10000);
                $('#viewer_img').hide();
                $('#viewer_video').hide();
            }
            location.reload();

        }


        $.ajax({
            url: 'http://' + host + "/api/thing/playlist?thing_id="+thing_id,
            success: (data) => {
                let playlist_new = data;
                loop(data);
            }
        });


    });
</script>