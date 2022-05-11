<?php

namespace app\modules\VkAPI;

use app\modules\app\common\VkApps;
use app\modules\app\service\VkGroupTokens;
use VkSender\Core;
use App\Service;
use CURLfile;

/**
 * Class VkAPI
 * @package VkSender\VkAPI
 * https://vk.com/dev/manuals
 */
class VkAPI
{
    /**
     * Используемая версия API
     * https://vk.com/dev/versions
     * @var string
     */
    public static $version = '5.85';
    /**
     * Язык по умолчанию
     * @var string
     */
    public static $lang = 'ru';
    /**
     * Произвольная строка, которая будет возвращена вместе с результатом авторизации.
     * @var string
     */
    public static $state = '';
    /**
     * Идентификатор группы ВКонтакте
     * @var int
     */
    public $vk_group_id = 0;
    /**
     * Ключ доступа
     * https://vk.com/dev/authentication
     * @var string
     */
    public $access_token = '';
    /**
     * Адрес отправки запроса
     * @var string
     */
    public $url = '';
    public static $oauth_url = 'https://oauth.vk.com/';
    public static $method_url = 'https://api.vk.com/method/';
    /**
     * Количество запросов в секунду
     * NOT 0, else division by zero!
     * @var int
     */
    public static $requests_per_second = 3;
    public static $requests_per_second_group = 20;
    /**
     * Название метода API, к которому Вы хотите обратиться.
     * https://vk.com/dev/methods
     * @var string
     */
    public $method = '';
    /**
     * Входные параметры соответствующего метода API, последовательность пар name=value, разделенных амперсандом.
     * @var array
     */
    public $params = [];
    /**
     * Формат JSON используется по умолчанию. Вы также можете получать ответы в формате XML, для этого добавьте к названию метода ".xml"
     * @var string
     */
    public $format = '';
    /**
     * Ошибка
     * @var array
     */
    public $error = null;
    /**
     * Идентификатор Вашего приложения (обязательный)
     * @var string
     */
    public $client_id = '';
    /**
     * Защищенный ключ Вашего приложения (указан в настройках приложения) (обязательный)
     * @var string
     */
    public $client_secret = '';
    /**
     * URL, который использовался при получении code на первом этапе авторизации. (обязательный)
     * @var string
     */
    public $redirect_uri = '';
    /**
     * Результат выполнения запроса
     * @var array
     */
    public $response = array();

    /**
     * @var bool Если true - добавлять в очередь rabbit
     */
    public $queue = false;
    public $callback_payload = null;
    public $queue_priority = 10;
    public $queue_direct = false;


    const WRONG_TOKEN = 5;
    const TOO_MANY_REQUESTS = 6;
    const UNKNOWN_ERROR = 10;
    const INVALID_TARGET_GROUP = 100;
    const SHARED_GROUP = 8; // Invalid request: modifying contacts in a shared group is not allowed
    const NO_ACCESS_TARGET = 600;
    const DENIED_SEND_MESSAGES = 901;
    const ACCESS_DENIED = 15;
    const CAPTCHA_NEEDED = 14;
    const TOO_MUCH_CAPTCHA = 9;
    const REVOKE_GROUP_TOKEN = 27; // Ключ доступа сообщества недействителен
    const SERVERS_LIMIT = 2000;
    const NO_DELETE_MESSAGE_FOR_EVERYBODY = 924;

    /**
     * VkAPI constructor.
     * @param $client_id
     * @param $client_secret
     * @param $redirect_uri
     */
    public function __construct($client_id = null, $client_secret = null, $redirect_uri = null)
    {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->redirect_uri = $redirect_uri;
        self::$state = isset($_GET['state']) ? $_GET['state'] : null;
    }

    public function setGroup($vk_group_id)
    {
        $this->vk_group_id = intval($vk_group_id);
        //$this->queue = Core\App::getDeliveryQueue();
    }

    public static function getQueueServerId($vk_group_id)
    {
        return $vk_group_id % count(Core\App::getServers()['vk_producer']);
    }

    public static function getProducerLink($vk_group_id)
    {
        $server_id = self::getQueueServerId($vk_group_id);
        return 'http://' .
            Core\App::getServers()['vk_producer'][$server_id]['ip'] . ':' .
            Core\App::getServers()['vk_producer'][$server_id]['port'];
    }

    public function setToken($access_token)
    {
        $this->access_token = $access_token;
    }

    public function getToken()
    {
        return $this->access_token;
    }

    /**
     * @param $request
     * @return array
     * @throws Exception
     */
    function queue($request)
    {
        $data = [
            'vk_group_id' => (int)$this->vk_group_id,
            'request' => $request,
            'callback' => $this->callback_payload
        ];
        if (($this->callback_payload) && (count($this->callback_payload))) {
            $rabbit_error = false;
            if ($this->queue_direct) {
                try {
                    Core\RabbitMq::publishVk($data);
                } catch (\Exception $e) {
                    $rabbit_error = true;

                    mail(Core\App::getEmailAdmin(), 'Rabbit Error', print_r([
                        'date' => date("c"),
                        'message' => $e->getMessage(),
                        'code' => $e->getCode()
                    ], 1));
                }
            }
            if ((!$this->queue_direct) || ($rabbit_error)) {
                $redis = Core\Redis::getConnection(6);
                $redis_key = 'vk_' . self::getQueueServerId($this->vk_group_id);
                if (IS_LOCAL) $redis_key = 'loc_' . $redis_key;
                $redis->rpush($redis_key, [json_encode($data, JSON_UNESCAPED_UNICODE)]);
            }
            $response = ['response' => 'added to queue'];
        } else {
            $res = Core\Requester::send(
                self::getProducerLink($this->vk_group_id) . '/add',
                json_encode($data),
                ['CURLOPT_HTTPHEADER' => ['Content-Type: application/json'], 'timeout' => 65],
                $error
            );
            if ($error) {
                /*if ($error['code'] == 52) {
                    $error = null;
                    sleep(1);
                    $res = Core\Requester::send(
                        self::getProducerLink($this->vk_group_id) . '/add',
                        json_encode($data),
                        ['CURLOPT_HTTPHEADER' => ['Content-Type: application/json'], 'timeout' => 65],
                        $error
                    );
                }
                if ($error) {*/
                throw new Exception("Curl queue error: {$error['code']} {$error['message']}");
                //}
            }

            $response = json_decode($res, true);
            $e = json_last_error();
            if ($e) {
                $e_msg = json_last_error_msg();
                throw new Exception("Json queue error: $e_msg");
            }

            if (isset($response['error_queue'])) {
                throw new Exception("Producer error: " . (isset($response['message']) ? $response['message'] : json_encode($response,JSON_UNESCAPED_UNICODE)));
            }
        }

        return $response;
    }

    /**
     * Обновление лимита группы
     * @param int $vk_group_id
     * @return bool|array
     */
    static function RefreshGroupLimit($vk_group_id)
    {
        $server_id = self::getQueueServerId($vk_group_id);
        $url = 'http://' . Core\App::getServers()['vk_consumer'][$server_id]['ip'] . ':' .
            Core\App::getServers()['vk_consumer'][$server_id]['port'] .
            "/refresh_group_limit?vk_group_id={$vk_group_id}";
        $res = Core\Requester::send($url);
        return json_decode($res, true);
    }

    function setCallbackPayload($type, $params)
    {
        switch ($type) {
            case 'delivery':
                $this->callback_payload = [
                    //'url' => 'http://' . HOST . '/Commands/VkRequest/Delivery',
                    'url' => 'delivery',
                    'params' => $params
                ];
                break;
            case 'bot':
                $this->callback_payload = [
                    'url' => 'bot',
                    'params' => $params
                ];
                break;
        }
    }

    /**
     * Отправка запроса
     * @return array|mixed
     * @throws Exception
     * @throws Core\Exception\ServiceUnavailable
     */
    private function send()
    {
        // сбрасывание ошибки перед каждым запросом
        $this->error = [];

        if ($this->vk_group_id) {
            if (!$this->queue) {
                $i = 0;
                do {
                    $token = VkGroupTokens::get($this->vk_group_id, true);

                    if ($token['status'] == 'not_exist') throw new Exception("NO TOKEN {$this->vk_group_id}");
                    if ($i++ > 3000) throw new Exception("Time VkException getToken {$this->vk_group_id}"); // Если не удалось втиснуться за 100к циклов (пример пол часа)
                    if ($token['status'] == 'wait') usleep($token['delay'] * 1000000 + 1000); // Выдерживаем от предыдущего запроса + 0,01
                } while ($token['status'] == 'wait');

                if (!$token['token']) throw new Exception("No group token {$this->vk_group_id}");

                $this->setToken($token['token']->vk_token);
            }
        } elseif ($this->client_id) {

            $delay = 1 / self::$requests_per_second;

            $i = 0;
            while (VkApps::getSecondsBetweenLast($this->client_id) < $delay) {
                if ($i++ > 3000) throw new Exception("Time VkException"); // Если не удалось втиснуться за 100к циклов (пример пол часа)
                usleep(10000); // Выдерживаем паузу 0,01 сек
            }
            VkApps::updateLastTime($this->client_id);
        }

        $url = $this->getUrl();

        if ($this->queue) {
            $params = $this->getParamsNew();

            $this->response = $this->queue([
                'params' => $params,
                'url' => $url,
                'priority' => $this->queue_priority
            ]);
        } else {
            $params = $this->getParams();

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

            $ret = curl_exec($curl);
            $e = curl_errno($curl);

            if ($e) {
                $e_msg = curl_error($curl);
                curl_close($curl);
                throw new Exception("Curl VkException #$e ($e_msg): $ret IN $url DATA $params");
            }

            $this->response = json_decode($ret, true);
            $e = json_last_error();

            if ($e) {
                $e_msg = json_last_error_msg();
                curl_close($curl);
                throw new Exception("Json VkException #$e ($e_msg): $ret IN $url DATA $params");
            }
            curl_close($curl);
        }
        return $this->response;
    }

    /**
     * @param $url
     * @param $filename
     * @return array|mixed
     * @throws Exception
     */
    function uploadFile($url, $filename)
    {
        if (!$filename) throw new Exception("Empty Vk Filename");
        if (!file_exists($filename)) throw new Exception("Vk File not found: {$filename}");
        $file = new CURLfile($filename);

        $params = array('file' => $file);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        $ret = curl_exec($curl);
        $info = curl_getinfo($curl);
        $this->response = json_decode($ret, true);

        $e = curl_errno($curl);
        if ($e) {
            $e_msg = curl_error($curl);
            throw new Exception("Curl VkException #$e ($e_msg): $ret IN $url DATA $params");
        }

        $e = json_last_error();
        if ($e) {
            $e_msg = json_last_error_msg();
            throw new Exception("Json VkException #$e ($e_msg): $ret IN $url DATA $params");
        }

        if ($info["http_code"] != 200) {
            throw new Exception("Http VkException #" . $info["http_code"] . ": $ret IN $url DATA $params");
        }

        curl_close($curl);
        return $this->response;
    }

    /**
     * Получение всех параметров для отправки запроса
     * @return string
     */
    public function getParams()
    {
        if (!isset($this->params['v']) || !$this->params['v']) $this->params['v'] = self::$version;
        if (!isset($this->params['lang']) || !$this->params['lang']) $this->params['lang'] = self::$lang;
        if ($this->access_token) $this->params['access_token'] = $this->access_token;
        return http_build_query($this->params);
    }

    /**
     * Получение всех параметров для отправки запроса
     * @return array
     */
    public function getParamsNew()
    {
        if (!isset($this->params['v']) || !$this->params['v']) $this->params['v'] = self::$version;
        if (!isset($this->params['lang']) || !$this->params['lang']) $this->params['lang'] = self::$lang;
        return $this->params;
    }

    /**
     * Получение полного адреса для отправки запроса
     * @return string
     */
    public function getUrl()
    {
        return trim($this->url, "/") . ($this->method ? ("/" . trim($this->method, "/") . $this->format) : '');
    }

    /**
     * Выполнение метода и получение ответа
     * @return array|mixed
     * @throws \Exception
     */
    public function execute()
    {
        try {
            $response = $this->send();

            if (isset($response['error'])) {
                if (is_array($response['error'])) {
                    $this->error = $response['error'];
                    self::handleGroupError($this->vk_group_id, $this->error['error_code']);
                    ex([ $this->error['error_code'], $this->error['error_msg'], $this->vk_group_id]);
//                    Service\Errors::add(
//                        $this->error['error_code'], $this->error['error_msg'], $this->vk_group_id);
                } elseif ($response['error'] == 'invalid_grant') {
                    $this->error = ['error_code' => 0, 'error_msg' => $response['error_description']];
                    // {"error":"invalid_grant","error_description":"Code is invalid or expired."}
                    // to do nothing...
                } else {
                    $this->error = ['error_code' => 0, 'error_msg' => "unknown vk error"];
                    ex(['UNKNOWN_VK_ERROR', $response, $this->vk_group_id]);
                    //Service\Errors::add(Service\Errors::UNKNOWN_VK_ERROR, $response, $this->vk_group_id);
                }
            }
            return $response;
        } catch (\Exception $e) {
            ex([$e->getCode(), $e->getMessage(), $this->vk_group_id]);
            //Service\Errors::add($e->getCode(), $e->getMessage(), $this->vk_group_id);
            throw $e;
        }
    }

    /**
     * Обработка ошибок ВКонтакте
     * @param $vk_group_id
     * @param $error_code
     * @return bool
     * @throws Core\Exception\ServiceUnavailable
     */
    public static function handleGroupError($vk_group_id, $error_code)
    {
        $vk_group_id = intval($vk_group_id);
        if (!$vk_group_id) return false;

        switch ($error_code) {
            case self::WRONG_TOKEN:
            case self::REVOKE_GROUP_TOKEN:
                $group = Service\Group::getGroup2Vk($vk_group_id);
                Service\VkGroupTokens::test($group);
                break;
        }
        return true;
    }
}