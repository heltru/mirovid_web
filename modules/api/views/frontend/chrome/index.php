<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 11.12.2019
 * Time: 19:51
 */
?>
<style>
    .file-icon {
        width: 20px;
    }
    .folder-icon {
        width: 20px;
    }
    width: 20px;
</style>

<ul id="filelist"></ul>
<button id="btn_download">download</button>
<script>
    const thing_id = 3;
    const host = 'mirovid';
    var fs = null;
    const dir_web = 'mirovid';
    const dir_files = 'files';

    var playlist_new = [];
    var playlist_old = [];

    var comparelist_new = [];
    var comparelist_old = [];

    $(document).ready(function () {

        $('#btn_download').click(function (){
            $.ajax({
                url: 'http://' + host + "/api/thing/playlist?thing_id=2",
                success: (data) => {
                    emitter.emit('event:playlist-download', data);
                }
            });
        } );

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

        ferrorHandler



        emitter.subscribe('event:playlist-download', data => {
            comparelist_new = [];
            comparelist_old = [];
            playlist_new = data;
            comparelist_new_make_playlist_new_save();
        });

        emitter.subscribe('event:playlist_new_save', data => {
            comparelist_old_make_clear_empty_folder();
        });


        function comparelist_old_make_clear_empty_folder() {
            comparelist_old = [];
            emitter.emit('event:comparelist_old_make');
        }

        emitter.subscribe('event:comparelist_old_make', data => {
            compare_list();
        });

        function compare_list(){



            let add_item = arr_diff(comparelist_new,comparelist_old);
            let remove_item = arr_diff(comparelist_old,comparelist_new);

            add_item.forEach((i,v)=>{

              download_file(i);
            });
        }

        function download_file(path){
            let url = 'http://'+host+'/'+dir_web+'/'+path;
           // console.log(url);
            jQuery.ajax({
                url:url,
                cache:false,
                xhr:function(){// Seems like the only way to get access to the xhr object
                    var xhr = new XMLHttpRequest();
                    xhr.responseType= 'blob';
                    return xhr;
                },
                success: function(data,textStatus,jqXHR ){

                    save_file_p(path,data);
                },
                error:function(){

                }
            });
        }

        function save_file_p(path,data) {
            const myPromise = new Promise(function (resolve, reject) {
                let full_path = path;
                let dir =  full_path.split('/');
                dir.splice(-1,1);
                dir = dir.join('/');
                console.log(full_path,dir);

                create_dir(fs.root,dir.split('/'));

                resolve({data:data,path:path});

            }).then((data)=>{   save_file(data.path,data.data)  })
                .catch(handleErrors);
        }

        function create_dir(rootDirEntry, folders) {
            // Throw out './' or '/' and move on to prevent something like '/foo/.//bar'.
            if (folders[0] == '.' || folders[0] == '') {
                folders = folders.slice(1);
            }
            console.log(folders);
            rootDirEntry.getDirectory(folders[0], {create: true}, function(dirEntry) {
                // Recursively add the new subfolder (if we still have another to create).
                if (folders.length) {
                    create_dir(dirEntry, folders.slice(1));
                } else {
                }
            }, errorHandler);
        }


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
                    console.log(data );

                    fileWriter.write(data);

                }, errorHandler);

            }, errorHandler);
        }



        function comparelist_new_make_playlist_new_save() {

            for (reklamir_id in playlist_new.reklamir) {
                if (playlist_new.reklamir.hasOwnProperty(reklamir_id)) {
                    let file = dir_files + '/' + playlist_new.reklamir[reklamir_id].file;
                    playlist_new.reklamir[reklamir_id].file = file;
                    comparelist_new.push(file);
                }
            }
            playlist_new_save();

        }

        emitter.subscribe('event:fileSystem-init', data => {
            directoryList(fs);
        });


        function playlistReadOld() {
            const myPromise = new Promise(function (resolve, reject) {
                navigator.webkitPersistentStorage.requestQuota(1024 * 1024 * 300, () => {
                    window.webkitRequestFileSystem(window.PERSISTENT, 1024 * 1024* 300, (a) => {
                        fs = a;
                        resolve(a);
                    });
                });
            }).then(directoryList)
                .then((result) => {
                    return result;
                })
                .then(playlistReadOldFile)
                .catch(handleErrors);
        }


        function playlistReadOldFile(fs) {
            fs.root.getFile('playlist.json', {}, function (fileEntry) {
                // Get a File object representing the file,
                // then use FileReader to read its contents.
                fileEntry.file(function (file) {
                    let reader = new FileReader();

                    reader.onloadend = function (e) {
                        let txtArea = document.createElement('textarea');
                        txtArea.value = this.result;
                        document.body.appendChild(txtArea);

                        playlist_old = this.result;
                        emitter.emit('event:playlist-read-old', {fs: fs});

                    };

                    reader.readAsText(file);
                }, errorHandler);

            }, errorHandler);

        }


        function playlist_new_save() {

            fs.root.getFile('playlist.json', {create: true, exclusive: false}, function (fileEntry) {

                fileEntry.createWriter(function (fileWriter) {

                    fileWriter.onwriteend = function (e) {
                        //console.log('Write completed.');
                    };
                    fileWriter.onerror = function (e) {
                        console.log('Write failed: ' + e.toString());
                    };

                    let blob = new Blob([playlist_new], {type: "application/json"});
                    fileWriter.write(blob);
                    emitter.emit('event:playlist_new_save');

                }, function (e) {
                    console.log(e)
                });

            }, function (e) {
                console.log(e)
            });

        }


        function handleErrors(error) {
            console.error('Something went wrong ', error)
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

        function toArray(list) {
            return Array.prototype.slice.call(list || [], 0);
        }

        function listResults(entries, fs) {
            // Document fragments can improve performance since they're only appended
            // to the DOM once. Only one browser reflow occurs.
            var fragment = document.createDocumentFragment();

            entries.forEach(function (entry, i) {
                var img = entry.isDirectory ? '<img class="folder-icon" src="/themes/html/folder-icon.png">' :
                    '<img class="file-icon" src="/themes/html/file-icon.png">';
                var li = document.createElement('li');
                li.innerHTML = [img, '<span>', entry.name, '</span>'].join('');
                fragment.appendChild(li);
            });

            document.querySelector('#filelist').appendChild(fragment);


        }

        function directoryList(fs) {
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

        function arr_diff(a1, a2) {
            return a1.filter(x => !a2.includes(x));
        }

    });
</script>