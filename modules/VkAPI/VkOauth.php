<?php

namespace app\modules\VkAPI;

/**
 * Class VkOauth
 * @package VkSender\VkAPI
 * https://vk.com/dev/authcode_flow_user
 */
class VkOauth extends VkMethod
{
    /**
     * @return array|mixed
     * @throws \Exception
     */
    private function executeOauth()
    {
        $this->url = self::$oauth_url;
        return $this->execute();
    }

    /**
     * Запрос ключа доступа (для пользователя возвращает его идентификатор)
     * @return int
     * @throws \Exception
     */
    public function requestToken()
    {
        if (isset($_GET['error_description'])) throw new Exception($_GET['error_description']);
        if (isset($_GET['error_reason'])) throw new Exception($_GET['error_reason']);
        if (isset($_GET['error'])) throw new Exception($_GET['error']);

        if (empty($_GET['code'])) throw new Exception("No user code");

        $this->url = self::$oauth_url;
        $this->method = 'access_token';
        $this->params = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'code' => $_GET['code'],
        ];

        $response = $this->executeOauth();
        if (empty($response['user_id'])) throw new Exception("No user id");
        if (empty($response['access_token'])) throw new Exception("No user token");

        $this->setToken($response['access_token']);
        return (int)$response['user_id'];
    }

    /**
     * Получение ключа доступа сообщества
     * @param $vk_group_id
     * @throws \Exception
     */
    public function requestGroupToken($vk_group_id)
    {
        $vk_group_id = intval($vk_group_id);
        if (empty($vk_group_id)) throw new Exception("No group id");

        if (isset($_GET['error_description'])) throw new Exception($_GET['error_description']);
        if (isset($_GET['error_reason'])) throw new Exception($_GET['error_reason']);
        if (isset($_GET['error'])) throw new Exception($_GET['error']);

        if (empty($_GET['code'])) throw new Exception("No group code");

        $this->url = self::$oauth_url;
        $this->method = 'access_token';
        $this->params = [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'code' => $_GET['code'],
        ];

        $response = $this->executeOauth();
        if (empty($response['access_token_' . $vk_group_id])) throw new Exception("No group token");

        $this->setToken($response['access_token_' . $vk_group_id]);
    }

    /**
     * Получение ссылки для авторизации
     * @param string $display
     * @param array $scope
     * @param null $state
     * @param array $group_ids
     * @return string
     */
    public function getLink($scope = [], $state = null, $group_ids = [], $display = 'page')
    {
        $this->url = self::$oauth_url;
        $this->method = 'authorize';
        $this->params = [
            'client_id' => $this->client_id,
            'redirect_uri' => $this->redirect_uri,
            'display' => $display,
            'group_ids' => implode(",", $group_ids),
            'response_type' => 'code',
            'scope' => implode(",", $scope),
            'state' => $state
        ];
        return $this->getUrl() . "?" . $this->getParams();
    }
}