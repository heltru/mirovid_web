<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 18.09.2018
 * Time: 0:36
 */

namespace app\modules\helper\models;


class Helper
{

    public  static  function base64_to_jpeg($data, $output_file) {
        // open the output file for writing
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);

        file_put_contents($output_file, $data);

    }

    public  static  function text_to_file($data, $output_file) {
        file_put_contents($output_file, $data);
    }

    public static function mysql_datetime($now_timestamp = false){
        if ($now_timestamp === false){
            return date( 'Y-m-d H:i:s');
        } else {
            return date( 'Y-m-d H:i:s', $now_timestamp );
        }

    }

    public static function runConsole($command)
    {

        $cmd = $command;

        $cmd = "{$cmd} > /dev/null 2>&1 &";

        pclose(popen($cmd, 'r'));
        return true;



    }
}