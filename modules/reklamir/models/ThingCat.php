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

    const C_TABLE_AUTO  = 'table_auto';
    const C_TABLET_TAXI  = 'tablet_taxi';




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
            [['name','sys_name'], 'string', 'max' => 45],
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
            'sys_name' => 'Системное имя',
        ];
    }
}
