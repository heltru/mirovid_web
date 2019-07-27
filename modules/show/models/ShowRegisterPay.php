<?php

namespace app\modules\show\models;

use Yii;

/**
 * This is the model class for table "show_register_pay".
 *
 * @property int $id
 * @property int $show_register_id
 * @property int $pay_id
 */
class ShowRegisterPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'show_register_pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['show_register_id', 'pay_id'], 'required'],
            [['show_register_id', 'pay_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'show_register_id' => 'Show Register ID',
            'pay_id' => 'Pay ID',
        ];
    }
}
