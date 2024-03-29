<?php

namespace app\modules\show\models;

use app\modules\reklamir\models\Reklamir;
use app\modules\reklamir\models\Thing;
use Yii;

/**
 * This is the model class for table "show_register".
 *
 * @property int $id
 * @property int $reklamir_id
 * @property string $date_sh
 * @property int $lat
 * @property int $long
 * @property int $thing_id
 *
 */
class ShowRegister extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'show_register';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[ 'date_sh'], 'required'],
            [['reklamir_id','file_id','thing_id'], 'integer'],
            [[  'lat', 'long' ], 'number'],

            [['date_sh'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reklamir_id' => 'Реклама',
            'date_sh' => 'Date Sh',
            'lat' => 'Lat',
            'long' => 'Long',
            'date_from' => 'От',
            'date_to' => 'До',
            'thing_id' => 'Устройство'
        ];
    }

    public function getReklamir_r(){
        return $this->hasOne( Reklamir::class, ['id' => 'reklamir_id']);
    }

    public function getThing_r(){
        return $this->hasOne( Thing::class, ['id' => 'thing_id']);
    }



}
