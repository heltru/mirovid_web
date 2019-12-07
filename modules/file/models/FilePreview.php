<?php

namespace app\modules\file\models;

use Yii;

/**
 * This is the model class for table "file_preview".
 *
 * @property int $id
 * @property string $path_preview
 * @property int $file_id
 */
class FilePreview extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'file_preview';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [[ 'file_id'], 'integer'],
            [['path_preview'], 'string', 'max' => 512],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path_preview' => 'Path Preview',
            'file_id' => 'File ID',
        ];
    }
}
