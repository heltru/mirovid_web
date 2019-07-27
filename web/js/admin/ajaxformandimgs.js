

    function initform(parForm) {

        var formData = null;

        var valid = false;
        var event = document.createEvent('Event'); //document.createEvent('sendingform');// new CustomEvent('sendingform', { valid: false });

        event.initEvent('sendingform', true, true);

        window.addEventListener('writeFiles',
            function (e) {

                var formElement = document.getElementById(  parForm.idformimg/*'brand-update'*/);

                formData = new FormData(formElement);
                //  arrFilesToSend  = sendToEventSubmit;
                console.log( 'begin submit' );
                //    console.log(arrFilesToSend);

                for ( var i=0; i<= arrFilesToSend.length-1;i++){

                    formData.append( arrFilesToSend[i][0], arrFilesToSend[i][1], arrFilesToSend[i][2]);
                    console.log('append');
                    console.log( arrFilesToSend[i][0], arrFilesToSend[i][1], arrFilesToSend[i][2] );
                }
                //   console.log( arrFilesToSend );

                /*  arrFilesToSend.forEach(function(item, i, arr) {
                 console.log(item[0], item[1], item[2]);
                 formData.append(item[0], item[1], item[2]);
                 });*/

                sendFormData();

            }, false);

        $('#'+parForm.idformimg).on('afterValidate', function (e) {
            if ( $('#'+parForm.idformimg).find('.has-error').length ) {
                valid = false;
            } else {
                valid = true;
                console.log('dispatch');
                window.dispatchEvent(event);
            }
        });

        function sendFormData() {

            if (! formData) return null;
            var xhr = new XMLHttpRequest();
            // обработчик для закачки
            //   $('#progressbarcont').removeClass('hidden');
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable)
                {  //evt.loaded the bytes browser receive
                    //evt.total the total bytes seted by the header
                    //
                    var valeur = (e.loaded / e.total)*100;

                    $('.progress-bar').css('width', valeur+'%').attr('aria-valuenow', valeur);
                }

            }
            // обработчики успеха и ошибки
            // если status == 200, то это успех, иначе ошибка
            xhr.onload = xhr.onerror = function() {

                if (this.status == 200) {

                    console.log("success");

                } else {
                    alert('Error sending');
                    console.log("error " + this.status);
                }
            };

            xhr.onreadystatechange = function(  ) {//Call a function when the state changes.
                if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200 || xhr.responseType === "json" ) {
                    // Request finished. Do processing here.,
//console.log(xhr.responseText ); return;
                    var obj = $.parseJSON( xhr.responseText );

                    if (obj.status == 200){
                        // url redirect

                        if ( 'datared' in  obj){

                           // console.log(obj.data);return;
                            window.location.href =  obj.datared;
                          //  location.reload();
                        } else {
                            var url = parForm.redirectUrl;
                            window.location.href = url;// + '1';
                        }


                        /*window.location.href = url+obj.data;
                        location.reload();*/
                    }

                    //alert( obj.status === "John" );
                }/* else {
                 alert('error sending');
                 }*/
            }

            xhr.open("POST", parForm.route , true);
            xhr.send(formData);

        }

        $(document).on('pjax:end', function() {
            $( "#imgRowView" ).sortable({
                items: "tr",
                update:function () {
                    var info = $(this).sortable("serialize",{'attribute':'idsort'});
                    //   console.log(info);
                    $.ajax({
                        type: "POST",
                        url: parForm.imgSortRoute,
                        data: {_csrfbe:yii.getCsrfToken(),info:info},
                        context: document.body,
                    });
                },
                placeholder: "ui-state-highlight-group",
                handle: $(".ui-sortable-handle-imgv")


            });
            $( "#imgRowView" ).disableSelection();
            console.log('init sorter');

        })

        $('#'+parForm.idformimg).submit(function (e) {
            e.preventDefault();
        });

    }

    function initImgs(matrSize) {
        var event = document.createEvent('Event');
        event.initEvent('writeFiles', true, true);


        var arrImg = [];
        var currCountLoadFl = 0;
        var countLoaded=0;

        var fileToUp = [];
        var fileToUpSend = [];

        var sendToEventSubmit= [];

        imgInfo();

        function imgInfo() {
            var cm = 1;
            $.each(matrSize,function (i,v) {
                String(v).split('_')[0]
                $('#matrixTxt').append( $('</p>').text(
                    cm + '. ' + String(v).split('_')[0] + ' x ' + String(v).split('_')[1]
                ) );
                cm ++;
            });
        }

        window.addEventListener('sendingform', function (e) {


            fileToUpSend.forEach(function(item, i, arr) {
                sendToEventSubmit.push(['images[]', item, item.name]);
            });

            arrFilesToSend  = sendToEventSubmit;
            window.dispatchEvent(event);

        }, false);


        function handleFileSelect(evt) {

            var files = evt.target.files; // FileList object

            checkSizeImg(files);

            fileToUp.push(files);
            // fileToUpSend.push(files);

            // Loop through the FileList and render image files as thumbnails.
            for (var i = 0, f; f = files[i]; i++) {
                fileToUpSend.push(f);
                // Only process image files.
                if (!f.type.match('image.*')) {
                    continue;
                }


                var reader = new FileReader();

                // Closure to capture the file information.
                reader.onload = (function(theFile) {
                    return function(e) {
                        var n = currCountLoadFl;

                        var imgg = document.createElement("img");
                        imgg.src  = e.target.result;
                        imgg.className = "thum ";
                        //  imgg.style = " max-height: 305px";
                        imgg.id =  'img_' +  n;
                        arrImg.push({ imgJ: imgg });
                        //
                        var alt = '<label for="imgs_alt" >Alt</label><input name="imgs_alt_'+n+'[]" class="form-control imgAlt" placeholder="alt" >';
                        var tit = '<label for="imgs_title" >Title</label><input name="imgs_title_'+n+'[]" class="form-control imgTit" placeholder="title" >';
                        var wid = '<label for="imgs_width" >Ширина</label><input value="" name="imgs_width_'+n+'[]" class="form-control imgWid" placeholder="ширина" >';
                        var heg = '<label for="imgs_height" >Высота</label><input value="" name="imgs_height_'+n+'[]" class="form-control imgHeg" placeholder="высота" >';
                        var cb = '<input type="checkbox" name="imgs_wat_'+n+'[]"  ><label for="imgs_wat" >Водяной знак</label>';

                        var cln = '<a title="Клон" class="addClone btn btn-default" ><span class="glyphicon glyphicon-share"></span></a>';
                        var btm = '<br><a title="Сетка размеров для картинки" class="addMatrSz btn btn-success" data-num="'+arrImg.length+'" ><span class="glyphicon glyphicon-flash"></span></a>';

                        var rem = '<a title="Удалить" class="rmvBlc btn btn-default rmv" data-nameimg="'+escape(theFile.name)+'" ><span class="glyphicon glyphicon-trash"></span></a>';
                        var hid = '<a title="Скрыть клоны" class="btn btn-default hidCln" ><span class="glyphicon glyphicon-eye-open"></span></a>';
                        var nimg = '<input type="hidden" value="'+escape(theFile.name)+'" name="imgs_name_'+n+'[]" >';
                        //  var sel = makeSelect();

                        var col1 = '<div class="col-xs-12 col-md-6 setImg" style=" margin-top: 1em; margin-bottom: 1em;" >'
                            +nimg+alt+tit+wid+heg+cb+btm+hid+rem+cln+'</div>';

                        var clBox = '<div id="hdbt_'+arrImg.length+'" class="col-xs-12 col-md-12 hdbt"  ><div class="row clBox" ></div></div>';


                        var img = '<img id="img_'+n+'" class="thumb imgprevUp"  data-num="'+n+'" src="' + e.target.result +
                            '" title="' +  escape(theFile.name) +  '"/>';
                        var sort = '<div class="col-md-12 col-md-push-11"><a class="btn btn-default" title="Сортировка">' +
                            '<span class="glyphicon glyphicon-sort ui-sortable-handle-img"></span></a></div>';

                        var col = '<div class="col-xs-12 col-md-6 imgCl" >'+img+'</div>';
                        var row = '<div class="row " style="margin-top: 1em; margin-bottom: 1em;border: dashed 2px rgba(128, 128, 128, 0.2);" > '
                            +sort+col+col1+clBox+' </div>';


                        $('#imgs_cont').append(row);
                        imgg.onload = function(e) {
                            var id = $(this).attr('id');
                            var wid = e.currentTarget.naturalWidth;
                            var heg = e.currentTarget.naturalHeight;
                            $('#'+id).parent().parent().find('.imgWid').val(wid);
                            $('#'+id).parent().parent().find('.imgHeg').val(heg);
                        };
                        currCountLoadFl ++;

                        if ( files.length == currCountLoadFl) {
                            //init sorter after loads imgs
                            $( "#imgs_cont" ).sortable({
                                placeholder: {
                                    element: function(currentItem) {
                                        return $("<div class='col-xs-12' style='background: #fad42e'><hr>СЮДА<hr></div>");
                                    }
                                    ,
                                    update: function(container, p) {
                                        return;
                                    }
                                },
                                handle: $("span.glyphicon.glyphicon-sort"),
                                start: function(e, ui){

                                }
                            });
                        }

                    };
                })(f);

                // Read in the image file as a data URL.
                reader.readAsDataURL(f);
            }

        }

        document.getElementById('fileProduct').addEventListener('change', handleFileSelect, false);

        $('body').on('click', 'a.addClone', function(e) {
            e.preventDefault();

            var $tbody = $(this).parent();

            var cl = $tbody.clone(true);

            cl.find( '.addClone'  ).remove();
            cl.find( '.addMatrSz'  ).remove();
            cl.find( '.hidCln'  ).remove();

            //cl.find( '.imgAlt'  ).remove();

            var img = $(this).parent().parent().find( '.imgCl' ).clone(true);
            img.removeClass( "imgCl" );


            var colb = $('<div/>' , {class:'col-xs-12'} );
            var row = $('<div/>' , {class:'row'} );
            colb.append(row);
            row.append(img ); row.append( cl);

            $(this).parent().parent().find('.clBox' ).append( colb );


        });


        $('body').on('click', 'a.addMatrSz', function(e) {
            e.preventDefault();
            var ins = $(this); var k = 1;
            $.each( matrSize, function( i, val ) {

                var $tbody = ins.parent();

                var cl = $tbody.clone(true);
                cl.find( '.addClone'  ).remove();
                cl.find( '.addMatrSz'  ).remove();
                cl.find( '.hidCln'  ).remove();

                var size = val.split('_');
                cl.find( '.imgWid'  ).val(size[0]);
                cl.find( '.imgHeg'  ).val(size[1]);

                var img = ins.parent().parent().find( '.imgCl' ).clone(true);
                img.removeClass( "imgCl" );


                var resImg = resizeImg( arrImg,size[0],size[1]);//[ ins.attr('data-num') ]

                var numImg = $('<p/>',{style:"font-weight: bold;font-size: 1.2em;padding: 0.5em;    border-bottom: 1.5px solid #d8d8d8;"}).text(k);

                var colb = $('<div/>' , {class:'col-xs-12'} );
                var row = $('<div/>' , {class:'row'} );
                colb.append(row);

                row.append( resImg ); row.append( cl );
                row.append(numImg);
                $(ins.parent().parent().find('.clBox' )).append(  colb );

                k ++;
            });
            $(this).removeClass('btn-success');$(this).addClass('btn-default');
            $(this).parent().parent().find('.hdbt' ).show();
        });

        function resizeImg( img  ,widthN,heightN) {
            //   console.log(img[0].imgJ);return
            var img =  img[0].imgJ;//img.imgJ;
            var canvas = $("<canvas/>")[0];

            var ctx = canvas.getContext("2d");
            ctx.drawImage( img , 0, 0); //arrImg[0].imgJ img

            var MAX_WIDTH = widthN;  //  150
            var MAX_HEIGHT = heightN; // 150

            var width = img.width;
            var height = img.height;

            if (width > height) {
                if (width > MAX_WIDTH) {
                    height *= MAX_WIDTH / width;
                    width = MAX_WIDTH;
                }
            } else {
                if (height > MAX_HEIGHT) {
                    width *= MAX_HEIGHT / height;
                    height = MAX_HEIGHT;
                }
            }

            canvas.width = width;
            canvas.height = height;
            var ctx = canvas.getContext("2d");
            ctx.drawImage( img, 0, 0, width, height);

            var vartemp = $('<div/>' , {class:'col-xs-12 col-md-6'} );
            vartemp.append(canvas);
            return vartemp;
            //  $(this).parent().parent().find('.clBox' ).append( vartemp );
        }


        $('body').on('click', 'a.hidCln', function(e) {
            e.preventDefault();
            var span = $(this).find('span');
            if (span.hasClass('glyphicon-eye-open')){
                console.log(1);
                span.removeClass('glyphicon-eye-open');
                span.addClass('glyphicon glyphicon-eye-close');
                //glyphicon glyphicon-eye-close
            } else {
                span.removeClass('glyphicon glyphicon-eye-close');
                span.addClass('glyphicon glyphicon-eye-open');
            }
            if  ( $(this).parent().parent().find('.hdbt' ).is(":visible") ){
                $(this).parent().parent().find('.hdbt' ).hide();
            } else {

                $(this).parent().parent().find('.hdbt' ).show();
            }


        });

        $('body').on('click', 'a.rmvBlc', function(e) {
            e.preventDefault();

            if  (  $(this).parent().prev().hasClass('imgCl') ) {

                var name =  $(this).attr('data-nameimg');
                // console.log(name);
                $(this).parent().parent().remove();

                removeImg(name);
            } else {
                $(this).parent().parent().parent().remove();
            }


        });

        function removeImg(nameFDel) {
            fileToUpSend = [];

            fileToUp.forEach(function(item, i, arr) {

                for (var i = 0, f; f = item[i]; i++) {
                    //console.log(f.name , 'file');
                    if (  f.name != nameFDel ) {
                        fileToUpSend.push(f);
                    }
                }

            });
            console.log('remFunc fileToUpSend');
            console.log(fileToUpSend );

            // arrFilesToSend = fileToUpSend;

        }

        $('#btnAddImgBl').click(function (e) {
            $.ajax({
                type:"POST",
                url:"<?= Url::to(['product-image/get-bl-img']) ?>",
                data:{_csrfbe:yii.getCsrfToken()},
                success:function (data) {
                    $('#imgs_cont').append(data);
                }
            });
        });
        $('#input-id').on('fileselect', function(event, numFiles, label) {

        });
    }

//});
