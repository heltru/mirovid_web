<?php

namespace app\modules\zapros\models;

use Yii;

/**
 * This is the model class for table "order".
 *
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property string $email
 * @property string $text_query
 * @property string $date_cr
 * @property int $status
 */
class Zapros extends \yii\db\ActiveRecord
{



    const T_COMMON = 0;
    const T_LEDBILLBOAD = 1;

    public static  $arrTxtStatus = [ self::T_COMMON => 'Общая', self::T_LEDBILLBOAD =>'led биллборд'];


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'zapros';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_cr'], 'safe'],
            [['status','type'], 'integer'],
            [['text_query','email'], 'default','value'=>''],
            [['name', 'phone', 'email', 'text_query'], 'string', 'max' => 512],
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
            'phone' => 'Phone',
            'email' => 'Email',
            'text_query' => 'Text Query',
            'date_cr' => 'Date Cr',
            'status' => 'Status',
        ];
    }
}
