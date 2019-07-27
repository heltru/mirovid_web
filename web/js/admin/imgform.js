function imgform(){

    var countDraw = 0;
    changeFiles = function (e) {

        //clearCont();
        var files = e.target.files; // FileList object

        // Loop through the FileList and render image files as thumbnails.
        for (var i = 0, f; f = files[i]; i++) {

            // Only process image files.
            if (!f.type.match('image.*')) {
                continue;
            }

            var reader = new FileReader();


            // Closure to capture the file information.
            reader.onload = (function(theFile) {
                return function (e) {

                    var imgg = document.createElement("img");
                    imgg.src = e.target.result;
                    $('#tempCont').append($(imgg).css('max-width','250px'));
                    imgg.onload = function(e) {

                        var img = { 'width': e.currentTarget.naturalWidth,
                            'height' : e.currentTarget.naturalHeight,file:theFile, img:$(this),valid:true};

                        drawImgItem(img);

                    };
                }
            })(f);
            reader.readAsDataURL(f);

        }
    };

    function ckeckSize(wD,hD,wO,hO) {
        var valid = true;
        var w_src = wO;
        var h_src = hO;
        var _D_left_right = 0;
        var dest_ratio = wD / hD ;


        if ( w_src > h_src ){
            // h - const
            // w - adt
            var new_w = dest_ratio * h_src ;
            // center align
            _D_left_right = (w_src - new_w) / 2 ;
            if (_D_left_right < 0){

                valid = false;
            }
            // crop setting point with x,y & box with w,h
        } else {
            // w - const
            // h - adt
            var new_h = w_src / dest_ratio;
            // center align
            _D_left_right = (h_src - new_h) / 2 ;
            if (_D_left_right < 0) {
                valid = false;
            }
            // crop setting point with x,y & box with w,h
        }
        return valid;
    }


    function drawImgItem(img) {
        var imgNum = 0;

        $.each(matrSize,function (i,v) {

            var size = String(v).split('_');
            var valid = ckeckSize(size[0],size[1],img.width,img.height);
            var cl = $("#imgTemplate").clone(true);


            if (img.valid){
                cl.css('display','block');
                cl.find('.imgValid').attr('name','Img['+countDraw+'][img_'+imgNum+'][valid]').val(1);
            }

            cl.attr('id','');

            cl.find('.imgAlt').attr('name','Img['+countDraw+'][img_'+imgNum+'][alt]');
            /*if (! valid)  {

            }*/

            cl.find('.imgTitle').attr('name','Img['+countDraw+'][img_'+imgNum+'][title]');
            cl.find('.imgWidth').attr('name','Img['+countDraw+'][img_'+imgNum+'][width]').val(size[0]);
            cl.find('.imgHeight').attr('name','Img['+countDraw+'][img_'+imgNum+'][height]').val(size[1]);
            cl.find('.imgWatermark').attr('name','Img['+countDraw+'][img_'+imgNum+'][watermark]');

            cl.find('.imgValid').attr('name','Img['+countDraw+'][img_'+imgNum+'][valid]').val( Number(valid) );

            cl.find('.imgImg').append(($(img.img).clone(true))) ;
            cl.find('.imgNum').text('Размер ' + size[0] + 'x'+ size[1] );
            cl.find('.imgName').text(img.file.name)
            $('.imgItems').append(cl);
            imgNum ++;
        });
        countDraw ++;
    }

    document.getElementById('files').addEventListener('change', changeFiles, false);

}