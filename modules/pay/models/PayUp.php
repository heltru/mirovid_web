<?php

namespace app\modules\pay\models;

use Yii;

/**
 * This is the model class for table "pay_up".
 *
 * @property int $id
 * @property int $pay_source
 * @property int $pay_dest
 * @property string $summ
 */
class PayUp extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_up';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pay_source', 'pay_dest', 'summ'], 'required'],
            [['pay_source', 'pay_dest'], 'integer'],
            [['summ'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pay_source' => 'Pay Source',
            'pay_dest' => 'Pay Dest',
            'summ' => 'Summ',
        ];
    }
}
