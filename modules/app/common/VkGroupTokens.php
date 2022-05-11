<?php

namespace app\modules\app\common;

use Yii;

/**
 * This is the model class for table "vk_group_tokens".
 *
 * @property int $vk_group_token_id
 * @property int|null $vk_group_id
 * @property string|null $vk_token
 * @property string|null $date
 * @property int|null $app_id
 */
class VkGroupTokens extends \yii\db\ActiveRecord
{

    public $primaryKey = 'vk_group_token_id';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vk_group_tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vk_group_id', 'app_id'], 'integer'],
            [['date'], 'safe'],
            [['vk_token'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'vk_group_token_id' => 'Vk Group Token ID',
            'vk_group_id' => 'Vk Group ID',
            'vk_token' => 'Vk Token',
            'date' => 'Date',
            'app_id' => 'App ID',
        ];
    }
}
