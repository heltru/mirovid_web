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
use app\modules\file\models\FilePreview;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;
use yii\web\UploadedFile;
use Yii;

class FileUpload
{


    public $dir = 'mirovid/files';
    private $temp_file;

    private $name_file;


    public $is_add=false;
    public $form_name = 'Reklamir';
    public $field_name = 'uploadFile';

    private $file_db;

    public function __construct($dir = 'mirovid/files',$form_name='Reklamir',$field_name='uploadFile')
    {
        $this->form_name = $form_name;
        $this->field_name = $field_name;
        $this->dir = $dir;
    }

    public function getModelHtmlForm(){
        return new UploadVideoForm();
    }


    public function begin(){
        if (isset($_FILES['Reklamir']) && isset($_FILES['Reklamir']['name']) && isset($_FILES['Reklamir']['name']['uploadFile']) &&
            $_FILES['Reklamir']['name']['uploadFile'] == ''   ){
            return ;
        }
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
            ex($e->getMessage());
            $transaction->rollBack();
            throw $e;
        }




    }

    private function setNameFilefromFile(){
        $this->name_file = $_FILES[$this->form_name]['name'][$this->field_name];
        $this->temp_file =  $_FILES[$this->form_name]['tmp_name'][$this->field_name];
    }

    private function saveFile(){

        if (! is_dir($this->dir)){
            FileHelper::createDirectory($this->dir);
        }

        $new_file_path = $this->dir . '/' . $this->name_file;

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



        if ( ! is_object($this->file_db)){
            $this->file_db = new File();
        } else {
            @unlink($this->file_db->path);
        }


        $this->file_db->name = $this->name_file;
        $this->file_db->path = $this->dir . '/' . $this->name_file;
        $this->file_db->save();



        $reklamir_id = $this->file_db->id;

        $ord = $this->getFileOrd();

        $this->name_file = $ord . '_' .  $reklamir_id .'_'. $this->name_file;

        $this->file_db->name = $this->name_file;
        $this->file_db->path = $this->dir . '/' . $this->name_file;
        $this->file_db->update(false,['name','path']);

        //check exsisit calc ID  set num thorut db table calcName for File


    }


    public function create_preview(){

        $slides = 10;

        $sec = 5;
        $movie = $this->file_db->path;

        $pathinfo = pathinfo($this->name_file);
        $filename = $pathinfo['filename'];

        $ext = $pathinfo['extension'];

        if (in_array($ext,['png','jpg','jpeg','gif','bmp'])){
            return;
        }


        $thumbnail = $this->dir. '/' . $filename. '.png';

        $ffmpeg =  FFMpeg::create(['ffmpeg.binaries'  => 'C:/FFmpeg/bin/ffmpeg.exe', // the path to the FFMpeg binary
            'ffprobe.binaries' => 'C:/FFmpeg/bin/ffprobe.exe', // the path to the FFProbe binary
            'timeout'          => 3600, // the timeout for the underlying process
            'ffmpeg.threads'   => 12,   // the number of threads that FFMpeg should use
            ]);
        $video = $ffmpeg->open($movie);
        /*
        $duration =
            $video->getFFProbe()->format($movie)
                ->get('duration');
        $duration = (int) floor($duration);
        $slide_dx =  $duration / $slides;
        for ($i=0;$i<=9;$i++){

            $thumbnail = $this->dir. '/' . $filename . '_' . $i. '.png';
            $frame = $video->frame(TimeCode::fromSeconds(round($i*$slide_dx)));
            $frame->save($thumbnail);

            $fp = new FilePreview();
            $fp->file_id = $this->file_db->id;
            $fp->path_preview = $thumbnail;
            $fp->save();
            if (count($fp->getErrors())){
                ex($fp->getErrors());
            }
        }
*/
       // ex($duration);

        $thumbnail = $this->dir. '/' . $filename. '.png';
        $frame = $video->frame(TimeCode::fromSeconds($sec));
        $frame->save($thumbnail);

        $this->file_db->path_preview =  $thumbnail;
        $this->file_db->update(false,['path_preview']);

    }


    private function getFileOrd(){
        return  (int) File::find()->count() ;
    }

    public function getFileModel(){
        return $this->file_db;
    }

    public function setFileModel($model){
        return $this->file_db = $model;
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

        $filename = StringHelper::truncate($filename,220);

        $filename = $filename . '_' . time();

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