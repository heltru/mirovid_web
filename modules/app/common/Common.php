<?php
namespace app\modules\app\common;

use app\modules\app\AppModule;

class Common
{
    static function getToken($access_token = null)
    {
        if ($access_token) return $access_token;
        if ( AppModule::getUserVkToken()) return AppModule::getUserVkToken();
        return AppModule::getVkAppTech4user();
    }
}