<?php

namespace app\modules\block\models;

use app\modules\app\app\AppAccount;
use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "msg".
 *
 * @property int $id
 * @property int $block_id
 * @property string $type
 * @property string $content
 * @property string $date_cr
 * @property string $date_update
 * @property int $status
 * @property string $img_preview_320_160
 * @property int $car_id
 * @property int $count_total
 * @property int $count_limit
 * @property int $count_show
 * @property string $img_exp
 * @property string $content_update
 *
 *
 *
 */
class Msg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */


    const ST_OK = 0;
    const ST_NO = 1;
    const ST_MR = 2;
    const ST_DL = 3;


    const  T_T = 'text';
    const  T_P = 'pixeldata';
    const  T_I = 'image';
    const  T_C = 'canvas';

    public static $arrTxtType = [self::T_T => 'Мем текст',
        //self::T_P => 'Pixeldata',
        self::T_I => 'Мем Картинка', self::T_C => 'Мем Канвас'];

    public static $arrTxtStatus = [self::ST_OK => 'Ok', self::ST_NO => 'Выкл', self::ST_MR => 'Moderate' /*, self::ST_DL => 'Delete'*/];

    public $locales = '';
    public $times='';
    public $content_update;
    public $raw_data;
    public $locales_cost;
    public $copy_mem_id;

    public static $days_show = [
        1 => 'ПН',
        2 => 'ВТ',
        3 => 'СР',
        4 => 'ЧТ',
        5 => 'ПТ',
        6 => 'СБ',
        7 => 'ВС'
    ];

    public static $times_show = [
        1 => ['00:00', '00:30'], 2 => ['00:30', '01:00'], 3 => ['01:00', '01:30'], 4 => ['01:30', '02:00'],
        5 => ['02:00', '02:30'], 6 => ['02:30', '03:00'], 7 => ['03:00', '03:30'], 8 => ['03:30', '04:00'],
        9 => ['04:00', '04:30'], 10 => ['04:30', '05:00'], 11 => ['05:00', '05:30'], 12 => ['05:30', '06:00'],
        13 => ['06:00', '06:30'], 14 => ['06:30', '07:00'], 15 => ['07:00', '07:30'], 16 => ['07:30', '08:00'],
        17 => ['08:00', '08:30'], 18 => ['08:30', '09:00'], 19 => ['09:00', '09:30'], 20 => ['09:30', '10:00'],
        21 => ['10:00', '10:30'], 22 => ['10:30', '11:00'], 23 => ['11:00', '11:30'], 24 => ['11:30', '12:00'],
        25 => ['12:00', '12:30'], 26 => ['12:30', '13:00'], 27 => ['13:00', '13:30'], 28 => ['13:30', '14:00'],
        29 => ['14:00', '14:30'], 30 => ['14:30', '15:00'], 31 => ['15:00', '15:30'], 32 => ['15:30', '16:00'],
        33 => ['16:00', '16:30'], 34 => ['16:30', '17:00'], 35 => ['17:00', '17:30'], 36 => ['17:30', '18:00'],
        37 => ['18:00', '18:30'], 38 => ['18:30', '19:00'], 39 => ['19:00', '19:30'], 40 => ['19:30', '20:00'],
        41 => ['20:00', '20:30'], 42 => ['20:30', '21:00'], 43 => ['21:00', '21:30'], 44 => ['21:30', '22:00'],
        45 => ['22:00', '22:30'], 46 => ['22:30', '23:00'], 47 => ['23:00', '23:30'], 48 => ['23:30', '00:00']
    ];


    public static function tableName()
    {
        return 'msg';
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['block_id', 'type'], 'required'],
            [['id', 'block_id', 'count_show', 'count_limit',

                'count_total', 'car_id', 'status', 'account_sort','content_update'], 'integer'],

            [

                ['img_preview_320_160', 'img_exp', 'date_update'],

                'string'
            ],


            [['date_cr', 'date_update','content_update'], 'safe'],
            [['type'], 'string', 'max' => 45],
            [['content'], 'string'],

            [['locales','times','raw_data','locales_cost','copy_mem_id'],'safe'],





        ];
    }



    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'block_id' => 'Block ID',
            'type' => 'Тип',
            'content' => 'Сообщение',
            'date_cr' => 'Date Cr',
            'status' => 'Status',
            'car_id' => 'car_id',
            'account_sort' => 'Порядок выхода (3 сообщения за раз)',

            'count_show' => 'Показанно из запланированного',
            'count_limit' => 'Запланированное кол-во показов',
            'count_total' => 'Общее кол-во показов',
            'copy_mem_id' => 'Копировать настройки с мема'


        ];
    }

    public function getBlock_r()
    {
        return $this->hasOne(Block::class, ['id' => 'block_id']);
    }

    public function getBlockMsg_r()
    {
        return $this->hasOne(BlockMsg::class, ['msg_id' => 'id']);
    }


    public function getLocale_r(){
        return $this->hasMany( MsgLocale::class, ['msg_id' => 'id']);
    }

    public function getLocale_cost_r(){
        return $this->hasMany( MsgLocaleCost::class, ['msg_id' => 'id']);
    }

    public function getDaytime_r(){
        return $this->hasMany( MsgDaytime::class, ['msg_id' => 'id']);
    }


    public function afterDelete()
    {

        if (file_exists($this->content)) {
            unlink($this->content);
        }

        foreach ( MsgDaytime::find()->where(['msg_id'=>$this->id])->all() as $item ){
            $item->delete();
        }
        foreach ( MsgLocaleCost::find()->where(['msg_id'=>$this->id])->all() as $item ){
            $item->delete();
        }
        foreach ( MsgLocale::find()->where(['msg_id'=>$this->id])->all() as $item ){
            $item->delete();
        }

        parent::afterDelete(); // TODO: Change the autogenerated stub
    }

    public static function getHierarchyMemByBlock($id_msg=null) {
        $options = [];

        $appAc = AppAccount::Instance();

        $parents = Block::find()->where(['account_id'=>$appAc->getAccount()->id])->orderBy('name')->all();

        foreach($parents as $id => $p) {

            $children = Msg::find()->where("block_id=:block_id", [":block_id"=>$p->id])->all();
            if (! count($children)){
                continue;
            }

            $child_options = [];
            foreach($children as $child) {
                if ( $child->id == $id_msg ){
                    continue;
                }
                $child_options[$child->id] = 'ID ' . $child->id;
            }
            $options[$p->name] = $child_options;
        }
        return $options;
    }

    public function getContentFormat(){
        if ( file_exists( $this->content)){
            if ($this->type == self::T_T){
                return file_get_contents( $this->content);
            }
            if ($this->type == self::T_I){
            }

        }
        return '';
    }


}
