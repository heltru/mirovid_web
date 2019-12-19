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

    class EventEmitter {
        constructor() {
            this.events = {};
        }

        subscribe(eventName, fn) {
            if (!this.events[eventName]) {
                this.events[eventName] = [];
            }

            this.events[eventName].push(fn);

            return () => {
                this.events[eventName] = this.events[eventName].filter(eventFn => fn !== eventFn);
            }
        }

        emit(eventName, data) {
            const event = this.events[eventName];
            if (event) {
                event.forEach(fn => {
                    fn.call(null, data);
                });
            }
        }
    }
    const emitter = new EventEmitter();


    const thing_id = "<?=Yii::$app->request->get('id') ? Yii::$app->request->get('id') : 3?>";
    const host = '<?=Yii::$app->request->hostInfo?>';
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
                        file_path_use_cache(file,function (file){

                            $('#viewer_img').attr('src', file);
                            $('#viewer_img').show();
                            console.log('show ',file);
                            register_show(reklamir_id,0,0);
                        });

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
                        register_show(reklamir_id,0,0);
                    }
                }
                await delay(10000);
                $('#viewer_img').hide();
                $('#viewer_video').hide();
            }
            location.reload();

        }


        emitter.subscribe('event:fileSystem-init', data => {
            $.ajax({
                url:host + "/api/thing/playlist?thing_id="+thing_id,
                success: (data) => {
                    let playlist_new = data;
                    loop(data);
                }
            });
        });



        function file_path_use_cache(file,cb) {
            fs.getFile(file, {create : false}, function() {
                cb(file);
                console.log('file_path_use_cache create',file
                    );
            }, function() {
                cb(file);
                console.log('file_path_use_cache NOT_ create',file)
            });
        }


        function register_show(reklamir_id,lat,long) {
            $.ajax({
                url:host + "/api/thing/register-show",
                data:{'reklamir_id':reklamir_id,'lat':lat,'long':long},
            });
        }



        function errorHandler(e) {
            var msg = '';
            console.log(e);
            switch (e.code) {

                case FileError.QUOTA_EXCEEDED_ERR:
                    msg = 'QUOTA_EXCEEDED_ERR';
                    break;
                case FileError.NOT_FOUND_ERR:
                    msg = 'NOT_FOUND_ERR';
                    break;
                case FileError.SECURITY_ERR:
                    msg = 'SECURITY_ERR';
                    break;
                case FileError.INVALID_MODIFICATION_ERR:
                    msg = 'INVALID_MODIFICATION_ERR';
                    break;
                case FileError.INVALID_STATE_ERR:
                    msg = 'INVALID_STATE_ERR';
                    break;
                default:
                    msg = 'Unknown Error';
                    break;
            }
            ;

            console.log('Error: ' + msg);
        }


        navigator.webkitPersistentStorage.requestQuota(1024 * 1024 * 300, () => {
            window.webkitRequestFileSystem(window.PERSISTENT, 1024 * 1024 * 300, (a) => {
                fs = a;
                emitter.emit('event:fileSystem-init');
            },errorHandler);
        });




    });
</script>