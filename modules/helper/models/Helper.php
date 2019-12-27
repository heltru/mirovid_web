<?php
/**
 * Created by PhpStorm.
 * User: heltru
 * Date: 18.09.2018
 * Time: 0:36
 */

namespace app\modules\helper\models;


use app\modules\helper\components\Transliteration;

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

    public static function getIsAdmin($user_id){
        $items = \Yii::$app->authManager->getRolesByUser( $user_id);


        return array_key_exists('admin',$items);
    }

    public static function runConsole($command)
    {

        $cmd = $command;

        $cmd = "{$cmd} > /dev/null 2>&1 &";

        pclose(popen($cmd, 'r'));
        return true;



    }


    public static function genColorCodeFromText($text,$min_brightness=100,$spec=10,$crc=true)
    {
        $err = '000000';
        // Check inputs
        if(!is_int($min_brightness)) return $err;
        if(!is_int($spec))  return $err;
        if($spec < 2 or $spec > 10) return $err;
        if($min_brightness < 0 or $min_brightness > 255)  return $err;


        $hash = ($crc) ? md5( crc32($text)) : md5( $text);
        //$hash =  md5( crc32($text));//md5($text);  //Gen hash of text

        $colors = array();
        for($i=0;$i<3;$i++)
            $colors[$i] = max(array(round(((hexdec(substr($hash,$spec*$i,$spec)))/hexdec(str_pad('',$spec,'F')))*255),$min_brightness)); //convert hash into 3 decimal values between 0 and 255

        if($min_brightness > 0)  //only check brightness requirements if min_brightness is about 100
            while( array_sum($colors)/3 < $min_brightness )  //loop until brightness is above or equal to min_brightness
                for($i=0;$i<3;$i++)
                    $colors[$i] += 10;	//increase each color by 10

        $output = '';

        for($i=0;$i<3;$i++)
            $output .= str_pad(dechex($colors[$i]),2,0,STR_PAD_LEFT);  //convert each color to hex and append to output

        return $output;
    }

    public static function nophoto(){
        return '/images/noimage/noimage.jpg';
    }


    public static  function transliterate($txt){


        $smth = preg_replace("/%u([0-9a-f]{3,4})/i","&#x\\1;",urldecode( $txt));
        $txt =  html_entity_decode( $smth,null,'UTF-8');
        $txt = mb_strtolower ($txt);


        $replacement = '-';
        $translator = new Transliteration();
        $translator->standard = Transliteration::GOST_779B;

        $txt = $translator->transliterate($txt);

        $string = preg_replace('/[^a-zA-Z0-9=\s—–-]+/u', '', $txt);
        $string = preg_replace('/[=\s—–-]+/u', $replacement, $string);
        $txt = trim($string, $replacement);
        return $txt;
    }


}