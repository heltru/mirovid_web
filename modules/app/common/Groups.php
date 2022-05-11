<?php

namespace app\modules\app\common;

use Yii;

/**
 * This is the model class for table "groups".
 *
 * @property int $group_id
 * @property string|null $name
 * @property int|null $is_closed
 * @property string|null $type
 * @property string|null $date
 * @property string|null $description
 * @property int|null $verified
 * @property string|null $site
 * @property int|null $user_id
 * @property int|null $vk_id
 * @property string|null $vk_photo
 * @property int|null $members_count
 * @property int|null $disabled
 * @property int|null $allow_callback
 * @property string|null $confirmation_token
 * @property string|null $callback_key
 * @property int|null $can_message
 * @property int|null $data_deleted
 * @property string|null $screen_name
 */
class Groups extends \yii\db\ActiveRecord
{

    public $primaryKey = 'group_id';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'groups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_closed', 'verified', 'user_id', 'vk_id', 'members_count', 'disabled', 'allow_callback', 'can_message', 'data_deleted'], 'integer'],
            [['date'], 'safe'],
            [['name', 'type', 'description', 'site', 'vk_photo', 'confirmation_token', 'callback_key', 'screen_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'group_id' => 'Group ID',
            'name' => 'Name',
            'is_closed' => 'Is Closed',
            'type' => 'Type',
            'date' => 'Date',
            'description' => 'Description',
            'verified' => 'Verified',
            'site' => 'Site',
            'user_id' => 'User ID',
            'vk_id' => 'Vk ID',
            'vk_photo' => 'Vk Photo',
            'members_count' => 'Members Count',
            'disabled' => 'Disabled',
            'allow_callback' => 'Allow Callback',
            'confirmation_token' => 'Confirmation Token',
            'callback_key' => 'Callback Key',
            'can_message' => 'Can Message',
            'data_deleted' => 'Data Deleted',
            'screen_name' => 'Screen Name',
        ];
    }
}
