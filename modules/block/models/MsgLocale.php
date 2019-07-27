<?php

namespace app\modules\block\models;

use Yii;

/**
 * This is the model class for table "msg_locale".
 *
 * @property int $id
 * @property int $msg_id
 * @property int $locale_id
 */
class MsgLocale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'msg_locale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['msg_id', 'locale_id'], 'integer'],
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
