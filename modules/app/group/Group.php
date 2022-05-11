<?php
namespace app\modules\app\group;

use app\modules\app\AppModule;
use app\modules\app\common\Groups;
use app\modules\helper\models\Helper;
use app\modules\VkAPI\Exception;
use app\modules\VkAPI\VkMethod;
use app\modules\VkAPI\VkOauth;

class Group
{
    static $fields = array('members_count', 'verified'/*, 'site', 'description'*/);

    static $vk_callback_ver = 5.103;

    static function getAuthLink($vk_group_id, $app_id = 0, $return = '')
    {
        $vk_group_id = intval($vk_group_id);
        if (!$vk_group_id) return false;

        $vk = new VkOauth( AppModule::getVkAppId4auth($app_id),
            AppModule::getVkAppSecret4auth($app_id),
            AppModule::getVkAppRedirect($app_id, $return));
        return $vk->getLink(array('messages', 'manage', 'photos', 'docs', 'stories'), 'group_' . $vk_group_id, [$vk_group_id]);
    }

    static function getPhoto2Vk($vk_group)
    {
        if (!$vk_group) return '';
        if (!isset($vk_group['photo_200'])) return '';

        return $vk_group['photo_200'];
    }

    static function update2Vk($vk_group, $user_id = null)
    {
        if (!$vk_group) return false;

        $params = array(
            'vk_id' => intval($vk_group['id']),
            'vk_photo' => self::getPhoto2Vk($vk_group),
            'disabled' => 0,
            'data_deleted' => 0,
            'can_message' => 1,
        );

        // sanitize data
        if (isset($vk_group['name'])) $params['name'] =  Helper::sanitizeString($vk_group['name']);
        if (isset($vk_group['screen_name'])) $params['screen_name'] = Helper::sanitizeString($vk_group['screen_name']);
//        if (isset($vk_group['site'])) $params['site'] = Core\Helper::sanitizeURL($vk_group['site']);
//        if (isset($vk_group['description'])) $params['description'] = Core\Helper::sanitizeString($vk_group['description']);
        if (isset($vk_group['type'])) $params['type'] = Helper::sanitizeString($vk_group['type']);
        if (isset($vk_group['verified'])) $params['verified'] = intval($vk_group['verified']);
        if (isset($vk_group['members_count'])) $params['members_count'] = intval($vk_group['members_count']);
        if (isset($vk_group['is_closed'])) $params['is_closed'] = intval($vk_group['is_closed']);



        $group = self::getGroup2Vk($vk_group['id']);

        if ($group) {
            Group::updateGroup($params, $group['group_id']);

//            if ($group['disabled'] == 1 && $user_id && $group['user_id'] != $user_id) {
//                // Передача другому администратору
//                Group::transfer($group, User::getUser($user_id));
//            }

            return self::getGroup($group['group_id']);
        } else {
            $params['callback_key'] = self::gen_key($params['vk_id']); // set callback_key only on created
            $params['user_id'] = intval($user_id);
            $params['date'] = date("Y-m-d H:i:s");
            $group_new = new Groups();
            Helper::assoc_model($params, $group_new);
            if (!$group_new->save()){
                ex($group_new->getErrors());
            }
            $group_id = $group_new->group_id; // Model\Groups::insert($params);
            return self::getGroup($group_id);
        }
    }

    static function gen_key($vk_id)
    {
        return substr(hash("sha1", $vk_id . "adf&^(sdf%") . rand(100000, 999999), 0, 50);
    }

    static function getGroup2Vk($vk_id, $cache = true)
    {
        $vk_id = intval($vk_id);
        if (!$vk_id) return false;
//
//        $redis = Core\Redis::getConnection(1, 1);
//        $key = Group::get_redis_key($vk_id);

//        if ($cache && $key) {
//            try {
//                $json_group = $redis->get($key);
//                if ($json_group) return json_decode($json_group, true);
//            } catch (Exception $e) {
//                mail(Core\App::getEmailAdmin(), 'Redis Error', print_r([
//                    'date' => date("c"),
//                    'e' => $e,
//                ], 1));
//            }
//        }

        $group = Groups::findOne(['vk_id' => $vk_id]);

//        if ($group && $key) {
//            try {
//                $redis->set($key, json_encode($group, JSON_UNESCAPED_UNICODE), 'EX', 3600 * 24 * 7);
//            } catch (Exception $e) {
//                mail(Core\App::getEmailAdmin(), 'Redis Error', print_r([
//                    'date' => date("c"),
//                    'e' => $e,
//                ], 1));
//            }
//        }

        return $group;
    }

    static function checkLinkVerifyCallback($url)
    {
        return preg_match('~://(mirovid\.ru)/(api/callback|webhook/vk)~', $url);
    }

    static function updateGroup($params, $group_id)
    {
        $group_id = intval($group_id);

        if (!$params) return false;
        if (!$group_id) return false;

        $group = self::getGroup($group_id);
        if (!$group) return false;
//
//        $redis = Core\Redis::getConnection(1, 1);
//        $key = Group::get_redis_key($group['vk_id']);
//        if ($redis->exists($key)) {
//            $redis->del($key);
//        }

        Helper::assoc_model($params, $group);
        if ( $group->update(false) ===false) ex($group->getErrors());

        return $group->group_id; // Model\Groups::update($params, $group_id);
    }

    static function getGroup($group_id)
    {
        $group_id = intval($group_id);
        if (!$group_id) return false;

        return Groups::findOne(['group_id'=>$group_id]);
    }

    static function setVkSettings($group)
    {
        $result = [];
        if (!$group) return $result;
        if ($group['disabled']) return $result;

        $vk = new VkMethod(AppModule::getVkAppId4auth());
        $vk->setGroup($group['vk_id']);

        try {
            $servers = $vk->getCallbackServers($group['vk_id']); // servers
            $confirmation = $vk->getCallbackConfirmationCode($group['vk_id']); // get code
            Group::updateGroup(['confirmation_token' => $confirmation['code']], $group['group_id']);

            $server = false;
            foreach ($servers['items'] as $item) {
                if (!$server && $item['title'] == AppModule::getName() && $item['status'] == 'ok') {
                    $server = $item; // берем первый успешный сервер
                } elseif (Group::checkLinkVerifyCallback($item['url'])) {
                    $vk->deleteCallbackServer($group['vk_id'], $item['id']); // остальные удаляем, чтобы не ДДосили
                }
            }

            if ($server) {
                $server_id = $server['id'];
                $result['editCallbackServer'] = $vk->editCallbackServer($group['vk_id'], $server_id,
                    AppModule::getLinkVerifyCallback($group['group_id']),
                    AppModule::getName(), $group['callback_key']);
            } else {
                $result['addCallbackServer'] = $response_3 = $vk->addCallbackServer($group['vk_id'],
                   AppModule::getLinkVerifyCallback($group['group_id']),
                    AppModule::getName(), $group['callback_key']);
                $server_id = $response_3['server_id'];
            }

            $result['getCallbackServers'] = $servers;
            $result['getCallbackConfirmationCode'] = $confirmation;

            $result['setGroupSettings'] = $vk->setGroupSettings($group['vk_id'], [
                'messages' => 1,
                'bots_capabilities' => 1,
            ]);
            $result['setCallbackSettings'] = $vk->setCallbackSettings($group['vk_id'], $server_id, [
                'message_new' => 1,
                'message_allow' => 1,
                'message_deny' => 1,
                'vkpay_transaction' => 1,
                'lead_forms_new' => 1,
                'group_change_photo' => 1,
                'user_block' => 1,
                'api_version' => Group::$vk_callback_ver,
            ]);
        } catch ( Exception $e) {
            ex([
                $vk->error['error_msg'], $vk->error['error_code']
            ]);
            //throw new Core\Exception\Ajax($vk->error['error_msg'], $vk->error['error_code']);
        }

        return $result;
    }

}