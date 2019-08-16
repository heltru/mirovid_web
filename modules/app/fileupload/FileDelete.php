<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 15.08.2019
 * Time: 21:56
 */

namespace app\modules\app\fileupload;


class FileDelete
{

    public function delete($path){
        if (is_file($path)){
            unlink($path);
        }
    }

}