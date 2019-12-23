<?php

namespace app\modules\reklamir\models;

use Yii;

/**
 * This is the model class for table "reklamir_thing".
 *
 * @property int $id
 * @property int $reklamir_id
 * @property int $thing_id
 */
class ReklamirThing extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reklamir_thing';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reklamir_id', 'thing_id'], 'required'],
            [['reklamir_id', 'thing_id'], 'integer'],
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
            'thing_id' => 'Thing ID',
        ];
    }

    public function getReklamir_r(){
        return $this->hasOne( Reklamir::class,['id'=> 'reklamir_id' ] );
    }

    public function getThing_r(){
        return $this->hasOne( Thing::class,['id'=> 'thing_id' ] );
    }
}
