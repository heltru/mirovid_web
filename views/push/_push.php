<?php
return '';
$domain = Yii::$app->getModule('domain');

if (YII_ENV_DEV || ! $domain->isDefDomain() ) {
    return '';
}
?>
<link rel="manifest" href="/manifest.json">
<script src="https://cdn.onesignal.com/sdks/OneSignalSDK.js" async></script>
<script>
    var OneSignal = window.OneSignal || [];
    OneSignal.push(["init", {
        appId: "4e4ac077-9b39-4d3c-b088-f04c3c90eb49",
        autoRegister: true,
        notifyButton: {
            enable: false /* Set to false to hide */
        },
        welcomeNotification: {
            disable: true
        },
        safari_web_id: 'web.onesignal.auto.4b99c5db-a7c9-461a-8333-facb0838095d'
    }]);
    OneSignal.push(function () {
        OneSignal.on('subscriptionChange', function (isSubscribed) {
            if (isSubscribed === true) {
                OneSignal.getUserId(function (userId) {
                    if (userId != undefined) {
                        (function ($) {
                            $.userId = userId;
                            $.ajax({
                                'url': '/main/default/push-service',
                                'method': 'POST',
                                'data': {
                                    'userId': userId,
                                    'url': $('.url')[0].value,
                                    'sessionId': '<?php echo session_id();?>',
                                    'method': 'insertUser',
                                    '_csrffe':$('meta[name=csrf-token]').attr('content')
                                }
                            })
                        })(jQuery);
                    }
                });
            }
        });
    });

    OneSignal.push(function () {
        OneSignal.getUserId(function (userId) {
            (function ($) {
                $.userId = userId;
            })(jQuery);
        });
    });
</script>