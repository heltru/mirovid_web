<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 14.08.2019
 * Time: 22:01
 */
namespace app\modules\app\fileupload;

use app\components\utils\Transliteration;
use app\modules\admin\models\UploadVideoForm;
use app\modules\file\models\File;
use yii\helpers\StringHelper;
use yii\web\UploadedFile;
use Yii;

class FileUpload
{


    private $dir = 'mirovid/files';
    private $temp_file;

    private $name_file;


    public $is_add=false;

    public function getModelHtmlForm(){
        return new UploadVideoForm();
    }



    public function begin(){


        if (! count($_FILES)){
            return;
        }


        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {

            $this->setNameFilefromFILE();
            $this->safeFileName();
            $this->checkOldCreateDb();
            $this->saveFile();

            $this->is_add = true;

            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }


    }

    private function setNameFilefromFile(){

        $this->name_file = $_FILES['UploadVideoForm']['name']['videoFile'];
        $this->temp_file =  $_FILES['UploadVideoForm']['tmp_name']['videoFile'];

    }

    private function saveFile(){
        $new_file_path = 'mirovid/files/'.$this->name_file;

        file_put_contents(  $new_file_path,file_get_contents($this->temp_file) );
        chmod($new_file_path, 0660);

    }



    private function checkOldCreateDb(){



        $pathinfo = pathinfo($this->name_file);

      //  ex($pathinfo);

        $filename = $pathinfo['filename'];
        $ext = $pathinfo['extension'];

        $oldFile = File::find()->where(['name'=>$filename])->one();

        if ($oldFile !== null){
            $this->name_file .= '_' . rand(1000,9999);
        }



        $file = new File();
        $file->name = $this->name_file;
        $file->path = $this->dir . '/' . $this->name_file;
        $file->save();

        $file_id = $file->id;

        $ord = $this->getFileOrd();

        $this->name_file = $ord . '_' .  $file_id .'_'. $this->name_file;

        $file->name = $this->name_file;
        $file->path = $this->dir . '/' . $this->name_file;
        $file->update(false,['name','path']);

        //check exsisit calc ID  set num thorut db table calcName for File


    }

    private function getFileOrd(){
        return  (int) File::find()->count() ;
    }


    private function safeFileName()
    {
        $filename = $this->name_file;

        $translit = new Transliteration();

        $path_parts = pathinfo($filename);

        $filename = $path_parts['filename'];
        $ext = $path_parts['extension'];

        $filename = mb_ereg_replace("([^\w\s\d\-_~,;\[\]\(\).])", '', $filename);
        $filename = mb_ereg_replace("([\.]{2,})", '', $filename);

        $filename = str_replace('.', '', $filename);
        $smth = preg_replace("/%u([0-9a-f]{3,4})/i", "&#x\\1;", urldecode($filename));
        $filename = $translit->transliterate(html_entity_decode($smth, null, 'UTF-8'));

        $filename = str_replace(' ', '-', $filename);

        $filename = preg_replace('/\s+/', '', $filename);

        $filename = StringHelper::truncate($filename,230);

        $filename = $this->normalizeString($filename);



        $safe_name = $filename . '.' . $ext;



        $this->name_file =  $safe_name;

    }

    private function normalizeString($str = '')
    {
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = strtolower($str);
        $str = html_entity_decode($str, ENT_QUOTES, "utf-8");
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace(' ', '-', $str);
        $str = rawurlencode($str);
        $str = str_replace('%', '-', $str);
        return $str;
    }



}