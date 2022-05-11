
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


    const thing_id = "<?=Yii::$app->request->get('id') ? Yii::$app->request->get('id') : 16?>";
    const host = '<?=Yii::$app->request->hostInfo?>';
    var fs = null;
    const dir_web = 'mirovid';
    const dir_files = 'files';

    var playlist_new = [];
    var playlist_old = [];

    var comparelist_new = [];
    var comparelist_old = [];

    var img_types = ['png', 'gif', 'jpg'];
    var video_types = ['mp4', 'webm'];

    var time_delay = 1000 * 60 * 1;

    $(document).ready(function () {


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
                register_show(reklamir_id, 0, 0);
            }, function (file) {

                console.log('host',host,'file',file);
                
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
                video_full_screen();
                console.log('show ', file);
                register_show(reklamir_id, 0, 0);
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
                register_show(reklamir_id, 0, 0);


            });


        }


        async function loop(playlist_new) {

            for (var i = 0; i < playlist_new.broadcast.length; i++) {
                let reklamir_id = playlist_new.broadcast[i];

                if (playlist_new.reklamir.hasOwnProperty(reklamir_id)) {
                    let file = dir_web + '/' + dir_files + '/' + playlist_new.reklamir[reklamir_id].file;
                    //let file_ext = file.split('.').pop();
                    let type = playlist_new.reklamir[reklamir_id].type;

                    console.log(file,type);

                    switch (type) {
                        case 'img':
                            preseach_img(file);
                            break;
                    }

                    // if (img_types.indexOf(file_ext) !== -1) {
                    //     console.log('img_types');
                    //     preseach_img(file);
                    // }
                    // if (video_types.indexOf(file_ext) !== -1) {
                    //     preseach_video(file);
                    // }
                }
                await delay(time_delay);
                $('#viewer_img').hide();
                $('#viewer_video').hide();
            }

            // location.reload();

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
            for (reklamir_id in playlist_new.reklamir) {
                if (playlist_new.reklamir.hasOwnProperty(reklamir_id)) {
                    let file = '/' + dir_web + '/' + dir_files + '/' + playlist_new.reklamir[reklamir_id].file;
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