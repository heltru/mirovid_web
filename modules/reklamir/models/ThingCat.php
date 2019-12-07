<?php

namespace app\modules\reklamir\models;

use Yii;

/**
 * This is the model class for table "thing_cat".
 *
 * @property int $id
 * @property string $name
 */
class ThingCat extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'thing_cat';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название категории',
        ];
    }
}
