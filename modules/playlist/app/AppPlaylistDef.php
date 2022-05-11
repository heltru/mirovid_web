<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 02.12.2019
 * Time: 17:21
 */

namespace app\modules\playlist\app;


use app\modules\reklamir\models\Reklamir;
use yii\helpers\ArrayHelper;

class AppPlaylistDef
{

    private $thing_id;

    private $playlist = [];
    private $balanceStruct = [];

    private $maxAccount = 0;


    public function __construct($thing_id)
    {
        $this->thing_id = $thing_id;
        $this->begin();
    }

    private function begin()
    {
        $this->reklamirSelect();
    }

    private function reklamirSelect()
    {

        $all = Reklamir::find()->
       // joinWith(['file_r', 'area_r'])->
        innerJoin('reklamir_thing','reklamir_thing.reklamir_id=reklamir.id AND reklamir_thing.thing_id = :thing_id',
            ['thing_id' => $this->thing_id])->
        where([ 'status' => Reklamir::ST_ON])->
            orderBy('id')->
        all();

        foreach ($all as $item) {
//            if (!is_object($item->file_r)) {
//                continue;
//            }


            $this->playlist['reklamir'][$item->id] =
                [
                    'reklamir_id' => $item->id,
                    'file' => str_replace('mirovid/files/', '', $item->file),
                    'type'=>  $item->type
//                    'area' => ArrayHelper::getColumn($item->area_r, 'area_id'),
//                    'daytime' => ArrayHelper::getColumn($item->daytime_r, 'time_id')
                ];
            $this->playlist['broadcast'][] =  $item->id;
        }

    }


    public function getPlaylist()
    {
        return $this->playlist;
    }

}