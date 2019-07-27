<?php
/**
 * Created by PhpStorm.
 * User: o.trushkov
 * Date: 20.02.18
 * Time: 12:08
 */

namespace app\modules\payment\app;


class Utils
{
    public static function formatDate(\DateTime $date) {
        $performedDatetime = $date->format("Y-m-d") . "T" . $date->format("H:i:s") . ".000" . $date->format("P");
        return $performedDatetime;
    }
    public static function formatDateForMWS(\DateTime $date) {
        $performedDatetime = $date->format("Y-m-d") . "T" . $date->format("H:i:s") . ".000Z";
        return $performedDatetime;
    }
}