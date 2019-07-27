
function initurlform(params){


    $('#btnGenerHref').click(function (e) {

        makeHref();
    });

    $('#btnGenerWrHref').click(function (e) {

        makeHrefW();
    });

    function makeHrefW(){
        var txt = $('#'+ params.fieldTxt).val();
        var oldHref = $('#old_href').val();
      //  console.log(txt);
        if (txt){
            $.ajax({
                type:"POST",
                url:  params.url,
                data:{txt:txt,oldHref:oldHref,_csrfbe:yii.getCsrfToken()},
                success:function (data) {
                    if (typeof data == 'object'){
                        if (data.message == 'error'){
                            $('#'+ params.form).yiiActiveForm('updateAttribute', 'url-href', ["Такой url уже есть"]);
                        }
                        if (data.status == 200){
                            $('#fieldHref').val(data.data);
                        }
                    }
                }
            });
        }

    }

    function makeHref(){
        $('#hrefInfo').hide();
        var txt =  $('#'+ params.fieldTxt).val();
        var oldHref = $('#old_href').val();
        if (txt){
            $.ajax({
                type:"POST",
                url:params.url,
                data:{txt:txt,oldHref:oldHref,_csrfbe:yii.getCsrfToken()},
                success:function (data) {
                    if (typeof data == 'object'){
                        if (data.message == 'error'){
                            $('#hrefInfo').show();
                            $('#'+ params.form).yiiActiveForm('updateAttribute', 'url-href', ["Не уникальный «Url»."]);
                        }
                        if (data.status == 200){

                            $('#fieldHref').val(data.data);
                        }
                    }
                }
            });
        }
    }
}

