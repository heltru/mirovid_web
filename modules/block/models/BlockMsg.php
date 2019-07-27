<?php

namespace app\modules\block\models;

use Yii;

/**
 * This is the model class for table "block_msg".
 *
 * @property integer $id
 * @property integer $block_id
 * @property integer $msg_id
 */
class BlockMsg extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'block_msg';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['block_id', 'msg_id'], 'required'],
            [['block_id', 'msg_id'], 'integer'],
        ];
    }

    public function getMsg_r(){

        return $this->hasOne( Msg::class, ['id' => 'msg_id']);
    }

    public function getBlock_r(){

        return $this->hasOne( Block::class, ['id' => 'block_id']);
    }

  /*  public function getComments_r(){
        return $this->hasMany( Comments::className(), ['id_type' => 'id'])->andWhere(['type' => 'blog']);
    }*/

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'block_id' => 'Block ID',
            'msg_id' => 'Msg ID'
        ];
    }




}
