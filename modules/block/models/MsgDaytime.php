<?php

namespace app\modules\block\models;

use Yii;

/**
 * This is the model class for table "msg_daytime".
 *
 * @property int $id
 * @property int $msg_id
 * @property int $time_id
 */
class MsgDaytime extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'msg_daytime';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['msg_id', 'time_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'msg_id' => 'Msg ID',
            'time_id' => 'Time ID',
        ];
    }
}
