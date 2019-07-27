<?php

namespace app\modules\image\models;

use Yii;

/**
 * This is the model class for table "img_links".
 *
 * @property integer $id
 * @property string $type
 * @property integer $id_type
 * @property integer $id_image
 */
class ImgLinks extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */

    const T_Pt = 'product';
    const T_As = 'action';
    const T_Bg = 'blog';
    const T_Bgc = 'blogcat';

    const T_Br = 'brand';
    const T_Bd = 'badge';
    const T_St = 'set';
    const T_At = 'author';
    const T_Bc = 'brandcat';
    const T_Cy = 'catalog';
    const T_Ds = 'descr';

    const T_Wm = 'watermark';



    public static  $arrTxtStatus = [0 => 'Включен',1 =>'Выключен'];


    public static  $arrTxtType = [
        self::T_Pt => 'Продукты',
        self::T_As => 'Акции',
        self::T_Bg => 'Блог',
        self::T_Br => 'Бренды',
        self::T_Bd => 'Бейджи',
        self::T_St => 'Наборы',
        self::T_At => 'Пользователи',
        self::T_Bc => 'БрендыКатегории',
    ];

    public static function tableName()
    {
        return 'img_links';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'string'],
            [['id_type', 'id_image','ord'], 'integer'],
        ];
    }

    public function getImg_r(){
        return $this->hasOne( Img::className(), ['id' => 'id_image']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'id_type' => 'Id Type',
            'id_image' => 'Id Image',
        ];
    }
}
