<?php

namespace app\modules\bid\models;

use app\modules\reklamir\models\Reklamir;
use Yii;

/**
 * This is the model class for table "bid_log".
 *
 * @property int $id
 * @property int $reklamir_id
 * @property string $msg
 */
class BidLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bid_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reklamir_id','read','time_id'], 'integer'],
            [['msg'], 'string', 'max' => 1024],
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
            'msg' => 'Msg',
        ];
    }
    public function getReklamir_r(){
        return $this->hasOne( Reklamir::class, ['id' => 'reklamir_id']);
    }

}
