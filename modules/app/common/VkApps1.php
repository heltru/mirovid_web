<?php
namespace app\modules\app\common;

use app\modules\app\AppModule;

class VkApps
{
    static function getSecondsBetweenLast($vk_app_id)
    {
        $vk_app = Model\VkApps::get($vk_app_id);
        return (microtime(true) - $vk_app['microtime']);
    }

}