<?php

namespace app\modules\user\forms\frontend;

use app\modules\app\app\AppNovaVidAdminClient;
use app\modules\helper\models\Logs;
use app\modules\user\models\User;
use app\modules\user\Module;
use yii\base\Model;
use Yii;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $phone;
    public $type;
    public $verifyCode;


    /**
     * @param string $defaultRole
     * @param array $config
     */
    public function __construct(  $config = [])
    {

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'filter', 'filter' => 'trim'],
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#is'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => Module::t('module', 'ERROR_USERNAME_EXISTS')],
            ['username', 'string', 'min' => 2, 'max' => 255],


            [['type','phone'], 'string', 'max' => 45],


            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => Module::t('module', 'ERROR_EMAIL_EXISTS')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['verifyCode', 'captcha', 'captchaAction' => '/user/default/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => Module::t('module', 'USER_USERNAME'),
            'email' => Module::t('module', 'USER_EMAIL'),
            'password' => Module::t('module', 'USER_PASSWORD'),
            'verifyCode' => Module::t('module', 'USER_VERIFY_CODE'),
            'phone'=>'Номер телефона'
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {

            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->status = User::STATUS_WAIT;
            $user->phone = $this->phone;

            $user->generateAuthKey();
            $user->generateEmailConfirmToken();


            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();

            try {

                if ($user->save()) {
                    $app = AppNovaVidAdminClient::Instance();
                   if (  $app->addNewUser($user) ){

                       $auth = Yii::$app->authManager;
                       $client = $auth->getRole('client');
                       $auth->assign($client, $user->id);

                       $transaction->commit();

                       mail($this->email,
                           'Email confirmation for ' . Yii::$app->name,
                           Yii::$app->getView()->renderFile('@app/modules/user/mails/emailConfirm.php',['user' => $user])
                       );
                       /* Yii::$app->mailer->compose(['text' => '@app/modules/user/mails/emailConfirm'], ['user' => $user])
                      ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                      ->setTo($this->email)
                      ->setSubject('Email confirmation for ' . Yii::$app->name)
                      ->send();*/

                       return $user;
                   }


                }

                $transaction->rollBack();
                return null;

            }catch (\Exception $e) {
                $transaction->rollBack();
                Logs::log('addNewUser',[$e]);
                return null;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                Logs::log('addNewUser',[$e]);
                return null;
            }


        }
        return null;
    }
}
