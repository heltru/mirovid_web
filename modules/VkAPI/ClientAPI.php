<?php

namespace app\modules\VkAPI;

use VkSender;

/**
 * Class ClientAPI
 * @package VkSender\ClientAPI
 * https://vk.com/dev/community_apps_docs
 */
class ClientAPI
{
    public static function checkAuth($params, $secret)
    {
        if (!$params) return false;
        if (!$secret) return false;

        $sign = '';
        if (is_array($params)) {
            foreach ($params as $key => $param) {
                if ($key == 'hash' || $key == 'sign' || $key == 'api_result' || $key == '_url') continue;
                $sign .= $param;
            }
        }
        $sig = hash_hmac('sha256', $sign, $secret);
        return (isset($params['sign']) && $params['sign'] == $sig);
    }

    public static function buildLink($link)
    {
        $params = self::getParams();
        return $link . ($params ? '?' . http_build_query($params) : '');
    }

    public static function getParams()
    {
        $params = array();
        if (!empty($_GET)) {
            $params = $_GET;
            if (isset($params['_url'])) unset($params['_url']);
        }
        return $params;
    }
}
