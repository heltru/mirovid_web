<?php

namespace app\modules\show\models;

use Yii;

/**
 * This is the model class for table "track_point".
 *
 * @property int $id
 * @property int $track_id
 * @property string $lat
 * @property string $long
 */
class TrackPoint extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'track_point';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['track_id'], 'integer'],
            [['lat', 'long'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'track_id' => 'Track ID',
            'lat' => 'Lat',
            'long' => 'Long',
        ];
    }
}
