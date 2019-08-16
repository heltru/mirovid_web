<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 15.08.2019
 * Time: 22:05
 */

namespace app\modules\app\fileupload;


class DirFile
{

    public function getListVideo()
    {

        $video = [];

        try{
            foreach (scandir(  $this->video_dir) as $file) {
                $file_path = $this->video_dir . '/' . $file;
                if (!is_file($file_path)) {
                    continue;
                }
                $path_info = pathinfo($file_path);
                $filename = $path_info['filename'] .'.'.$path_info['extension'];
                $video[] = $filename;
            }
        } catch (\Exception $e){

        }

        return $video;
    }
}