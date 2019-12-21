<?php

namespace app\modules\reklamir\models;

use Yii;

/**
 * This is the model class for table "place".
 *
 * @property int $id
 * @property int $price_show
 * @property string $name
 * @property string $gps
 */
class Place extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'place';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 512],
            [['price_show'], 'integer'],
            [['price_show'], 'default','value'=>1],
            [['gps'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [

            'name' => 'Название места',
            'price_show'=>'Базовая цена показа места',
            'gps' => 'Gps',
        ];
    }


}
