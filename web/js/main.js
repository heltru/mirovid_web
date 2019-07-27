/**
 * Created by o.trushkov on 02.06.17.
 */
$(document).ready( function () {

    $('#phone-order').mask("9(999)999-99-99");


    //one-click send fast form
    $('body').on('click', '#fastForm', function(e) {
        e.preventDefault();

        var url = String(  $('#quick-order').find('form').attr('action'));

        var id = $('#quick-order').find('#id_type').val();
        var type = $('#quick-order').find('#type').val();
      //  var cost =  $('#quick-order').find('#cost').val();

        var name =  $('#quick-order').find('#name-order').val();
        var phone =  $('#quick-order').find('#phone-order').val();
        var email =  $('#quick-order').find('#email').val();
        var address =  $('#quick-order').find('#address').val();

        if ( name != '' && phone != ''){
            x = new Date();
            currentTimeZoneOffsetInHours = -x.getTimezoneOffset()/60;
            $.fancybox.open('#success_fastform');

            if (id && type /*&& cost*/ && (  phone || email ) ) {
                $.ajax({
                    url:url,
                    type:"POST",
                    data:{
                        name:name,phone:phone,email:email,address:address,
                        id:id,type:type /*,cost:cost*/,_csrffe:$('meta[name=csrf-token]').attr('content'),
                        gmt:currentTimeZoneOffsetInHours
                    }
                });
                $('#name-order').val('');
                $('#phone-order').val('');
                $('#email').val('');
                $('#address').val('');

            }
        } else {
            if ( ! name && ! phone ){
                alert('Заполните поля ФИО и Телефон');
            } else {
                if ( ! name ){
                    alert('Заполните поле ФИО');
                }
                if ( ! phone ){
                    alert('Заполните поле Телефон');
                }
            }
        }

        return false;
    });



});