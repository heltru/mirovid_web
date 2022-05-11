<?php

namespace app\modules\user\models;

use app\modules\app\AppModule;
use app\modules\app\common\Common;
use app\modules\helper\models\Helper;
use app\modules\user\models\query\UserQuery;
use app\modules\user\Module;
use app\modules\VkAPI\Exception;
use app\modules\VkAPI\VkAPI;
use app\modules\VkAPI\VkMethod;
use app\modules\VkAPI\VkOauth;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $username
 * @property string $auth_key
 * @property string $email_confirm_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $role
 * @property integer $status
 * @property integer $phone
 *
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_BLOCKED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_WAIT = 2;

    public $phone;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @return UserQuery
     */
    public static function find()
    {
        return Yii::createObject(UserQuery::className(), [get_called_class()]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#is'],
            ['username', 'unique', 'targetClass' => self::className(), 'message' => Module::t('module', 'ERROR_USERNAME_EXISTS')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => Module::t('module', 'ERROR_EMAIL_EXISTS')],
            ['email', 'string', 'max' => 255],

            ['fio', 'string', 'max' => 255],

            ['email_confirm_token', 'string', 'max' => 255],
            ['email_confirm_token', 'unique' ],

            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],

            ['phone','string','max'=>12],

            ['vk_id', 'integer'],
            ['first_name', 'string'],
            ['last_name', 'string'],
            ['sex', 'integer'],
            ['domain', 'string'],
            ['bdate', 'safe'],
            ['city', 'string'],
            ['country', 'string'],
            ['vk_photo', 'string'],
            ['vk_token', 'string'],



        ];
    }

    public function getRole(){
        return 'delete!!';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => Module::t('module', 'USER_CREATED'),
            'updated_at' => Module::t('module', 'USER_UPDATED'),
            'username' => Module::t('module', 'USER_USERNAME'),
            'email' => Module::t('module', 'USER_EMAIL'),
            'status' => Module::t('module', 'USER_STATUS'),
            'fio'=>'ФИО'

        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED => Module::t('module', 'USER_STATUS_BLOCKED'),
            self::STATUS_ACTIVE => Module::t('module', 'USER_STATUS_ACTIVE'),
            self::STATUS_WAIT => Module::t('module', 'USER_STATUS_WAIT'),
        ];
    }

    /**
    * @inheritdoc
    */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('findIdentityByAccessToken is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findByPhone($phone)
    {
        return static::findOne(['phone' => $phone]);
    }


    public static function findByEmmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * @param string $email_confirm_token
     * @return static|null
     */
    public static function findByEmailConfirmToken($email_confirm_token)
    {
        return static::findOne(['email_confirm_token' => $email_confirm_token, 'status' => self::STATUS_WAIT]);
    }

    /**
     * Generates email confirmation token
     */
    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    /**
     * Removes email confirmation token
     */
    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @param integer $timeout
     * @return static|null
     */
    public static function findByPasswordResetToken($token, $timeout)
    {
        if (!static::isPasswordResetTokenValid($token, $timeout)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @param integer $timeout
     * @return bool
     */
    public static function isPasswordResetTokenValid($token, $timeout)
    {
        if (empty($token)) {
            return false;
        }
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $timeout >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }

    static function getLoginLink($scope = null)
    {
        if ($scope && !is_array($scope)) $scope = [$scope];

        $a = ['groups', 'offline', 'ads', 'stories'];
        if ($scope) $a = array_merge($a, $scope);

        $vk = new VkOauth(
            AppModule::getVkAppId4auth(),
            AppModule::getVkAppSecret4auth(),
            AppModule::getVkAppRedirect()
        );

        return $vk->getLink($a, 'user');
    }

    public static function update2Vk($vk_user_id, $access_token = null)
    {
        $vk_user = self::getOriginalVkUser($vk_user_id, $access_token);


        if (!$vk_user || !$vk_user['id'] || isset($vk_user['deactivated'])) {
            throw new HttpException(400,'bad user');
        }

//        if (Ignores::test(1, $vk_user_id)) {
//            throw new Core\Exception\BadRequest();
//        }

        $params = array(
            'vk_id' => intval($vk_user['id']),
            'first_name' => '',
            'last_name' => '',
            'sex' => intval(isset($vk_user['sex']) ? $vk_user['sex'] : 0),
            'domain' => '',
            'bdate' => '',
            'city' => '',
            'country' => '',
            'vk_photo' => self::getPhoto2Vk($vk_user),
        );

        if ($access_token) $params['vk_token'] = $access_token;

        if (isset($vk_user['city']['title'])) $params['city'] =  Helper::sanitizeString($vk_user['city']['title']);
        if (isset($vk_user['bdate'])) $params['bdate'] = date("Y-m-d H:i:s", strtotime($vk_user['bdate']));
        if (isset($vk_user['country']['title'])) $params['country'] =  Helper::sanitizeString($vk_user['country']['title']);
        if (isset($vk_user['first_name'])) $params['first_name'] =  Helper::sanitizeString($vk_user['first_name']);
        if (isset($vk_user['last_name'])) $params['last_name'] =  Helper::sanitizeString($vk_user['last_name']);
        if (isset($vk_user['domain'])) $params['domain'] = Helper::sanitizeString($vk_user['domain']);

        $user = User::getUser2Vk($vk_user_id);
        if (!$user) {
            //$params['referrer_id'] = Core\App::$referrer_id;

//            $tariff = Tariff::getDefaultTariff();
//            if ($tariff) {
//                $params['tariff_id'] = $tariff['tariff_id'];
//            }

//            if (in_array(Core\App::$referrer_id, array(1913))) {
//                $params['tariff_id'] = 11; // Silver+
//            }

//            $params['pay_tariff_date'] = date("Y-m-d H:i:s");
//            $params['change_tariff_date'] = date("Y-m-d H:i:s");
//            $params['next_pay_tariff_date'] = date("Y-m-d H:i:s", strtotime("+1 month"));


            $model_user = self::findOne(['id'=>Yii::$app->getUser()->getId()]);// new User();

            Helper::assoc_model($params,$model_user);
            if (!$model_user->save()){
                ex([
                    $params,
                    $model_user->getErrors()]);
            }


            $user_id = $model_user->id;
            $user = $model_user; //self::getUser($user_id);


//            if (!in_array(Core\App::$referrer_id, array(1913))) {
//                MoneyFlow::referralStart($user, Core\App::$referrer_id);
//            }
        } /*elseif ($user['disabled']) {
            throw new Core\Exception\BadRequest();
        } */else {
            Helper::assoc_model($params,$user);
            if ($user->update(false) === false){
                ex($user->getErrors());
            }

            //Model\Users::update($params, $user['user_id']);
        }
        return $user;
    }

    static function getUser2Vk($vk_id)
    {
        $vk_id = intval($vk_id);
        if (!$vk_id) return false;

        return self::findOne(['vk_id' => $vk_id]);
    }

    static function getPhoto2Vk($vk_user)
    {
        if (!$vk_user) return '';
        if (!$vk_user['has_photo']) return '';
        if (!isset($vk_user['photo_50'])) return '';

        return Helper::sanitizeURL($vk_user['photo_50']);
    }

    static $max_id = 2000000000;

    static function getOriginalVkUser($vk_user_id, $access_token = null)
    {
        $vk_user_id = intval($vk_user_id);
        if ($vk_user_id < 1 || $vk_user_id >= self::$max_id) return null;

        $vk_users = self::getOriginalVkUsers($vk_user_id, $access_token);
        if (count($vk_users) < 1) return false;
        return array_shift($vk_users);
    }
    static $fields = array('city', 'country', 'has_photo', 'photo_50', 'photo_100', 'photo_200', 'photo_max_orig', 'domain', 'bdate', 'sex', 'relation');
    static function getOriginalVkUsers($vk_user_ids, $access_token = null)
    {
        $vk_users = array();
        $vk = new VkMethod();

        try {
            $vk->setToken(Common::getToken($access_token));
            $vk_users = $vk->getUsers($vk_user_ids, self::$fields);
        } catch ( Exception  $e) {
            if ($vk->error) {
                if (in_array($vk->error['error_code'], array(
                    VkAPI::ACCESS_DENIED, VkAPI::WRONG_TOKEN))) {
                    $vk->setToken(AppModule::getVkAppTech4user());
                    $vk_users = $vk->getUsers($vk_user_ids, self::$fields);
                }
            }
        }

        if (!$vk_users) return array();
        return self::formatOriginalVkUsers($vk_users);
    }

    private static function formatOriginalVkUsers($vk_users)
    {
        if (is_array($vk_users) && $vk_users) {
            foreach ($vk_users as $key => $vk_user) {
                if (!isset($vk_user['id'])) $vk_users[$key]['id'] = 0;
                if (!isset($vk_user['first_name'])) $vk_users[$key]['first_name'] = '';
                if (!isset($vk_user['last_name'])) $vk_users[$key]['last_name'] = '';
                if (!isset($vk_user['photo_200'])) $vk_users[$key]['photo_200'] = '';
                if (!isset($vk_user['sex'])) $vk_users[$key]['sex'] = 0;
                if (!isset($vk_user['relation'])) $vk_users[$key]['relation'] = 0;
                if (!isset($vk_user['domain'])) $vk_users[$key]['domain'] = '';
                if (!isset($vk_user['has_photo'])) $vk_users[$key]['has_photo'] = '';
                if (!isset($vk_user['photo_50'])) $vk_users[$key]['photo_50'] = '';
                if (!isset($vk_user['photo_100'])) $vk_users[$key]['photo_100'] = '';
                if (!isset($vk_user['photo_200'])) $vk_users[$key]['photo_200'] = '';

                if (isset($vk_user['bdate'])) {
                    $e = explode(".", $vk_user['bdate']);
                    $vk_users[$key]['bdate'] = sprintf("%04d-%02d-%02d", (isset($e[2]) ? $e[2] : 1970), (isset($e[1]) ? $e[1] : 1), (isset($e[0]) ? $e[0] : 1));
                } else {
                    $vk_users[$key]['bdate'] = null;
                }

                if (!isset($vk_user['photo_max_orig'])) $vk_users[$key]['photo_max_orig'] = '';
                if (!isset($vk_user['city'])) $vk_users[$key]['city'] = array('title' => '', 'id' => 0);
                if (!isset($vk_user['country'])) $vk_users[$key]['country'] = array('title' => '', 'id' => 0);
            }
        }
        return $vk_users;
    }


}
