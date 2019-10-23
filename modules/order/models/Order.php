<?php

namespace app\modules\order\models;

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
class Order extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_cr'], 'safe'],
            [['status'], 'integer'],
            [['text_query'], 'default','value'=>''],
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
