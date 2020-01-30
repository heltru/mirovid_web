
$(document).ready(function() {

    $(".dropdown-trigger").dropdown();

    $('.zapros_form').on('submit',function(e){

        e.preventDefault();
        let url = this.getAttribute('action')||'';
        let obj = {};
        $(this).find('input').each(function(){
            obj[this.name] = this.value;
        });


        $('#zapros_success').modal('open');

        $.ajax({
            url : url,
            data: obj,
            type: 'POST'
        })
            .done(function(){
               // window.dataLayer.push({'event': 'submit'});
            });
    });





});
