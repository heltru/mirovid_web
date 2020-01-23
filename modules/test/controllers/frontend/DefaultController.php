<?php

namespace app\modules\test\controllers\frontend;

use app\modules\car\models\Car;
use app\modules\test\app\SiteError;
use app\modules\user\models\User;
use yii\web\Controller;
use Yii;
use yii\base\Model;


/**
 * Default controller for the `test` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public $email = 'laneo2007@yandex.ru';

    public function actionTestTime(){

    }

    public function actionTest(){

        $this->layout = false;
        return $this->render('a');

        /*
        Yii::$app->mailer->compose(['text' => '@app/modules/user/mails/test'])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
            ->setTo($this->email)
            ->setSubject('Email confirmation for ' . Yii::$app->name)
            ->send();
        mail('test@test.com','Email confirmation for ' . Yii::$app->name,'test');
        echo '123';
        */
        /*
        Yii::$app->mailer->compose()
            ->setFrom('89991002878@mail.ru')
            // ->setTo('757537s@mail.ru')
            ->setTo('laneo2007@yandex.ru')
            ->setSubject('Заказ звонка с сайта novaferm.ru')
            //->setTextBody('Ваша заявка №'.$model->id.' принята. В течении недели мы свяжимся с вами, по телефону или по почте.')
            ->setHtmlBody(' ФИО ')
            ->send();
        */
        /*
        $user = new User();
        $user->username ='123';
        $user->email = 'test@test.test';
        $user->setPassword(3423);
        $user->status = User::STATUS_WAIT;

        mail($this->email,
            'Email confirmation for ' . Yii::$app->name,
            Yii::$app->getView()->renderFile('@app/modules/user/mails/emailConfirm.php',['user' => $user])
        );*/
    }




}
