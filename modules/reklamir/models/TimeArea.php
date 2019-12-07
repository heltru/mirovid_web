<?php

namespace app\modules\reklamir\models;

use app\modules\account\models\Account;
use app\modules\file\models\File;
use app\modules\file\models\FilePreview;
use Yii;
use yii\base\Model;

/**
 * This is the model class for table "reklamir".
 *
 * @property int $id
 * @property int $thing_id
 * @property int $file_id
 * @property int $account_id
 * @property int $show
 * @property int $status
 * @property int $name
 */
class TimeArea extends Model
{


    public static $days_show = [
        1 => 'ПН',
        2 => 'ВТ',
        3 => 'СР',
        4 => 'ЧТ',
        5 => 'ПТ',
        6 => 'СБ',
        7 => 'ВС'
    ];

    public static $times_show = [
        1 => ['00:00','01:00'], 2 => ['01:00', '02:00'], 3 => ['02:00', '03:00'], 4 => ['03:00', '04:00'],
        5 => ['04:00', '05:00'], 6 => ['05:00', '06:00'], 7 => ['06:00', '07:00'], 8 => ['07:00', '08:00'],
        9 => ['08:00', '09:00'], 10 => ['09:00', '10:00'], 11 => ['10:00', '11:00'], 12 => ['11:00', '12:00'],
        13 => ['12:00', '13:00'], 14 => ['13:00', '14:00'], 15 => ['14:00', '15:00'], 16 => ['15:00', '16:00'],
        17 => ['16:00', '17:00'], 18 => ['17:00', '18:00'], 19 => ['18:00', '19:00'], 20 => ['19:00', '20:00'],
        21 => ['20:00', '21:00'], 22 => ['21:00', '22:00'], 23 => ['22:00', '23:00'], 24 => ['23:00', '00:00'],

    ];


}
