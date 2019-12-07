<?php

namespace app\modules\show\models;

use Yii;

/**
 * This is the model class for table "track_auto".
 *
 * @property int $id
 * @property string $name
 * @property int $thing_id
 */
class TrackAuto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'track_auto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['thing_id'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['img'], 'string', 'max' => 255],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'thing_id' => 'Thing ID',
        ];
    }

    public function getTrackPoints_r()
    {
        return $this->hasMany( TrackPoint::class, ['track_id' => 'id']);

    }
}
