<?php

namespace app\modules\block\models;

use Yii;

/**
 * This is the model class for table "msg_locale_cost".
 *
 * @property int $id
 * @property int $msg_id
 * @property int $locale_id
 * @property int $cost
 */
class MsgLocaleCost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'msg_locale_cost';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['msg_id', 'locale_id','cost'], 'integer'],
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
            'locale_id' => 'Locale ID',
        ];
    }
}
