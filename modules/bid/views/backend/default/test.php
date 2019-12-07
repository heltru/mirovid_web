<style>
    .active-hour {
        background-color: #538fcd !important;
    }

    .bid_mystatus {
        color: greenyellow;
    }

    .verticalTableHeader {
        text-align: center;
        white-space: nowrap;
        transform-origin: 50% 50%;
        transform: rotate(-90deg);

    }

    .verticalTableHeader:before {
        content: '';
        padding-top: 110%; /* takes width as reference, + 10% for faking some extra padding */
        display: inline-block;
        vertical-align: middle;
    }

    .bid_status_brone {
        color: red;
    }

    .bid_hour_my {
        box-shadow: 0px 0px 0px 3px #5cb85c inset;
    }

    .table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
        padding: 1px;
    }

    .table_header {
        text-align: center;
    }

    .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td {
        border: 1px solid #b1b2b6;
    }
    .bg-green-gradient {
        background: #00a65a !important;
        background: -webkit-gradient(linear, left bottom, left top, color-stop(0, #00a65a), color-stop(1, #00ca6d)) !important;
        background: -ms-linear-gradient(bottom, #00a65a, #00ca6d) !important;
        background: -moz-linear-gradient(center bottom, #00a65a 0%, #00ca6d 100%) !important;
        background: -o-linear-gradient(#00ca6d, #00a65a) !important;
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#00ca6d', endColorstr='#00a65a', GradientType=0) !important;
        color: #fff;
    }
    #calendar td {
       /* border: 1px solid #b1b2b6;*/
    }
</style>
<link rel="stylesheet" href="/js/vanilla-calendar-master/src/css/vanilla-calendar-min.css">
<script src="/js/vanilla-calendar-master/src/js/vanilla-calendar-min.js"></script>

<link rel="stylesheet" href="/js/toastr-master/build/toastr.css">
<script src="/js/toastr-master/toastr.js"></script>


<!-- Date Picker -->
<link rel="stylesheet" href="/js/uxsolutions-bootstrap-datepicker/dist/css/bootstrap-datepicker.css">


<script src="/js/uxsolutions-bootstrap-datepicker/dist/js/bootstrap-datepicker.js"></script>

<div class="box-group" id="accordion">

    <div class="panel box box-primary">
        <div class="box-header with-border">
            <h4 class="box-title">

                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTime" class="" aria-expanded="true">
                    Настроки Time
                </a>

            </h4>
        </div>
        <div id="collapseTime" class="panel-collapse collapse in" aria-expanded="true" style="">
            <div class="box-body">



                <div class="box box-solid bg-green-gradient" style="position: relative; left: 0px; top: 0px;margin-bottom: 10px">
                    <div class="box-header ui-sortable-handle"  >
                        <i class="fa fa-calendar"></i>

                        <h3 class="box-title">Календарь</h3>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body no-padding">
                        <!--The calendar -->
                        <div id="calendar" style="width: 100%">
                        </div>
                    </div>

                </div>








                <div id="date_select_view_block"  >
                    <p class="date_select_view" style="    text-align: center;
    font-size: 21px;
    font-weight: bold;"><?=date('d.m.Y')?></p>
                </div>

                <div id="bid_hours" style="margin-top: 10px">
                    <?= $this->render('_bid_hours', ['bid' => $bid, 'bid_hour' => $bid_hour, 'account_id' => $account_id]) ?>
                </div>

                <div id="bid_minutes" style="margin-top: 15px">
                    <?php //echo $this->render('_bid_minutes',['bid'=>$bid,'bid_minute'=>$bid_minute,'account_id'=>$account_id])?>
                </div>

            </div>
        </div>
    </div>


</div>

<script>

    $(document).ready(function () {



        toastr.options.positionClass = "toast-bottom-right";
        var datetime_active = new Date();
        datetime_active = datetime_active.getTime() / 1000;

        $.fn.datepicker.dates.ru = {
            days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
            daysShort: ["Вск", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб"],
            daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
            months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
            monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
            today: "Сегодня",
            clear: "Очистить",
            format: "dd.mm.yyyy",
            weekStart: 1,
            monthsTitle: "Месяцы"
        }

        $('#calendar').datepicker({

            todayHighlight: true,
            language: 'ru'
        });
        $('#calendar').on('changeDate', function(event) {
            datetime_active = Date.parse(event.date)/1000;
            $('.date_select_view').text( event.format());
            $.ajax({
                type: "POST",
                url: "<?= \yii\helpers\Url::to(['/admin/bid/default/bid-hour-table'])?>",
                data: {_csrfbe:yii.getCsrfToken(),datetime:datetime_active},
                success:function (data) {
                    if (typeof data == 'object'){
                        if (data.status == 'success'){
                            $('#bid_hours').html(data.data);
                            $('#bid_minutes').html('');
                        }
                    }

                }
            });

        });


        $('body').on('click', '.rewrite_price', function (e) {

            if ($(this).prop('checked')) {
                $(this).parent().parent().find('.bid_status').removeClass('fa-circle-o').addClass('fa-circle');
                $(this).parent().parent().find('.myval').attr('disabled', 'disabled');

                let bid_hour = $('#bid_hours').find('.active-hour').attr('data-hour-num');
                let bid_val = $(this).parent().parent().find('.myval').val();
                let bid_minute = $(this).parent().parent().find('.bidtime').attr('data-bid-minute');

                if (!datetime_active) {
                    alert('Выберите дату');
                    return 1;
                }
                if (!bid_hour) {
                    alert('Выберите час');
                    return 1;
                }

                $.ajax({
                    type: "POST",
                    url: "<?= \yii\helpers\Url::to(['/admin/bid/default/bid-rewrite'])?>",
                    data: {
                        _csrfbe: yii.getCsrfToken(), minute_id: bid_minute,
                        hour_id: bid_hour, date: datetime_active, val: bid_val
                    },
                    success: function (data) {

                        if (typeof data == 'object') {
                            if (data.status == 'success') {
                                toastr.success(data.response);
                                $status.removeClass('fa-circle-o').addClass('fa-circle').addClass('bid_status_main');
                                $('#bid_hours').find('.active-hour').addClass('bid_hour_my');

                            }

                        }

                    }
                });

            } else {
                $(this).parent().parent().find('.bid_status').removeClass('fa-circle').addClass('fa-circle-o');
                $(this).parent().parent().find('.myval').removeAttr('disabled');


            }
        });

        $('body').on('click', '.bid_status', function (e) {
            var $status = $(this);
            if ($(this).hasClass('btn-default')) {
                let bid_hour = $('#bid_hours').find('.active-hour').attr('data-hour-num');
                let bid_val = $(this).parent().parent().find('.rate').text();
                let bid_minute = $(this).parent().parent().find('.bidtime').attr('data-bid-minute');

                if (!datetime_active) {
                    alert('Выберите дату');
                    return 1;
                }
                if (!bid_hour) {
                    alert('Выберите час');
                    return 1;
                }

                $.ajax({
                    type: "POST",
                    url: "<?= \yii\helpers\Url::to(['/admin/bid/default/bid-make'])?>",
                    data: {
                        _csrfbe: yii.getCsrfToken(), minute_id: bid_minute,
                        hour_id: bid_hour, date: datetime_active, val: bid_val
                    },
                    success: function (data) {

                        if (typeof data == 'object') {
                            if (data.status == 'success') {
                                toastr.success(data.response);
                                $status.removeClass('btn-default').addClass('btn-success').addClass('bid_status_main');
                                $('#bid_hours').find('.active-hour').addClass('bid_hour_my');
                                $status.attr('data-bid-id',data.data);
                            }

                        }

                    }
                });
            } else {
                if ($(this).hasClass('btn-success')) {
                    $.ajax({
                        type: "POST",
                        url: "<?= \yii\helpers\Url::to(['/admin/bid/default/bid-remove'])?>",
                        data: {
                            _csrfbe: yii.getCsrfToken(), bid_id: $(this).attr('data-bid-id'),
                        },
                        success: function (data) {

                            if (typeof data == 'object') {
                                if (data.status == 'success') {
                                    toastr.error(data.response);
                                    $status.removeClass('btn-success').addClass('btn-default');

                                }

                            }

                        }
                    });
                }
            }
        });

        $('body').on('click', '.bid_hour', function (e) {

            let hour_num = $(this).attr('data-hour-num');
            $('#bid_hours').find('.active-hour').removeClass('active-hour');
            $(this).addClass('active-hour');

            $.ajax({
                type: "POST",
                url: "<?= \yii\helpers\Url::to(['/admin/bid/default/bid-minute-table'])?>",
                data: {_csrfbe: yii.getCsrfToken(), hour_num: hour_num, datetime: datetime_active},
                success: function (data) {
                    if (typeof data == 'object') {
                        if (data.status == 'success') {
                            $('#bid_minutes').html(data.data);
                        }
                    }

                }
            });
        });


    });
</script>