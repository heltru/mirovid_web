<?php

namespace app\modules\show\models;

use Yii;

/**
 * This is the model class for table "show_register".
 *
 * @property int $id
 * @property int $msg_id
 * @property string $date_sh
 * @property int $lat
 * @property int $long
 * @property int $time
 */
class ShowRegister extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'show_register';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['msg_id', 'date_sh'], 'required'],
            [['msg_id', 'time'], 'integer'],
            [[  'lat', 'long' ], 'number'],

            [['date_sh'], 'safe'],
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
            'date_sh' => 'Date Sh',
            'lat' => 'Lat',
            'long' => 'Long',
            'time' => 'time',
        ];
    }
}
