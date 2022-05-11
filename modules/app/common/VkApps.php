<?php

namespace app\modules\app\common;

use Yii;

/**
 * This is the model class for table "vk_apps".
 *
 * @property int $vk_app_id
 * @property string $secret
 * @property float $microtime
 */
class VkApps extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vk_apps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['vk_app_id', 'secret', 'microtime'], 'required'],
            [['vk_app_id'], 'integer'],
            [['microtime'], 'number'],
            [['secret'], 'string', 'max' => 255],
            [['vk_app_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'vk_app_id' => 'Vk App ID',
            'secret' => 'Secret',
            'microtime' => 'Microtime',
        ];
    }

    static function getSecondsBetweenLast($vk_app_id)
    {

        $vk_app = VkApps::findOne(['vk_app_id'=>$vk_app_id]);
        return (microtime(true) - $vk_app->microtime);
    }

    static function updateLastTime($vk_app_id)
    {
        $vk_app = VkApps::findOne(['vk_app_id'=>$vk_app_id]);
        if ($vk_app){
            $vk_app->microtime = microtime(true);
            $vk_app->update(false,['microtime']);
        }
    }

}
