<?php

namespace app\modules\reklamir\models;

use Yii;

/**
 * This is the model class for table "reklamir_area".
 *
 * @property int $id
 * @property int $reklamir_id
 * @property int $area_id
 */
class ReklamirArea extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reklamir_area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reklamir_id', 'area_id'], 'integer'],
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
            'area_id' => 'Area ID',
        ];
    }
}
