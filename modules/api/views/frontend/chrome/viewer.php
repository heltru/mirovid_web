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


    const thing_id = "<?=Yii::$app->request->get('id') ? Yii::$app->request->get('id') : 3?>";
    const host = '<?=Yii::$app->request->hostInfo?>';
    var fs = null;
    const dir_web = 'mirovid';
    const dir_files = 'files';

    var playlist_new = [];
    var playlist_old = [];

    var comparelist_new = [];
    var comparelist_old = [];

    var img_types = ['png', 'gif', 'jpg'];
    var video_types = ['mp4'];

    var time_delay = 1000 * 60 * 5;

    $(document).ready(function () {



        navigator.webkitPersistentStorage.requestQuota(1024 * 1024 * 300, () => {
            window.webkitRequestFileSystem(window.PERSISTENT, 1024 * 1024 * 300, (a) => {
                fs = a;
                emitter.emit('event:fileSystem-init');
            }, errorHandler);
        }, errorHandler);

        function save_file(path,data) {


            fs.root.getFile(path, {create: true}, function(fileEntry) {

                // Create a FileWriter object for our FileEntry (log.txt).
                fileEntry.createWriter(function(fileWriter) {

                    fileWriter.onwriteend = function(e) {
                        console.log('Write completed.');
                    };

                    fileWriter.onerror = function(e) {
                        console.log('Write failed: ' + e.toString());
                    };
                    fileWriter.write(data);

                }, errorHandler);

            }, errorHandler);
        }

        function create_dir(rootDirEntry, folders) {
            // Throw out './' or '/' and move on to prevent something like '/foo/.//bar'.
            if (folders[0] == '.' || folders[0] == '') {
                folders = folders.slice(1);
            }
            rootDirEntry.getDirectory(folders[0], {create: true}, function(dirEntry) {
                // Recursively add the new subfolder (if we still have another to create).
                if (folders.length) {
                    create_dir(dirEntry, folders.slice(1));
                } else {
                }
            }, errorHandler);
        }

        function create_dirs_save_file(path,data) {
            const myPromise = new Promise(function (resolve, reject) {
                let full_path = path;
                let dir =  full_path.split('/');
                dir.splice(-1,1);
                dir = dir.join('/');
                create_dir(fs.root,dir.split('/'));
                resolve({data:data,path:path});
            }).then((data)=>{   save_file(data.path,data.data)  })
                .catch(errorHandler);
        }



        function file_in_chache(fileName, cb_yes,cb_no) {
            fs.root.getFile(fileName, {create : false}, function(fileEntry) {
              //  console.log( fileEntry.toURL());
                cb_yes(fileEntry.toURL());
            }, function() {
                cb_no(fileName);
            });
        }

        function preseach_img(file) {

            file_in_chache(file, function (file) {
                $('#viewer_img').attr('src', file);
                $('#viewer_img').show();
                console.log('show from chache', file);
                register_show(reklamir_id, 0, 0);
            },function (file){

                $.ajax({
                    url: host+'/'+file,
                    xhrFields:{
                        responseType: 'blob'
                    },
                    success: function(data){
                        var blobData = data;
                        var url = window.URL || window.webkitURL;
                        var src = url.createObjectURL(data);
                        $('#viewer_img').attr("src", src);
                        create_dirs_save_file(file,blobData);
                    }
                });

            });


        }

        function video_tag(file){
            let video = '<video autoplay="autoplay" loop="" preload="auto" muted>' +
                '<source src="/' + file + '" ></video>';
            if (m_flag) {
                let video = ' <video autoplay="autoplay" loop="" preload="auto" controls muted poster="/' + file + '"><source src="/' + file + '"></video>';
            }
            return video;
        }

        function preseach_video(file) {



            $(video_block).html( video_tag(file));
            $('#viewer_video').show();
            console.log('show ', file);
            register_show(reklamir_id, 0, 0);

/*
            $.ajax({
                url: host+'/'+file,
                xhrFields:{
                    responseType: 'blob'
                },
                success: function(data){
                    var blobData = data;
                    var url = window.URL || window.webkitURL;

                    var src = url.createObjectURL(data);
                    console.log( document.querySelector('video') );

//console.log(src);
               //     $(video_block).html( video_tag(src));
                 //   $('#viewer_video').show();
                    //$("#viewer_video source").attr("src",src);

                    //$('#viewer_img').attr("src", src);
                   // create_dirs_save_file(file,blobData);
                }
            });

*/
            /*file_in_chache(file, function (file) {
                $('#viewer_img').attr('src', file);
                $('#viewer_img').show();
                console.log('show from chache', file);
                register_show(reklamir_id, 0, 0);
            },function (file){
                $.ajax({
                    url: host+'/'+file,
                    xhrFields:{
                        responseType: 'blob'
                    },
                    success: function(data){
                        var blobData = data;
                        var url = window.URL || window.webkitURL;
                        var src = url.createObjectURL(data);
                        $('#viewer_img').attr("src", src);
                        create_dirs_save_file(file,blobData);
                    }
                });
            });*/


        }


        async function loop(playlist_new) {

            for (reklamir_id in playlist_new.reklamir) {


                if (playlist_new.reklamir.hasOwnProperty(reklamir_id)) {
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
           // location.reload();

        }


        emitter.subscribe('event:fileSystem-init', data => {

            directoryList();

           // dir_files_clear();

            $.ajax({
                url: host + "/api/thing/playlist?thing_id=" + thing_id,
                success: (data) => {
                    let playlist_new = data;
                    loop(data);
                }
            });

        });

        function folder_delete(path){
            fs.root.getDirectory(path, {}, function(dirEntry) {

                dirEntry.removeRecursively(function() {
                    console.log('Directory removed.');
                }, errorHandler);

            }, errorHandler);
        }
        function file_delete(file) {
            fs.root.getFile(file, {create: false}, function(fileEntry) {
                fileEntry.remove(function() {
                    console.log('File removed.');
                }, errorHandler);
            }, errorHandler);
        }

        function dir_files_clear() {
            let dirReader = fs.root.createReader();
            let entries = [];

            let readEntries = function () {

                dirReader.readEntries(function (results) {
                    if (!results.length) {
                        listResultsClear(entries.sort());
                    } else {
                        entries = entries.concat(toArray(results));
                        readEntries();
                    }
                }, errorHandler);
            };

            return new Promise(function (resolve, reject) {
                readEntries();
                resolve(fs)
            });
        }

        function listResultsClear(entries) {
            entries.forEach(function (entry, i) {
                if (entry.isDirectory){
                    folder_delete(entry.name)
                } else {
                    file_delete(entry.name);
                }
            });
        }


        function listResults(entries) {
            entries.forEach(function (entry, i) {
                console.log(entry.isDirectory ? 'dir ' : 'file ',entry.name);
            });
        }

        function directoryList() {
            let dirReader = fs.root.createReader();
            let entries = [];

            let readEntries = function () {

                dirReader.readEntries(function (results) {
                    if (!results.length) {
                        listResults(entries.sort());
                    } else {
                        entries = entries.concat(toArray(results));
                        readEntries();
                    }
                }, errorHandler);
            };

            return new Promise(function (resolve, reject) {
                readEntries();
                resolve(fs)
            });

        }

        function toArray(list) {
            return Array.prototype.slice.call(list || [], 0);
        }

        function register_show(reklamir_id, lat, long) {
            $.ajax({
                url: host + "/api/thing/register-show",
                data: {'reklamir_id': reklamir_id, 'lat': lat, 'long': long},
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