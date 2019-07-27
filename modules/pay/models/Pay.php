<?php

namespace app\modules\pay\models;

use Yii;

/**
 * This is the model class for table "pay".
 *
 * @property int $id
 * @property string $val
 * @property int $status
 * @property string $info
 * @property string $sys_name
 * @property int $type
 */
class Pay extends \yii\db\ActiveRecord
{

    const INIT_PAY = 'initpay';
    const YACLI_PAY = 'yaclipay';

    const ST_ACTIVE = 0;
    const ST_DELETE = 1;

    public static  $arrTxtStatus = [ self::ST_ACTIVE => 'Активен', self::ST_DELETE =>'Удален'];

    /**
     * @inheritdoc
     */

    public static function tableName()
    {
        return 'pay';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['val'], 'number'],
            [['status','type'], 'integer'],
            [['info','type'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'val' => 'Balance',
            'status' => 'Status',
            'info' => 'info',
            'sys_name' => 'sys_name',
            'type' => 'type',
        ];
    }
}
