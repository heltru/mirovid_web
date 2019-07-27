<?php

namespace app\modules\pay\models;

use Yii;

/**
 * This is the model class for table "pay_form".
 *
 * @property int $id
 * @property string $label
 * @property int $user_id
 * @property string $summ
 * @property string $date
 * @property int $status
 */
class PayForm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pay_form';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'status'], 'integer'],
            [['summ'], 'number'],
            [['date'], 'required'],
            [['date'], 'safe'],
            [['label'], 'string', 'max' => 36],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'label' => 'Label',
            'user_id' => 'User ID',
            'summ' => 'Summ',
            'date' => 'Date',
            'status' => 'Status',
        ];
    }
}
