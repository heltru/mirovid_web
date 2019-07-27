<?php

namespace app\modules\account\models;

use app\modules\pay\models\Pay;
use app\modules\user\models\User;
use Yii;

/**
 * This is the model class for table "account".
 *
 * @property int $id
 * @property int $balance
 * @property string $user_id
 * @property int $status
 * @property string $date_cr
 * @property string $ord_show
 *
 */
class Account extends \yii\db\ActiveRecord
{
    const ST_OK = 0;
    const ST_NO = 1;

    public static  $arrTxtStatus = [self::ST_OK => 'Включен',self::ST_NO =>'Выключен'];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance', 'status','user_id','ord_show'], 'integer'],
            [['user_id', 'date_cr'], 'required'],
            [['date_cr'], 'safe'],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'balance' => 'Balance',
            'user_id' => 'user_id Id',
            'status' => 'Status',
            'date_cr' => 'Date Cr',
            'ord_show' => 'Сортировка аккаунта'
        ];
    }

    public function getUser_r(){

        return $this->hasOne( User::className(), ['id' => 'user_id']);
    }


}
