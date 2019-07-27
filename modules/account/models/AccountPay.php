<?php

namespace app\modules\account\models;

use Yii;

/**
 * This is the model class for table "account_pay".
 *
 * @property int $id
 * @property int $account_id
 * @property int $pay_id
 */
class AccountPay extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account_pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id', 'pay_id'], 'required'],
            [['account_id', 'pay_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'Account ID',
            'pay_id' => 'Pay ID',
        ];
    }
}
