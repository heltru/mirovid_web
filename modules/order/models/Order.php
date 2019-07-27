<?php

namespace app\modules\order\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $datetime
 * @property string $ip
 * @property string $phone
 * @property string $car_id
 * @property string $text
 * @property string $count_view
 * @property string $status
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const T_Q = 'qpay';

    const ST_N = 0;
    const ST_P = 1;



    public static  $arrTxtStatus = [ self::ST_N => 'нулевой', self::ST_P =>'оплачен быстрым платежом'];

    public static function tableName()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['datetime','car_id','phone'], 'required','text'],
            [['count_view','car_id','status'],'integer'],
            [['datetime'], 'safe'],
            [['type','phone'], 'string', 'max' => 45],
            [['ip','text'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'datetime' => 'Datetime',
            'ip' => 'Ip',
            'type' => 'type',
        ];
    }


}
