<?php

namespace app\modules\preview\models;

use Yii;

/**
 * This is the model class for table "preview".
 *
 * @property int $id
 * @property int $status
 * @property string $file
 * @property string $link
 */
class Preview extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'preview';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['file', 'link'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'file' => 'File',
            'link' => 'Link',
        ];
    }
}
