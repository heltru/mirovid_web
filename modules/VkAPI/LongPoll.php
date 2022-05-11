<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 02.08.2017
 * Time: 23:14
 */

namespace app\modules\VkAPI;

/**
 * Class LongPoll
 * @package VkSender\VkAPI
 * https://vk.com/dev/using_longpoll
 */
class LongPoll
{
    /**
     * @var int - версия
     */
    public static $version = 2;

    private $server;
    private $key;
    private $ts;
    private $wait = 25;
    private $mode = 0;

    public function __construct($server, $key, $ts, $wait = null, $mode = null)
    {
        $this->server = $server;
        $this->key = $key;
        $this->ts = $ts;
        if (isset($wait)) $this->wait = $wait;
        if (isset($mode)) $this->mode = $mode;
    }

    private function getUrl()
    {
        return "https://{$this->server}?act=a_check&key={$this->key}&ts={$this->ts}&wait={$this->wait}&mode={$this->mode}&version=" . self::$version;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function execute()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->getUrl());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_POST, false);
        $ret = curl_exec($ch);
        $info = curl_getinfo($ch);

        $response = json_decode($ret, true);
        $e = curl_errno($ch);
        if ($e) {
            $e_msg = curl_error($ch);
            throw new Exception("Curl VkException #$e ($e_msg): $ret");
        }
        $e = json_last_error();
        if ($e) {
            $e_msg = json_last_error_msg();
            throw new Exception("Json VkException #$e ($e_msg): $ret");
        }
        if ($info["http_code"] != 200) {
            throw new Exception("Http VkException #" . $info["http_code"] . ": $ret");
        }
        curl_close($ch);
        return $response;
    }
}