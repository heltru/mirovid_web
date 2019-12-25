<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 11.12.2019
 * Time: 19:51
 */
?>
<style>
    html, body {
        margin: 0;
        height: 100%;
        overflow: hidden;
    }

    img {
        min-height: 80%;
        min-width: 100%;
        height: auto;
        width: auto;
        position: absolute;
        top: -100%;
        bottom: -100%;
        left: -100%;
        right: -100%;
        margin: auto;
    }



    video {
        position: fixed; right: 0; bottom: 0;
        min-width: 100%; min-height: 100%;
        width: auto; height: auto; z-index: -100;
        background: url(polina.jpg) no-repeat;
        background-size: cover;
    }


</style>

<img id="viewer_img">
<div id="viewer_video"></div>
<ul id="filelist"></ul>

<script>

    $('#viewer_img').hide();
    $('#viewer_video').hide();


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


    const thing_id = "<?=Yii::$app->request->get('id') ? Yii::$app->request->get('id') : 15?>";
    const host = '<?=Yii::$app->request->hostInfo?>';
    var fs = null;
    const dir_web = 'mirovid';
    const dir_files = 'files';

    var playlist_new = [];
    var comparelist_new = [];

    var reklamir_id = 0;


    var img_types = ['png', 'jpg', 'gif', 'jpeg', 'bmp'];
    var video_types = ['mp4', 'avi', 'webm', 'mpeg', 'mpg', 'wmv', 'mkv', 'mov','MOV'];

    var time_delay = parseInt("<?=Yii::$app->request->get('t') ? Yii::$app->request->get('t') : 60000?>");


    var lat = 0;
    var long = 0;
    var matrix_area = [[[58.63153527769201,49.511239887417005],[58.66744545273785,49.580136112582935]],[[58.63153527769198,49.58013611258283],[58.66744545273787,49.64903233774886]],[[58.63153527769201,49.64903233774886],[58.66744545273785,49.717928562914835]],[[58.595620373349945,49.511275223827596],[58.63153075030898,49.580100776172344]],[[58.59562037334996,49.580100776172344],[58.63153075030897,49.64892632851704]],[[58.595620373349945,49.64892632851694],[58.63153075030898,49.71775188086174]],[[58.5597052734611,49.54572325051421],[58.59561585246496,49.61447830183058]],[[58.55970527346108,49.61447830183053],[58.59561585246498,49.68323335314701]],[[58.5597052734611,49.68323335314701],[58.59561585246496,49.75198840446344]],[[58.52378997788079,49.51134563925065],[58.55970075906097,49.580030360749284]],[[58.52378997788079,49.580030360749284],[58.55970075906097,49.64871508224792]],[[58.52378997788082,49.64871508224792],[58.55970075906097,49.7173998037465]]];

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            lat = position.coords.latitude;
            long = position.coords.longitude;
            console.log(lat,long);
        });
    }

    var watchId = navigator.geolocation.watchPosition(function(position) {
        console.log(position.coords.latitude);
        console.log(position.coords.longitude);
    });

    navigator.geolocation.clearWatch(watchId);

    $(document).ready(function () {



        var matrix_time = [];//build_matrix_time();
        var time_id_active = 0;
        var area_id_active = 0;

        build_matrix_time();

        function build_matrix_time(){
            let c = 1;
            let dx = 60*60;
            let start = 0;
            let stop = dx;

            for (let i=1; i<= 7;i++){
                matrix_time[i] = [];

                for (let j=1; j<= 24;j++){
                    matrix_time[i].push( {'c':c,'start':start,'stop':stop} );
                    start = stop;
                    stop += dx;
                    c += 1
                }

                start = 0;
                stop = dx;
            }

        }

        function check_time(times){
            if (times.length < 1){
                return true;
            }
            if (times.includes(time_id_active)) {
                return true;
            }
            return false;
        }

        function check_area(areas){
            if (area_id_active === 0){
                return true;
            }
            if (areas.length < 1){
                return true;
            }
            if ( areas.includes(area_id_active)){
                return true;
            }
            return false;
        }

        function convert_gps_area_id(){

            let curr_lat = lat;
            let curr_long = long;

            for (let area_num=0; area_num<= matrix_area.length-1;area_num++){
                let coord = self.matrix_area[area_num];
                let e_lat = curr_lat <= coord[1][0] && curr_lat >= coord[0][0];
                let e_long = curr_long <= coord[1][1] && curr_long >= coord[0][1];
                if (e_lat && e_long){
                    area_id_active = area_num+1;
                    console.log('area_id_active',area_id_active);
                }

            }


        }

        function convert_datetime_time_id(){
            let now = new Date(); //Date.now() / 1000 | 0;
            let hour = now.getHours();
            let minute = now.getMinutes();
            let week_day = now.getDay();

            let search_between = ((hour*60*60)+(minute*60));
            let day_cells = matrix_time[week_day];

            for (let i=0;i<=day_cells.length;i++){
                let item_time = day_cells[i];
                let start = item_time['start'];
                let stop = item_time['stop'];
                if (search_between >= start && search_between <= stop){
                    time_id_active = item_time['c'];
                    return item_time['c']
                }
            }

        }

        navigator.webkitPersistentStorage.requestQuota(1024 * 1024 * 300, () => {
            window.webkitRequestFileSystem(window.PERSISTENT, 1024 * 1024 * 300, (a) => {
                fs = a;
                emitter.emit('event:fileSystem-init');
            }, errorHandler);
        }, errorHandler);

        function save_file(path, data) {


            fs.root.getFile(path, {create: true}, function (fileEntry) {

                // Create a FileWriter object for our FileEntry (log.txt).
                fileEntry.createWriter(function (fileWriter) {

                    fileWriter.onwriteend = function (e) {
                        console.log('Write completed.');
                    };

                    fileWriter.onerror = function (e) {
                        console.log('Write failed: ' + e.toString());
                    };
                    fileWriter.write(data);

                }, errorHandler);

            }, errorHandler);
        }

        function create_dirs_save_file(path, data) {


            let dir = path.split('/');
            dir.splice(-1, 1);

            rec_create_path({rootDirEntry:fs.root, folders:dir})
                .then( function(result) {
                    save_file(path, data);
                })
                .catch( function(err) {console.log("oops:" + err);});

        }

        function async_create_dir(obj) {
            let rootDirEntry = obj.rootDirEntry;
            let folders = obj.folders;

            return new Promise( function( resolve, reject) {
                if (folders[0] === '.' || folders[0] === '') {
                    folders = folders.slice(1);
                }
                rootDirEntry.getDirectory(folders[0], {create: true}, function (dirEntry) {resolve({folders:folders,rootDirEntry:dirEntry});}, errorHandler);
            });
        }

        function rec_create_path(obj) {
            function decide( obj) {
                if(obj.folders.length === 1)
                    return "lift off";
                obj.folders =  obj.folders.slice(1);
                return rec_create_path(obj); // not all done, recurse
            }
            return async_create_dir(obj).then(decide);
        }

        function file_in_chache(fileName, cb_yes, cb_no) {
            fs.root.getFile(fileName, {create: false}, function (fileEntry) {
                cb_yes(fileEntry.toURL());
            }, function () {
                cb_no(fileName);
            });
        }

        function preseach_img(file) {

            file_in_chache(file, function (file) {
                $('#viewer_img').attr('src', file);
                $('#viewer_img').show();
                console.log('show from chache', file);
                register_show();
            }, function (file) {

                $.ajax({
                    url: host + '/' + file,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (data) {
                        var blobData = data;
                        var url = window.URL || window.webkitURL;
                        var src = url.createObjectURL(data);
                        create_dirs_save_file(file, blobData);
                    }
                });

                $('#viewer_img').attr("src", file);
                $('#viewer_img').show();
                register_show();

            });


        }

        function video_tag(file) {
            let video = '<video autoplay="autoplay" loop="" preload="auto" muted>' +
                '<source src="' + file + '" ></video>';
            if (m_flag) {
                let video = ' <video autoplay="autoplay" loop="" preload="auto" controls muted poster="' + file + '">' +
                    '<source src="' + file + '"></video>';
            }
            return video;
        }



        function video_full_screen() {
            var elem = document.querySelector("video");
            if (elem.requestFullscreen) {
                elem.requestFullscreen();
            } else if (elem.mozRequestFullScreen) {
                elem.mozRequestFullScreen();
            } else if (elem.webkitRequestFullscreen) {
                elem.webkitRequestFullscreen();
            } else if (elem.msRequestFullscreen) {
                elem.msRequestFullscreen();
            }
        }

        function preseach_video(file) {


            file_in_chache(file, function (file) {
                $(video_block).html(video_tag(file));
                $('#viewer_video').show();
               // video_full_screen();

                console.log('show ', file);
                register_show();
            }, function (file) {
                $.ajax({
                    url: host + '/' + file,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: function (blobData) {
                        create_dirs_save_file(file, blobData);
                    }
                });

                $(video_block).html(video_tag('/' + file));
                $('#viewer_video').show();
                console.log('show ', file);
                register_show();


            });


        }


        async function loop(playlist_new) {

            for (var i = 0; i < playlist_new.broadcast.length; i++) {
                reklamir_id = playlist_new.broadcast[i];
                convert_datetime_time_id();
                convert_gps_area_id();

                if (playlist_new.reklamir.hasOwnProperty(reklamir_id)) {

                    if (! check_time(playlist_new.reklamir[reklamir_id].daytime)){
                        continue;
                    }
                    if (! check_area(playlist_new.reklamir[reklamir_id].area)){
                        continue;
                    }

                    let file = dir_web + '/' + dir_files + '/' + playlist_new.reklamir[reklamir_id].file;
                    let file_ext = file.split('.').pop();

                    if (img_types.indexOf(file_ext) !== -1) {
                        preseach_img(file);
                    }
                    if (video_types.indexOf(file_ext) !== -1) {
                        preseach_video(file);
                    }
                }
                await delay(time_delay);
                $('#viewer_img').hide();
                $('#viewer_video').hide();
            }

             location.reload();

        }


        emitter.subscribe('event:fileSystem-init', data => {

            $.ajax({
                url: host + "/api/thing/playlist?thing_id=" + thing_id,
                success: (data) => {
                    playlist_new = data;
                    dir_files_clear(playlist_new);
                    loop(data);
                }
            });

        });

        function dir_files_clear(playlist_new) {
            comparelist_new = [];
            for (reklamir_id_new in playlist_new.reklamir) {
                if (playlist_new.reklamir.hasOwnProperty(reklamir_id_new)) {
                    let file = '/' + dir_web + '/' + dir_files + '/' + playlist_new.reklamir[reklamir_id_new].file;
                    comparelist_new.push(file);
                }
            }
            read_all_compare_remove();
        }

        function read_all_compare_remove(dir) {

            fs.root.getDirectory(dir, {}, function (dirEntry) {
                var dirReader = dirEntry.createReader();
                dirReader.readEntries(function (entries) {

                    if (!entries.length) {
                        console.log('Directory Remove: ' + dirEntry.fullPath);
                        folder_delete(dirEntry.fullPath);
                    }
                    for (var i = 0; i < entries.length; i++) {
                        var entry = entries[i];
                        if (entry.isDirectory) {
                            read_all_compare_remove(entry.fullPath);

                            console.log('Directory: ' + entry.fullPath);
                        }
                        else if (entry.isFile) {
                            console.log('File: ' + entry.fullPath);
                            if (!comparelist_new.includes(entry.fullPath)) {
                                console.log('File Remove: ' + entry.fullPath);
                                file_delete(entry.fullPath);
                            }
                        }
                    }

                }, errorHandler);
            }, errorHandler);

        }

        function folder_delete(path) {
            fs.root.getDirectory(path, {}, function (dirEntry) {

                dirEntry.removeRecursively(function () {
                }, errorHandler);

            }, errorHandler);
        }

        function file_delete(file) {
            fs.root.getFile(file, {create: false}, function (fileEntry) {
                fileEntry.remove(function () {
                }, errorHandler);
            }, errorHandler);
        }


        function register_show() {
            $.ajax({
                url: host + "/api/thing/register-show",
                data: {'reklamir_id': reklamir_id, 'lat': lat, 'long': long,'thing_id':thing_id},
            });
        }


        function errorHandler(e) {
            console.log(e);
            /*
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
            */
        }


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


    });
</script>