<?php
namespace app\modules\VkAPI;

use VkSender;

/**
 * Class OpenAPI
 * @package VkSender\OpenAPI
 * https://vk.com/dev/openapi
 */
class OpenAPI
{
    public static function auth($app_id, $secret)
    {
        if (!isset($_COOKIE['vk_app_' . $app_id])) return FALSE;
        return self::parseCookie($_COOKIE['vk_app_' . $app_id], $secret);
    }

    public static function parseCookie($cookie, $secret)
    {
        $session = array();
        $member = FALSE;
        $valid_keys = array('expire', 'mid', 'secret', 'sid', 'sig');
        if ($cookie) {
            $session_data = explode('&', $cookie, 10);
            foreach ($session_data as $pair) {
                list($key, $value) = explode('=', $pair, 2);
                if (empty($key) || empty($value) || !in_array($key, $valid_keys)) {
                    continue;
                }
                $session[$key] = $value;
            }
            foreach ($valid_keys as $key) {
                if (!isset($session[$key])) return $member;
            }
            ksort($session);

            $sign = '';
            foreach ($session as $key => $value) {
                if ($key != 'sig') {
                    $sign .= ($key . '=' . $value);
                }
            }
            $sign .= $secret;
            $sign = md5($sign);
            if ($session['sig'] == $sign && $session['expire'] > time()) {
                $member = array(
                    'id' => intval($session['mid']),
                    'secret' => $session['secret'],
                    'sid' => $session['sid']
                );
            }
        }
        return $member;
    }
}
