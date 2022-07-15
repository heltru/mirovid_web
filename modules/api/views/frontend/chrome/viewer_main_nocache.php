<div id="viewer"
     data-thing_id="<?= Yii::$app->request->get('id') ? Yii::$app->request->get('id') : 16 ?>"
     data-host="<?= Yii::$app->request->hostInfo ?>"
>

</div>
<img id="viewer_img">
<div id="viewer_video"></div>
<ul id="filelist"></ul>

<script>

    class Viewer {

        constructor() {

            this.fs = new FileSystem();
            this.img_view = new ImgView(this);
            this.video_view = new VideoView(this);
            this.playlist_new = [];
            this.playlist_old = [];
            this.time_delay = 3000;//1000 * 60 * 1;

            this.viewer = $('#viewer');

            this.thing_id = this.viewer.attr('data-thing_id');
            this.host = this.viewer.attr('data-host');

            this.item = null;

            // navigator.webkitPersistentStorage.requestQuota(1024 * 1024 * 300, () => {
            //     window.webkitRequestFileSystem(window.PERSISTENT, 1024 * 1024 * 300, (a) => {
            //         this.fs.fs = a;
            //         this.load();
            //     }, this.errorHandler);
            // }, this.errorHandler);
            this.load();

        }

        async loop(playlist_new) {

            for (var i = 0; i < playlist_new.broadcast.length; i++) {
                let reklamir_id = playlist_new.broadcast[i];

                if (playlist_new.reklamir.hasOwnProperty(reklamir_id)) {
                    this.item = playlist_new.reklamir[reklamir_id];

                    //let file = this.fs.dir_web + '/' + this.fs.dir_files + '/' + this.item.file;
                    let file =  '/mirovid/files/'+ this.item.file; //this.fs.dir_web + '/' + this.fs.dir_files + '/' + this.item.file;

                    let type = this.item.type;

                    console.log('.loop',file, type);


                    switch (type) {
                        case 'img':
                            this.img_view.preseach_img(file);
                            break;
                    }
                }
                await this.delay(this.time_delay);
              //  this.img_view.hide();
                this.video_view.hide();
            }

          ///  location.reload();

        }


        delay(amount) {
            return new Promise((resolve) => {
                setTimeout(resolve, amount);
            })
        };


        register_show() {
            let reklamir_id = this.item.reklamir_id
            $.ajax({
                url: this.host + "/api/thing/register-show",
                data: {'reklamir_id': reklamir_id, 'lat': 0, 'long': 0},
            });
        }

        load() {
            $.ajax({
                url: this.host + "/api/thing/playlist?thing_id=" + this.thing_id,
                success: (data) => {
                    console.log(data);
                    this.playlist_new = data;
                   // this.fs.dir_files_clear(this.playlist_new);
                    this.loop(data);
                }
            });
        }

        errorHandler(e) {
            console.log('Viewer',e);
        }

    }

    class ImgView {

        constructor(vr) {
            this.vr = vr;
            this.viewer_img = $('#viewer_img');
            this.hide();
        }

        preseach_img(file) {
                  console.log('.preseach_img src_file=',file);
            this.viewer_img.attr("src",file);
            this.viewer_img.show();
            // this.vr.fs.file_in_chache(file, (file) => {
            //     this.viewer_img.attr('src', file);
            //     this.viewer_img.show();
            //     console.log('img_view show from chache', file);
            //     this.vr.register_show();
            // }, (file) => {
            //
            //     console.log('ImgView download','host', this.vr.host, 'file', file);
            //
            //     $.ajax({
            //         url: this.vr.host + '/' + file,
            //         xhrFields: {
            //             responseType: 'blob'
            //         },
            //         success: (data) => {
            //             console.log('isDownloaded',data);
            //             let blobData = data;
            //             let url = window.URL || window.webkitURL;
            //             let src = url.createObjectURL(data);
            //             this.vr.fs.create_dirs_save_file(file, blobData);
            //         }
            //     });
            //
            //     console.log('.preseach_img src_file=',file);
            //     this.viewer_img.attr("src", this.vr.host + '/' + file);
            //     this.viewer_img.show();
            //
            // });
        }

        hide() {
            this.viewer_img.hide();
        }

        errorHandler(e) {
            console.log('ImgView',e);
        }

    }

    class FileSystem {

        constructor() {

            this.fs = null;

            this.dir_web = 'mirovid';
            this.dir_files = 'files';

            this.comparelist_new = [];
            this.comparelist_old = [];

        }

        dir_files_clear(playlist_new) {
            this.comparelist_new = [];

            for (let reklamir_id in playlist_new.reklamir) {
                if (playlist_new.reklamir.hasOwnProperty(reklamir_id)) {
                    let file = '/' + this.dir_web + '/' + this.dir_files + '/' + playlist_new.reklamir[reklamir_id].file;
                    this.comparelist_new.push(file);
                }
            }
            this.read_all_compare_remove();
        }

        read_all_compare_remove(dir) {

            this.fs.root.getDirectory(dir, {}, (dirEntry) => {
                var dirReader = dirEntry.createReader();
                dirReader.readEntries((entries) => {

                    if (!entries.length) {
                        console.log('Directory Remove: ' + dirEntry.fullPath);
                        this.folder_delete(dirEntry.fullPath);
                    }
                    for (var i = 0; i < entries.length; i++) {
                        var entry = entries[i];
                        if (entry.isDirectory) {
                            this.read_all_compare_remove(entry.fullPath);

                            console.log('Directory: ' + entry.fullPath);
                        } else if (entry.isFile) {
                            console.log('File: ' + entry.fullPath);
                            if (!this.comparelist_new.includes(entry.fullPath)) {
                                console.log('File Remove: ' + entry.fullPath);
                                this.file_delete(entry.fullPath);
                            }
                        }
                    }

                }, this.errorHandler);
            }, this.errorHandler);

        }

        folder_delete(path) {
            this.fs.root.getDirectory(path, {},  (dirEntry) => {
                dirEntry.removeRecursively( ()=> {
                }, this.errorHandler);
            }, this.errorHandler);
        }

        file_delete(file) {
            this.fs.root.getFile(file, {create: false},  (fileEntry) => {
                fileEntry.remove( () => {
                }, this.errorHandler);
            }, this.errorHandler);
        }

        file_in_chache(fileName, cb_yes, cb_no) {
            this.fs.root.getFile(fileName, {create: false},  (fileEntry) => {
                cb_yes(fileEntry.toURL());
            }, function () {
                cb_no(fileName);
            });
        }

        create_dirs_save_file(path, data) {


            let dir = path.split('/');
            dir.splice(-1, 1);

            this.rec_create_path({rootDirEntry: this.fs.root, folders: dir})
                .then((result) => {
                    console.log('.create_dirs_save_file result=',result);
                    this.save_file(path, data);
                })
                .catch(function (err) {
                    console.log("oops:" + err);
                });

        }

        save_file(path, data) {


            console.log('.save_file path=',path,' data=',data, ' fs=',this.fs);
            this.fs.root.getFile(path, {create: true},  (fileEntry) =>{
                console.log('.save_file.getFile success');
                // Create a FileWriter object for our FileEntry (log.txt).
                fileEntry.createWriter( (fileWriter) => {

                    fileWriter.onwriteend = function (e) {
                        console.log('Write completed.');
                    };

                    fileWriter.onerror = function (e) {
                        console.log('Write failed: ' + e.toString());
                    };
                    fileWriter.write(data);

                }, this.errorHandler);

            }, this.errorHandler);
        }

        rec_create_path(obj) {
            let decide = (obj) => {
                if (obj.folders.length === 1)
                    return "lift off";
                obj.folders = obj.folders.slice(1);
                return this.rec_create_path(obj); // not all done, recurse
            };
            return this.async_create_dir(obj).then(decide);
        }

        async_create_dir(obj) {
            let rootDirEntry = obj.rootDirEntry;
            let folders = obj.folders;

            return new Promise(  (resolve, reject) => {
                if (folders[0] === '.' || folders[0] === '') {
                    folders = folders.slice(1);
                }
                rootDirEntry.getDirectory(folders[0], {create: true},  (dirEntry) => {
                    resolve({folders: folders, rootDirEntry: dirEntry});
                }, this.errorHandler);
            });
        }

        errorHandler(e) {
            console.log('FileSystem',e);
        }

    }

    class VideoView {

        constructor(vr) {
            this.vr = vr;
            this.viewer_video = $('#viewer_video');
            this.hide();
        }

        preseach_video(file) {
            this.vr.fs.file_in_chache(file, (file) => {
                this.viewer_video.html(this.video_tag(file));
                this.viewer_video.show();
                this.video_full_screen();
                console.log('show ', file);
                this.vr.register_show(reklamir_id, 0, 0);
            }, (file) => {
                $.ajax({
                    url: host + '/' + file,
                    xhrFields: {
                        responseType: 'blob'
                    },
                    success: (blobData) => {
                        this.vr.fs.create_dirs_save_file(file, blobData);
                    }
                });

                this.viewer_video.html(this.video_tag('/' + file));
                this.viewer_video.show();
                console.log('show ', file);
                this.vr.register_show(reklamir_id, 0, 0);
            });
        }

        video_tag(file) {
            let m_flag = false; // отслеживание ширины устройства
            if ($(window).width() <= 768) {
                m_flag = true;
            }
            let video = '<video autoplay="autoplay" loop="" preload="auto" muted>' +
                '<source src="' + file + '" ></video>';
            if (m_flag) {
                let video = ' <video autoplay="autoplay" loop="" preload="auto" controls muted poster="' + file + '">' +
                    '<source src="' + file + '"></video>';
            }
            return video;
        }

        video_full_screen() {
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

        hide() {
            this.viewer_video.hide();
        }

        errorHandler(e) {
            console.log('VideoView',e);
        }

    }



    var img_types = ['png', 'gif', 'jpg'];
    var video_types = ['mp4', 'webm'];


    $(document).ready(function () {

        let app = new Viewer();

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


    });
</script>