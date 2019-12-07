<?php

namespace app\modules\reklamir\models;

use Yii;

/**
 * This is the model class for table "reklamir_daytime".
 *
 * @property int $id
 * @property int $reklamir_id
 * @property int $time_id
 */
class ReklamirDaytime extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reklamir_daytime';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reklamir_id', 'time_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reklamir_id' => 'Reklamir ID',
            'time_id' => 'Time ID',
        ];
    }
}
