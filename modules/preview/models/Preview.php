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



    const ST_NEED_PREVIEW = 0;
    const ST_READY = 1;

    public static  $arrTxtStatus = [
        self::ST_NEED_PREVIEW => 'Требует превью',
        self::ST_READY =>'Готов'];


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
