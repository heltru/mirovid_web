<?php

namespace app\modules\reklamir\models;

use app\modules\account\models\Account;
use app\modules\app\app\RegisterShow;
use app\modules\bid\models\Bid;
use app\modules\file\models\File;
use app\modules\file\models\FilePreview;
use app\modules\show\models\ShowRegister;
use Yii;

/**
 * This is the model class for table "reklamir".
 *
 * @property int $id
 * @property int $thing_id
 * @property int $file_id
 * @property int $account_id
 * @property int $show
 * @property int $status
 * @property int $name
 * @property int $thing_cat
 *
 */
class Reklamir extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */



    const ST_ON = 0;
    const ST_OFF = 1;
    const ST_MODERATE = 2;
    const ST_BLOCK = 3;
    const ST_TEST = 4;

    public static  $arrTxtStatus = [ self::ST_ON => 'Идут показы', self::ST_OFF =>'Выключено',
        self::ST_MODERATE =>'Модерация',self::ST_BLOCK =>'Отклонено',self::ST_TEST =>'Предтестовый запуск'];


    public $uploadFile;

    public $times;
    public $areas;


    public static function tableName()
    {
        return 'reklamir';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'account_id','name','thing_cat'], 'required'],
            [[ 'file_id', 'account_id', 'show', 'status','thing_cat'], 'integer'],
            [['uploadFile'], 'file',
                'extensions' => 'png, jpg, gif, jpeg, bmp, mp4, avi, webm, mpeg, mpg, wmv, mkv, mov, MOV'],
            [['name'], 'string','max' => 255],
            [['msg'], 'string','max' => 9000],
            [['times','areas'], 'safe'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'thing_id' => 'Устройство',
            'file_id' => 'Файл',
            'account_id' => 'Аккаунт',
            'show' => 'Показы',
            'status' => 'Статус',
            'name' => 'Название',
            'msg' => 'Сообщение',
            'thing_cat' => 'Категория устройств',
            'uploadFile'=>'Выбрать файл'

        ];
    }


    public function getThingCat_r(){
        return $this->hasOne( ThingCat::class, ['id' => 'thing_cat']);
    }


    public function getAccount_r(){
        return $this->hasOne( Account::class, ['id' => 'account_id']);
    }

    public function getThing_r(){
        return $this->hasMany(ReklamirThing::class,['reklamir_id'=>'id']);
    }

    public function getFile_r(){
        return $this->hasOne( File::class, ['id' => 'file_id']);
    }

    public function getBid_r(){
        return $this->hasMany( Bid::class, ['reklamir_id' => 'id']);
    }


    public function getDaytime_r(){
        return $this->hasMany( ReklamirDaytime::class, ['reklamir_id' => 'id']);
    }

    public function getArea_r(){
        return $this->hasMany( ReklamirArea::class, ['reklamir_id' => 'id']);
    }



    public function afterDelete()
    {
        $this->cleanFiles($this->file_id);
        foreach ( ReklamirDaytime::find()->where(['reklamir_id'=>$this->id])->all() as $item ){
            $item->delete();
        }
        foreach ( ReklamirArea::find()->where(['reklamir_id'=>$this->id])->all() as $item ){
            $item->delete();
        }
        foreach ( ReklamirThing::find()->where(['reklamir_id'=>$this->id])->all() as $item ){
            $item->delete();
        }
        foreach ( Bid::find()->where(['reklamir_id'=>$this->id])->all() as $item ){
            $item->delete();
        }
        foreach ( ShowRegister::find()->where(['reklamir_id'=>$this->id])->all() as $item ){
            $item->delete();
        }
        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    private function cleanFiles($file_id){
        $count_uses_for_file = (int)Reklamir::find()->where(['file_id'=>$file_id])->count();
        if (!$count_uses_for_file){
            $file = File::findOne(['id'=>$file_id]);
            if ($file!== null){
                $file->delete();
            }
        }
    }
}
