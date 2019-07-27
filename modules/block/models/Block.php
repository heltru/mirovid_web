<?php

namespace app\modules\block\models;

use app\modules\account\models\Account;
use Yii;

/**
 * This is the model class for table "block".
 *
 * @property int $id
 * @property int $account_id
 * @property int $status
 * @property string $date_cr
 * @property string $type
 * @property string $name
 * @property string $order_block
 *
 */
class Block extends \yii\db\ActiveRecord
{

    const ST_OK = 0;
    const ST_NO = 1;
    const ST_DL = 3;
    const ST_TEST = 4;

    const  T_Q = 'qick';
    const  T_RK = 'rk';

    public static  $arrTxtStatus = [ self::ST_OK => 'Показывается', self::ST_NO =>'Неактивен', self::ST_DL => 'Delete', self::ST_TEST => 'Test'];


    public $account_sort;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'block';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['account_id'], 'required'],
            [['account_id','order_block','id','status'], 'integer'],
            [['date_cr'], 'safe'],

            [['type'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 127],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account_id' => 'account_id',
            'status' => 'Status',
            'date_cr' => 'Date Cr',
            'type' => 'Type',
            'name' => 'Название РК',

         //   'car_id' => 'Для машины' // 0 Для всех
        ];
    }

    public function getAccount_r(){
        return $this->hasOne( Account::class, ['id' => 'account_id']);
    }

    public function getMsg_r(){
        return $this->hasMany( Msg::class, ['block_id' => 'id']);
    }





}
