<?php

namespace app\modules\pay\models;

use Yii;

/**
 * This is the model class for table "trx".
 *
 * @property int $id
 * @property int $dt
 * @property int $kt
 * @property int $type
 * @property string $summ
 * @property string $date
 */
class Trx extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trx';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dt', 'kt','type'], 'integer'],
            [['summ'], 'number'],
            [['date'], 'required'],
            [['date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'dt' => 'Dt',
            'kt' => 'Kt',
            'summ' => 'Summ',
            'date' => 'Date',
        ];
    }
}
