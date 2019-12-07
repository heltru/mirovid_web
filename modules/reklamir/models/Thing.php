<?php

namespace app\modules\reklamir\models;

use Yii;

/**
 * This is the model class for table "thing".
 *
 * @property int $id
 * @property string $name
 * @property string $global_config_local
 * @property int $cat_id
 * @property string $my_ip
 */


class Thing extends \yii\db\ActiveRecord
{





    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'thing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat_id','place_id' ], 'integer'],
            [['my_ip'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 255],
            [['global_config_local'], 'string', 'max' => 1024],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [

            'name' => 'Название устройства',
            'cat_id' => 'Категория устройства',
            'place_id' => 'Место',
            'global_config_local' => 'Локальный конфиг',


        ];
    }




    public function getCat_r(){
        return $this->hasOne( ThingCat::class, ['id' => 'cat_id']);
    }

    public function getPlace_r(){
        return $this->hasOne( Place::class, ['id' => 'place_id']);
    }


}
