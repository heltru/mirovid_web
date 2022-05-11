<?php
namespace app\modules\app\service;

use app\modules\app\AppModule;
use app\modules\app\common\Groups;
use app\modules\helper\models\Helper;
use app\modules\VkAPI\VkOauth;

class VkGroupTokens
{

    static function get($vk_group_id, $need_request = false)
    {
        $arr = [
            'status' => 'success'
        ];

//        $redis = Core\Redis::getConnection(1);
//        $key = self::get_key($vk_group_id);
//        $tokens = $redis->zrange($key, 0, -1, ['WITHSCORES' => true]);

//        if ($tokens) {
//            foreach ($tokens as $token => $time) {
//                if (isset($arr['token'])) {
//                    break;
//                } elseif ($token) {
//                    $arr['token'] = json_decode($token, true);
//                    $arr['time'] = $time;
//                }
//            }
//        }

        if (!isset($arr['token'])) {
            $vk_group_tokens = \app\modules\app\common\VkGroupTokens::find()
                ->where(['vk_group_id' => $vk_group_id])->all();

            if (count($vk_group_tokens)  == 0)
                return ['status' => 'not_exist'];

            $arr['token'] = $vk_group_tokens[0];
            $arr['time'] = 0;

           // $redis->del($key);
            //self::add_cache($vk_group_id, $vk_group_tokens['items']);

        } /*elseif ($need_request) {
            $delay = 1 / self::$requests_per_second_token;
            if (microtime(true) - $delay < $arr['time']) {
                return ['status' => 'wait', 'delay' => $delay];
            } else {
                $redis->zadd($key, [self::get_key_value($arr['token']) => microtime(true)]);
            }
        }*/
        return $arr;
    }


    static function add($group_id, $vk_group_id, $token, $app_id)
    {

        $token = trim($token);
        if (!$token) return false;

        $vk_group_id = intval($vk_group_id);
        if (!$vk_group_id) return false;

        $app_id = intval($app_id);
        if (!$app_id) return false;

        self::get($vk_group_id); // Нужно для синхронизации кэша

        $token_row =  \app\modules\app\common\VkGroupTokens::findOne([
            'vk_group_id' => $vk_group_id,
            'app_id' => $app_id
        ]);


        if ($token_row) {
            //self::delete_cache($vk_group_id, $token_row);
            \app\modules\app\common\VkGroupTokens::deleteAll([
                'vk_group_token_id'=>$token_row->vk_group_token_id]);
            //Model\VkGroupTokens::delete($token_row['vk_group_token_id']); // удление из mysql
        }

        // добавление в mysql
        $vk_group_token_insert = new  \app\modules\app\common\VkGroupTokens();
        $vk_group_token_insert->vk_group_id = $vk_group_id;
        $vk_group_token_insert->vk_token = $token;
        $vk_group_token_insert->date = date("c");
        $vk_group_token_insert->app_id = $app_id;
        if (!$vk_group_token_insert->save()){
            ex($vk_group_token_insert->getErrors());
        }
        $vk_group_token_id = $vk_group_token_insert->vk_group_token_id;

//        $vk_group_token_id = Model\VkGroupTokens::insert([
//            'vk_group_id' => $vk_group_id,
//            'vk_token' => $token,
//            'date' => date("c"),
//            'app_id' => $app_id
//        ], ['ignore' => true]);

        if ($vk_group_token_id) {
//            self::add_cache($vk_group_id, [[
//                'vk_group_token_id' => $vk_group_token_id,
//                'vk_group_id' => $vk_group_id,
//                'vk_token' => $token,
//                'app_id' => $app_id
//            ]]);
        }

//        BotCommand::RefreshGroupLimit($group_id);
      //  VkAPI\VkAPI::RefreshGroupLimit($vk_group_id);
        return true;
    }


}