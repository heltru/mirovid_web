<?php

namespace app\modules\pay\models;

use Yii;

/**
 * This is the model class for table "payment_system_pay".
 *
 * @property int $id
 * @property int $payment_system_id
 * @property int $pay_id
 */
class PaySystemPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment_system_pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['payment_system_id', 'pay_id'], 'required'],
            [['payment_system_id', 'pay_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'payment_system_id' => 'Payment System ID',
            'pay_id' => 'Pay ID',
        ];
    }
}
