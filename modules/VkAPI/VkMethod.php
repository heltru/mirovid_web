<?php

namespace app\modules\VkAPI;

/**
 * Class VkMethod
 * @package VkSender\VkAPI
 * https://vk.com/dev/methods
 */
class VkMethod extends VkAPI
{
    /**
     * @return array|mixed
     * @throws Exception
     */
    private function executeMethod()
    {
        $this->url = self::$method_url;
        $r = $this->execute();

        if (isset($r['error'])) throw new Exception(json_encode($r['error']));
        if (isset($r['execute_errors'])) throw new Exception(json_encode($r['execute_errors'])); // только для метода execute
        if (!isset($r['response'])) throw new Exception("No response VK");

        return $r;
    }

    /**
     * Универсальный метод, который позволяет запускать последовательность других методов, сохраняя и фильтруя промежуточные результаты.
     * @param $code
     * @return mixed
     * @throws Exception
     */
    public function executeCode($code)
    {
        $this->method = 'execute';
        $this->params = array(
            'code' => $code,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает расширенную информацию о пользователях.
     * @param null $user_ids
     * @param array $fields
     * @param null $name_case
     * @return mixed
     * @throws Exception
     */
    public function getUsers($user_ids = null, $fields = array(), $name_case = null)
    {
        $this->method = 'users.get';
        $this->params = array(
            'user_ids' => (is_array($user_ids) ? implode(",", $user_ids) : $user_ids),
            'fields' => implode(",", $fields),
            'name_case' => $name_case,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    public function getWall($owner_id = null, $offset =0, $count = 100, $filter = 'all' ,$extended = 0,
                            $fields = '')
    {
        $this->method = 'wall.get';
        $this->params = array(
            'owner_id' => $owner_id,
            'offset' => $offset,
            'count' => $count,
            'filter' => $filter,
            'extended' => $extended,
            'fields' => $fields
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    public function getStories($owner_id = null, $extended = 0)
    {
        $this->method = 'stories.get';
        $this->params = array(
            'owner_id' => $owner_id,
            'extended' => $extended
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Поиск по пользователям
     * @param null $q
     * @param array $fields
     * @param null $group_id
     * @param null $online
     * @param null $sort
     * @param null $offset
     * @param null $count
     * @param null $city
     * @param null $country
     * @param null $hometown
     * @param null $university_country
     * @param null $university_year
     * @param null $university_faculty
     * @param null $university_chair
     * @param null $sex
     * @param null $status
     * @param null $age_from
     * @param null $age_to
     * @param null $birth_day
     * @param null $birth_month
     * @param null $birth_year
     * @param null $has_photo
     * @param null $school_country
     * @param null $school_city
     * @param null $school_class
     * @param null $school
     * @param null $school_year
     * @param null $religion
     * @param null $interests
     * @param null $company
     * @param null $position
     * @param null $from_list
     * @return mixed
     * @throws Exception
     */
    public function searchUsers($q = null, $fields = array(), $group_id = null, $online = null, $sort = null, $offset = null, $count = null, $city = null, $country = null, $hometown = null,
                                $university_country = null, $university_year = null, $university_faculty = null, $university_chair = null,
                                $sex = null, $status = null, $age_from = null, $age_to = null, $birth_day = null, $birth_month = null, $birth_year = null,
                                $has_photo = null, $school_country = null, $school_city = null, $school_class = null, $school = null,
                                $school_year = null, $religion = null, $interests = null, $company = null, $position = null, $from_list = null)
    {
        $this->method = 'users.search';
        $this->params = array(
            'q' => $q,
            'fields' => implode(",", $fields),
            'group_id' => $group_id,
            'online' => $online,
            'sort' => $sort,
            'offset' => $offset,
            'count' => $count,
            'city' => $city,
            'country' => $country,
            'hometown' => $hometown,
            'university_country' => $university_country,
            'university_year' => $university_year,
            'university_faculty' => $university_faculty,
            'university_chair' => $university_chair,
            'sex' => $sex,
            'status' => $status,
            'age_from' => $age_from,
            'age_to' => $age_to,
            'birth_day' => $birth_day,
            'birth_month' => $birth_month,
            'birth_year' => $birth_year,
            'has_photo' => $has_photo,
            'school_country' => $school_country,
            'school_city' => $school_city,
            'school_class' => $school_class,
            'school' => $school,
            'school_year' => $school_year,
            'religion' => $religion,
            'interests' => $interests,
            'company' => $company,
            'position' => $position,
            'from_list' => $from_list,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Отправка сообщения пользователям
     * @param null $user_id
     * @param null $message
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    public function sendMessages($user_id = null, $message = null, $options = [])
    {
        $this->method = 'messages.send';
        $this->params = array(
            'message' => $message,
        );

        if (is_array($user_id)) {
            $this->params['user_ids'] = implode(",", $user_id);
        } else {
            $this->params['user_id'] = $user_id;
        }

        if (isset($options['attachment'])) $this->params['attachment'] = $options['attachment'];
        if (isset($options['keyboard'])) $this->params['keyboard'] = json_encode($options['keyboard'], JSON_UNESCAPED_UNICODE);
        if (isset($options['captcha_sid'])) $this->params['captcha_sid'] = $options['captcha_sid'];
        if (isset($options['captcha_key'])) $this->params['captcha_key'] = $options['captcha_key'];
        if (isset($options['dont_parse_links'])) $this->params['dont_parse_links'] = $options['dont_parse_links'];

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает информацию видео по их идентификатору
     * @param string $video_ids
     * @return array
     * @throws Exception
     */
    public function getVideoByIds($video_ids = null)
    {
        if (!is_array($video_ids)) $video_ids = [$video_ids];

        $this->method = 'video.get';
        $this->params = array(
            'videos' => implode(',', $video_ids),
            'v' => 5.92,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает информацию фотографий по их идентификатору
     * @param string $photo_ids
     * @return array
     * @throws Exception
     */
    public function getPhotoByIds($photo_ids = null)
    {
        if (!is_array($photo_ids)) $photo_ids = [$photo_ids];

        $this->method = 'photos.getById';
        $this->params = array(
            'photos' => implode(',', $photo_ids),
            'v' => 5.92,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает сообщения по их идентификаторам.
     * @param null $message_ids
     * @param int $preview_length
     * @return mixed
     * @throws Exception
     */
    public function getMessagesByIds($message_ids = null, $preview_length = 0)
    {
        if (!is_array($message_ids)) $message_ids = array($message_ids);

        $this->method = 'messages.getById';
        $this->params = array(
            'message_ids' => $message_ids,
            'preview_length' => $preview_length,
            'v' => 5.78,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Удаляет сообщения.
     * @param null $message_ids
     * @param int $delete_for_all
     * @param null $spam
     * @return mixed
     * @throws Exception
     */
    public function deleteMessages($message_ids = null, $delete_for_all = 0, $spam = null)
    {
        if (!is_array($message_ids)) $message_ids = array($message_ids);

        $this->method = 'messages.delete';
        $this->params = array(
            'message_ids' => $message_ids,
            'spam' => $spam,
            'delete_for_all' => $delete_for_all,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Редактирует сообщение
     * @param int $vk_user_id
     * @param int $message_id
     * @param string $message
     * @param array $options
     * @return mixed
     * @throws Exception
     */
    public function editMessage($vk_user_id, $message_id, $message, $options = [])
    {
        $this->method = 'messages.edit';
        $this->params = array(
            'peer_id' => $vk_user_id,
            'message' => $message,
            'message_id' => $message_id,
            'keep_snippets' => 0,
            'keep_forward_messages' => 0
        );

        if (isset($options['attachment'])) $this->params['attachment'] = $options['attachment'];
        if (isset($options['dont_parse_links'])) $this->params['dont_parse_links'] = $options['dont_parse_links'];

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Устанавливает статус набора текста
     * @param int $group_id
     * @param int $user_id
     * @param string $type typing — пользователь начал набирать текст, audiomessage — пользователь записывает голосовое сообщение
     * @return mixed
     * @throws Exception
     */
    public function setActivity($group_id, $user_id, $type = 'typing')
    {
        $this->method = 'messages.setActivity';
        $this->params = array(
            'user_id' => $user_id,
            'type' => $type,
            'group_id' => $group_id
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Проверить является ли пользователь участником сообщества
     * @param $group_id
     * @param $user_id
     * @return mixed
     * @throws Exception
     */
    public function isMemberGroup($group_id, $user_id)
    {
        $this->method = 'groups.isMember';
        $this->params = array(
            'user_id' => $user_id,
            'group_id' => $group_id
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает информацию о том, разрешена ли отправка сообщений от сообщества пользователю.
     * @param $group_id
     * @param $user_id
     * @return bool
     * @throws Exception
     */
    public function isMessagesAllowed($group_id, $user_id)
    {
        if ($user_id >= 2000000000) return false; // vk bug

        $this->method = 'messages.isMessagesFromGroupAllowed';
        $this->params = array(
            'user_id' => $user_id,
            'group_id' => $group_id,
        );

        $r = $this->executeMethod();
        if (!isset($r['response']['is_allowed'])) return false;
        return $r['response']['is_allowed'];
    }

    /**
     * Возвращает данные, необходимые для подключения к Long Poll серверу.
     * Long Poll позволит Вам моментально узнавать о приходе новых сообщений и других событий.
     * @param $need_pts int - 1 — возвращать поле pts, необходимое для работы метода messages.getLongPollHistory
     * @return mixed
     * @throws Exception
     */
    public function getLongPollServer($need_pts = 0)
    {
        $this->method = 'messages.getLongPollServer';
        $this->params = array(
            'need_pts' => intval($need_pts),
            'lp_version' => intval(LongPoll::$version),
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает историю сообщений для указанного диалога.
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function getHistoryMessages($params)
    {
        $this->method = 'messages.getHistory';
        $this->params = array(
            'offset' => (isset($params['offset']) ? intval($params['offset']) : null),
            'count' => (isset($params['count']) ? intval($params['count']) : null),
            'user_id' => (isset($params['user_id']) ? intval($params['user_id']) : null),
            'peer_id' => (isset($params['peer_id']) ? intval($params['peer_id']) : null),
            'start_message_id' => (isset($params['start_message_id']) ? intval($params['start_message_id']) : null),
            'rev' => (isset($params['rev']) ? intval($params['rev']) : null),
            'extended' => (isset($params['extended']) ? intval($params['extended']) : null),
            'fields' => (isset($params['fields']) ? (array)($params['fields']) : null),
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает историю сообщений для указанного диалога.
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function getMessagesSearch($params)
    {
        $this->method = 'messages.search';
        $this->params = array(
            'offset' => (isset($params['offset']) ? intval($params['offset']) : null),
            'count' => (isset($params['count']) ? intval($params['count']) : null),
            'group_id' => (isset($params['group_id']) ? intval($params['group_id']) : null),
            'peer_id' => (isset($params['peer_id']) ? intval($params['peer_id']) : null),
            'extended' => (isset($params['extended']) ? intval($params['extended']) : null),
            'fields' => (isset($params['fields']) ? (array)($params['fields']) : null),
            'q' => (isset($params['q']) ? (string)($params['q']) : null),
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает список бесед пользователя.
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function getConversations($params)
    {
        $this->method = 'messages.getConversations';
        $this->params = $params;

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает материалы диалога или беседы.
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function getHistoryAttachments($params)
    {
        $this->method = 'messages.getHistoryAttachments';
        $this->params = $params;

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает расширенную информацию о группах пользователях.
     * @param null $user_id
     * @param null $extended
     * @param array $filter
     * @param array $fields
     * @param int $offset
     * @param int $count
     * @return bool
     * @throws Exception
     */
    public function getGroups($user_id = null, $extended = null, $filter = array(), $fields = array(), $offset = 0, $count = 1000)
    {
        $user_id = intval($user_id);
        if (!$user_id) throw new Exception("Empty ID of user " . 'on line ' . __LINE__);

        $this->method = 'groups.get';
        $this->params = array(
            'user_id' => $user_id,
            'extended' => intval($extended),
            'filter' => implode(",", $filter),
            'fields' => implode(",", $fields),
            'offset' => intval($offset),
            'count' => min(1000, intval($count)),
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает расширенную информацию о группах
     * @param $group_ids
     * @param array $fields
     * @return mixed
     * @throws Exception
     */
    public function getGroupByIds($group_ids, $fields = [])
    {
        $this->method = 'groups.getById';
        $this->params = array(
            'group_ids' => (is_array($group_ids) ? implode(",", $group_ids) : $group_ids),
            'fields' => implode(",", $fields),
            'v' => 5.92,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает расширенную информацию о группе
     * @param integer $group_id (Максимальное число идентификаторов — 500.)
     * @param array $fields
     * @return mixed
     * @throws Exception
     */
    public function getGroupById($group_id = null, $fields = [])
    {
        $r = $this->getGroupByIds($group_id, $fields);
        $params = array(
            'group_id' => (is_array($group_id) ? implode(",", $group_id) : $group_id),
            'fields' => implode(",", $fields)
        );

        if (!isset($r[0])) throw new Exception("Empty response on line " . __LINE__ . ' ' . print_r($params, 1));
        return $r[0];
    }

    /**
     * Получение подписчиков группы
     * (!) Если не указано ни одно дополнительное поле, возвращает только массив идентификаторов
     * @param $group_id
     * @param array params
     * @return mixed
     * @throws Exception
     */
    public function getMembers($group_id, $params)
    {
        $this->method = 'groups.getMembers';
        $this->params = $params;
        $this->params['group_id'] = $group_id;

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Получает информацию о серверах для Callback API в сообществе.
     * @param $group_id
     * @param $server_ids
     * @return mixed
     * @throws Exception
     */
    public function getCallbackServers($group_id, $server_ids = array())
    {
        if (!$group_id) throw new Exception("Empty ID of group " . 'on line ' . __LINE__);

        $this->method = 'groups.getCallbackServers';
        $this->params = array(
            'group_id' => intval($group_id),
            'server_ids' => implode(",", $server_ids),
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Добавляет сервер для Callback API в сообщество.
     * @param $group_id
     * @param $url
     * @param $title
     * @param $secret_key
     * @return mixed
     * @throws Exception
     */
    public function addCallbackServer($group_id, $url, $title, $secret_key)
    {
        if (!$group_id) throw new Exception("Empty ID of group " . 'on line ' . __LINE__);

        $this->method = 'groups.addCallbackServer';
        $this->params = array(
            'group_id' => intval($group_id),
            'url' => $url,
            'title' => $title,
            'secret_key' => $secret_key,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Добавляет сервер для Callback API в сообщество.
     * @param $group_id
     * @param $server_id
     * @param $url
     * @param $title
     * @param $secret_key
     * @return mixed
     * @throws Exception
     */
    public function editCallbackServer($group_id, $server_id, $url, $title, $secret_key)
    {
        if (!$group_id) throw new Exception("Empty ID of group " . 'on line ' . __LINE__);

        $this->method = 'groups.editCallbackServer';
        $this->params = array(
            'group_id' => intval($group_id),
            'server_id' => intval($server_id),
            'url' => $url,
            'title' => $title,
            'secret_key' => $secret_key,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Удаляет сервер для Callback API в сообществе.
     * @param $group_id
     * @param $server_id
     * @return mixed
     * @throws Exception
     */
    public function deleteCallbackServer($group_id, $server_id)
    {
        if (!$group_id) throw new Exception("Empty ID of group " . 'on line ' . __LINE__);

        $this->method = 'groups.deleteCallbackServer';
        $this->params = array(
            'group_id' => intval($group_id),
            'server_id' => intval($server_id),
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Позволяет получить строку, необходимую для подтверждения адреса сервера в Callback API.
     * @param $group_id
     * @return mixed
     * @throws Exception
     */
    public function getCallbackConfirmationCode($group_id)
    {
        if (!$group_id) throw new Exception("Empty ID of group " . 'on line ' . __LINE__);

        $this->method = 'groups.getCallbackConfirmationCode';
        $this->params = array(
            'group_id' => intval($group_id),
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Позволяет задать настройки уведомлений о событиях в Callback API.
     * @param $group_id
     * @param $server_id
     * @param $params array
     * @return mixed
     * @throws Exception
     */
    public function setCallbackSettings($group_id, $server_id, $params = [])
    {
        if (!$group_id) throw new Exception("Empty ID of group " . 'on line ' . __LINE__);

        $this->method = 'groups.setCallbackSettings';
        $this->params = $params;

        $this->params['group_id'] = $group_id;
        $this->params['server_id'] = $server_id;

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Позволяет получить настройки уведомлений Callback API для сообщества.
     * @param $group_id
     * @param $server_id
     * @return mixed
     * @throws Exception
     */
    public function getCallbackSettings($group_id, $server_id)
    {
        if (!$group_id) throw new Exception("Empty ID of group " . 'on line ' . __LINE__);

        $this->method = 'groups.getCallbackSettings';
        $this->params = [];

        $this->params['group_id'] = $group_id;
        $this->params['server_id'] = $server_id;
        $this->params['api_version'] = 5.101;

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Устанавливает настройки сообщества
     * @param $group_id
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function setGroupSettings($group_id, $params = [])
    {
        $this->method = 'groups.setSettings';
        $this->params = $params;

        $this->params['group_id'] = $group_id;

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Позволяет получить URL, сокращенный с помощью vk.cc.
     * @param $url string - URL, для которого необходимо получить сокращенный вариант.
     * @param $private integer - 1 — статистика ссылки приватная. 0 — статистика ссылки общедоступная.
     * @return mixed
     * @throws Exception
     */
    public function getShortLink($url, $private = 0)
    {
        $this->method = 'utils.getShortLink';
        $this->params = array(
            'url' => $url,
            'private' => $private,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает информацию о том, является ли внешняя ссылка заблокированной на сайте ВКонтакте.
     * @param $url string - URL, для которого необходимо получить сокращенный вариант.
     * @return mixed
     * @throws Exception
     */
    public function checkLink($url)
    {
        $this->method = 'utils.checkLink';
        $this->params = array(
            'url' => $url,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Получает список сокращенных ссылок для текущего пользователя.
     * @param int $offset
     * @param int $count
     * @return mixed
     * @throws Exception
     */
    public function getLastShortenedLinks($offset = 10, $count = 0)
    {
        $this->method = 'utils.getLastShortenedLinks';
        $this->params = array(
            'offset' => $offset,
            'count' => $count,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Удаляет сокращенную ссылку из списка пользователя.
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    public function deleteFromLastShortened($key)
    {
        $this->method = 'utils.deleteFromLastShortened';
        $this->params = array(
            'key' => $key,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает список стран
     * @param $params
     * @return mixed
     * @throws Exception
     */
    public function getCountries($params)
    {
        $this->method = 'database.getCountries';
        $this->params = $params;

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает список городов
     * @param $params
     * @return mixed
     * @throws Exception
     */
    public function getCities($params)
    {
        $this->method = 'database.getCities';
        $this->params = $params;

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает информацию о городах по их идентификаторам.
     * @param $city_ids
     * @return mixed
     * @throws Exception
     */
    public function getCitiesById($city_ids)
    {
        $this->method = 'database.getCitiesById';
        $this->params = array(
            'city_ids' => $city_ids,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает информацию о городах по их идентификаторам.
     * @param $country_ids
     * @return mixed
     * @throws Exception
     */
    public function getCountriesById($country_ids)
    {
        $this->method = 'database.getCountriesById';
        $this->params = array(
            'country_ids' => $country_ids,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает список вики-страниц в группе.
     * @param $group_id
     * @return mixed
     * @throws Exception
     */
    public function getTitles($group_id)
    {
        $this->method = 'pages.getTitles';
        $this->params = array(
            'group_id' => $group_id,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает адрес сервера для загрузки фотографии в личное сообщение.
     * После успешной загрузки Вы можете сохранить фотографию, воспользовавшись методом photos.saveMessagesPhoto.
     * @param $peer_id
     * @return mixed
     * @throws Exception
     */
    public function getMessagesUploadServerPhoto($peer_id = null)
    {
        $this->method = 'photos.getMessagesUploadServer';
        $this->params = array(
            'peer_id' => $peer_id,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * @param $photo
     * @param $server
     * @param $hash
     * @return mixed
     * @throws Exception
     */
    public function saveMessagesPhoto($photo, $server, $hash)
    {
        $this->method = 'photos.saveMessagesPhoto';
        $this->params = array(
            'photo' => $photo,
            'server' => $server,
            'hash' => $hash,
            'v' => 5.101,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Получает адрес сервера для загрузки документа в личное сообщение.
     * @param $peer_id
     * @param $type
     * @return mixed
     * @throws Exception
     */
    public function getMessagesUploadServerDoc($peer_id = null, $type = 'doc')
    {
        $this->method = 'docs.getMessagesUploadServer';
        $this->params = array(
            'peer_id' => $peer_id,
            'type' => $type,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * @param $file
     * @param $title
     * @param $tags
     * @return mixed
     * @throws Exception
     */
    public function saveDoc($file, $title = null, $tags = null)
    {
        $this->method = 'docs.save';
        $this->params = array(
            'file' => $file,
            'title' => $title,
            'tags' => $tags,
            'v' => 5.103,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает список рекламных кабинетов.
     * @return mixed
     * @throws Exception
     */
    public function getAdsAccounts()
    {
        $this->method = 'ads.getAccounts';
        $this->params = array();

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает список клиентов рекламного агентства.
     * Доступно только для рекламных агентств.
     * @param $account_id
     * @return mixed
     * @throws Exception
     */
    public function getAdsClients($account_id)
    {
        $this->method = 'ads.getClients';
        $this->params = array(
            'account_id' => $account_id,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает список аудиторий ретаргетинга.
     * @param $account_id
     * @param null $client_id
     * @return mixed
     * @throws Exception
     */
    public function getTargetGroups($account_id, $client_id = null)
    {
        $this->method = 'ads.getTargetGroups';
        $this->params = array(
            'account_id' => $account_id,
        );

        if ($client_id) $this->params['client_id'] = $client_id;

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Импортирует список контактов рекламодателя для учета зарегистрированных во ВКонтакте пользователей в аудитории ретаргетинга.
     * Максимально допустимое количество контактов, импортируемых с помощью одного запроса — 1000.
     * @param $account_id
     * @param $client_id
     * @param $target_group_id
     * @param $contacts
     * @return mixed
     * @throws Exception
     */
    public function importTargetContacts($account_id, $client_id, $target_group_id, $contacts)
    {
        $this->method = 'ads.importTargetContacts';
        $this->params = array(
            'account_id' => $account_id,
            'target_group_id' => $target_group_id,
            'contacts' => implode(",", is_array($contacts) ? $contacts : [$contacts]),
        );

        if ($client_id) $this->params['client_id'] = $client_id;

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Возвращает информацию о текущем состоянии счетчика — количество оставшихся запусков методов и время до следующего обнуления счетчика в секундах.
     * @param $account_id
     * @return mixed
     * @throws Exception
     */
    public function getFloodStats($account_id)
    {
        $this->method = 'ads.getFloodStats';
        $this->params = array(
            'account_id' => $account_id,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Определяет тип объекта (пользователь, сообщество, приложение) и его идентификатор по короткому имени screen_name.
     * @param $screen_name
     * @return mixed
     * @throws Exception
     */
    public function resolveScreenName($screen_name)
    {
        $this->method = 'utils.resolveScreenName';
        $this->params = array(
            'screen_name' => $screen_name,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Позволяет получить адрес для загрузки фотографии в коллекцию сообщества для виджетов приложений сообществ.
     * @param $image_type
     * @return mixed
     * @throws Exception
     */
    public function getWidgetImageUploadServer($image_type)
    {
        $this->method = 'appWidgets.getGroupImageUploadServer';
        $this->params = array(
            'image_type' => $image_type
        );

        $r = $this->executeMethod();
        return $r['response'];
    }

    /**
     * Позволяет получить адрес для загрузки фотографии в коллекцию сообщества для виджетов приложений сообществ.
     * @param $hash
     * @param $image
     * @return mixed
     * @throws Exception
     */
    public function saveWidgetGroupImage($hash, $image)
    {
        $this->method = 'appWidgets.saveGroupImage';
        $this->params = array(
            'hash' => $hash,
            'image' => $image,
        );

        $r = $this->executeMethod();
        return $r['response'];
    }
}