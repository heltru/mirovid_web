<?php

namespace app\modules\image\controllers\console;


use app\modules\image\services\AttImg;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\Console;
use app\modules\image\models\Img;
use Yii;
/**
 * Interactive console image manager
 */
class ImageController extends Controller
{

    //php yii image/image/optimize

    public function actionOptimize()
    {
        $this->checkCount();
        $attimg = new AttImg();

        $imgs = Img::find()->where(['optimize'=>0,'original'=>0])->all();

        foreach ($imgs as $img) {
            /** @var Img $img */
            $this->stdout($img->name_image);
            $this->stdout(PHP_EOL);

            /*$size = $attimg->optimizeImg($img->name_image);


            if ((int)$size) {
                $this->stdout(' OK', Console::FG_GREEN, Console::BOLD);
            } else {

                $this->stderr(' FAIL', Console::FG_RED, Console::BOLD);
            }
            $this->stdout(PHP_EOL);*/

        }

        $this->stdout('Done!', Console::FG_GREEN, Console::BOLD);
        $this->stdout(PHP_EOL);
    }


    private function checkCount(){

        $settings = \Yii::$app->getModule('settings');
        $count =  (int)$settings->getVar('tinypng_count');

        if ($count >= 500){

            $this->stderr(' FAIL', Console::FG_RED, Console::BOLD);
            $this->stdout(' Лимит api ключа исчерпан', Console::FG_GREY);
            $this->stdout(PHP_EOL);

            Yii::$app->end();
        }


    }



    private function readValue($user, $attribute)
    {
        $user->$attribute = $this->prompt(mb_convert_case($attribute, MB_CASE_TITLE, 'utf-8') . ':', [
            'validator' => function ($input, &$error) use ($user, $attribute) {
                $user->$attribute = $input;
                if ($user->validate([$attribute])) {
                    return true;
                } else {
                    $error = implode(',', $user->getErrors($attribute));
                    return false;
                }
            },
        ]);
    }

    /**
     * @param bool $success
     */
    private function log($success)
    {
        if ($success) {
            $this->stdout('Success!', Console::FG_GREEN, Console::BOLD);
        } else {
            $this->stderr('Error!', Console::FG_RED, Console::BOLD);
        }
        $this->stdout(PHP_EOL);
    }
}