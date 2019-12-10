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

class AppPlaylist
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
        $this->reklamirBalance();


    }


    private function reklamirBalance()
    {


        for ($j = 0; $j <= $this->maxAccount-1; $j++) {

            foreach ($this->balanceStruct as $num => $item_struct) {

                $this->addBroadcastItem($item_struct['reklamir_id'][$item_struct['index_active']]);

                if ((count($item_struct['reklamir_id']) - 1) == $item_struct['index_active']) {
                    $this->balanceStruct[$num]['index_active'] = 0;
                } else {
                    $this->balanceStruct[$num]['index_active'] = (int)$this->balanceStruct[$num]['index_active'] + 1;
                }

            }
        }

    }


    private function reklamirSelect()
    {

        $all = Reklamir::find()->
        joinWith(['file_r', 'area_r'])->
        where(['thing_id' => $this->thing_id, 'status' => Reklamir::ST_ON])->
        all();

        foreach ($all as $item) {
            if (!is_object($item->file_r)) {
                continue;
            }


            $this->balanceStruct[$item->account_id]['reklamir_id'][] = $item->id;
            $this->balanceStruct[$item->account_id]['index_active'] = 0;

            $countReklamirInAccount = count($this->balanceStruct[$item->account_id]['reklamir_id']);
            if ($countReklamirInAccount > $this->maxAccount) {
                $this->maxAccount = $countReklamirInAccount;
            }

            $this->playlist['reklamir'][$item->id] =
                [
                    'reklamir_id' => $item->id,
                    'file' => str_replace('mirovid/files/', '', $item->file_r->path),
                    'area' => ArrayHelper::getColumn($item->area_r, 'area_id'),
                    'daytime' => ArrayHelper::getColumn($item->daytime_r, 'time_id')
                ];
        }

    }


    private function addBroadcastItem($id)
    {
        $this->playlist['broadcast'][] = $id;
        $this->broadcast[] = $id;
    }

    public function getPlaylist()
    {
        return $this->playlist;
    }

}