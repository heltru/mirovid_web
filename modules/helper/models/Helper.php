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

    public static function download_send_headers($filename) {
        // disable caching
        $now = gmdate("D, d M Y H:i:s");
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Cache-Control: max-age=0, no-cache, must-revalidate, proxy-revalidate");
        header("Last-Modified: {$now} GMT");

        // force download
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");

        // disposition / encoding on response body
        header("Content-Disposition: attachment;filename={$filename}");
        header("Content-Transfer-Encoding: binary");
    }


    public static function array2csv(array &$array)
    {
        if (count($array) == 0) {
            return null;
        }
        ob_start();
        $df = fopen("php://output", 'w');
        fputcsv($df, array_keys(reset($array)));
        foreach ($array as $row) {
            fputcsv($df, $row);
        }
        fclose($df);
        return ob_get_clean();
    }


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

    public static function curl_get($url,$query_data,$options=[],$deb = false){


        if ($deb){
            ex($url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($query_data));
        }

        $defaults = array(
            CURLOPT_URL => $url. (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($query_data),
            CURLOPT_HEADER => 0,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_TIMEOUT => 10, //Максимальное количество секунд для выполнения функций cURL
            CURLOPT_CONNECTTIMEOUT => 10, //Количество секунд ожидания при попытке подключения,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,

        );



        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        if( ! $result = curl_exec($ch))
        {
            try {
                trigger_error(curl_error($ch));


            } catch (\Exception $e) {
                curl_close($ch);
                return null;
            }

        }
        curl_close($ch);
        return $result;
    }

    static function sanitizeURL($text)
    {
        return self::sanitizeString($text, ['max_length' => 255, 'filter' => FILTER_SANITIZE_URL]);
    }

    /**
     * Обработка строк
     * @param $text
     * @param array $options
     * @return string
     */
    static function sanitizeString($text, array $options = [])
    {
        // default params
        if (!isset($options['quote_style'])) $options['quote_style'] = ENT_QUOTES;
        if (!isset($options['allowable_tags'])) $options['allowable_tags'] = null;
        if (!isset($options['charset'])) $options['charset'] = 'UTF-8';
        if (!isset($options['trim_charlist'])) $options['trim_charlist'] = null;
        if (!isset($options['max_length'])) $options['max_length'] = null;
        if (!isset($options['filter'])) $options['filter'] = null;

        // default functions
        if (!isset($options['htmlentities'])) $options['htmlentities'] = 1;
        if (!isset($options['strip_tags'])) $options['strip_tags'] = 1;
        if (!isset($options['trim'])) $options['trim'] = 1;

        if (isset($options['filter']) && $options['filter']) {
            $text = filter_var($text, $options['filter']);
        }

        if (isset($options['remove_emoji']) && $options['remove_emoji']) {
            $text = self::removeEmoji($text);
        }

        if (isset($options['max_length']) && $options['max_length']) {
            $text = mb_substr($text, 0, $options['max_length']); // обрезание строки до преобразования
        }

        if (isset($options['htmlentities']) && $options['htmlentities']) {
            $text = htmlentities($text, $options['quote_style'], $options['charset']);
        }

        if (isset($options['strip_tags']) && $options['strip_tags']) {
            $text = strip_tags($text, $options['allowable_tags']); // удаление тегов до обрезания
        }

        if (isset($options['trim_charlist']) && $options['trim_charlist']) {
            $text = trim($text, $options['trim_charlist']); // custom trim
        }

        if ($options['trim']) {
            $text = trim($text); // simple trim
        }

        if (isset($options['lower']) && $options['lower']) {
            $text = mb_strtolower($text);
        }

        return (string)$text;
    }

    static function removeEmoji($text)
    {
        // pack 1
        $regexEmoticons = '/[\x{1F000}-\x{1FFFF}]/u';
        $text = preg_replace($regexEmoticons, '', $text);

        // pack 2
        $regex2 = '/[\x{2000}-\x{2FFF}]/u';
        $text = preg_replace($regex2, '', $text);

        // pack 3
        $regex3 = '/[\x{3030}\x{303D}\x{3297}\x{3299}]/u';
        $text = preg_replace($regex3, '', $text);

        return $text;
    }

    public static function assoc_model($params, &$model){
        foreach ($params as $key => $value){
            $model->setAttribute($key,$value);
        }
    }

    public static function mime2ext($mime) {
        $mime_map = [
            'video/3gpp2'                                                               => '3g2',
            'video/3gp'                                                                 => '3gp',
            'video/3gpp'                                                                => '3gp',
            'application/x-compressed'                                                  => '7zip',
            'audio/x-acc'                                                               => 'aac',
            'audio/ac3'                                                                 => 'ac3',
            'application/postscript'                                                    => 'ai',
            'audio/x-aiff'                                                              => 'aif',
            'audio/aiff'                                                                => 'aif',
            'audio/x-au'                                                                => 'au',
            'video/x-msvideo'                                                           => 'avi',
            'video/msvideo'                                                             => 'avi',
            'video/avi'                                                                 => 'avi',
            'application/x-troff-msvideo'                                               => 'avi',
            'application/macbinary'                                                     => 'bin',
            'application/mac-binary'                                                    => 'bin',
            'application/x-binary'                                                      => 'bin',
            'application/x-macbinary'                                                   => 'bin',
            'image/bmp'                                                                 => 'bmp',
            'image/x-bmp'                                                               => 'bmp',
            'image/x-bitmap'                                                            => 'bmp',
            'image/x-xbitmap'                                                           => 'bmp',
            'image/x-win-bitmap'                                                        => 'bmp',
            'image/x-windows-bmp'                                                       => 'bmp',
            'image/ms-bmp'                                                              => 'bmp',
            'image/x-ms-bmp'                                                            => 'bmp',
            'application/bmp'                                                           => 'bmp',
            'application/x-bmp'                                                         => 'bmp',
            'application/x-win-bitmap'                                                  => 'bmp',
            'application/cdr'                                                           => 'cdr',
            'application/coreldraw'                                                     => 'cdr',
            'application/x-cdr'                                                         => 'cdr',
            'application/x-coreldraw'                                                   => 'cdr',
            'image/cdr'                                                                 => 'cdr',
            'image/x-cdr'                                                               => 'cdr',
            'zz-application/zz-winassoc-cdr'                                            => 'cdr',
            'application/mac-compactpro'                                                => 'cpt',
            'application/pkix-crl'                                                      => 'crl',
            'application/pkcs-crl'                                                      => 'crl',
            'application/x-x509-ca-cert'                                                => 'crt',
            'application/pkix-cert'                                                     => 'crt',
            'text/css'                                                                  => 'css',
            'text/x-comma-separated-values'                                             => 'csv',
            'text/comma-separated-values'                                               => 'csv',
            'application/vnd.msexcel'                                                   => 'csv',
            'application/x-director'                                                    => 'dcr',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
            'application/x-dvi'                                                         => 'dvi',
            'message/rfc822'                                                            => 'eml',
            'application/x-msdownload'                                                  => 'exe',
            'video/x-f4v'                                                               => 'f4v',
            'audio/x-flac'                                                              => 'flac',
            'video/x-flv'                                                               => 'flv',
            'image/gif'                                                                 => 'gif',
            'application/gpg-keys'                                                      => 'gpg',
            'application/x-gtar'                                                        => 'gtar',
            'application/x-gzip'                                                        => 'gzip',
            'application/mac-binhex40'                                                  => 'hqx',
            'application/mac-binhex'                                                    => 'hqx',
            'application/x-binhex40'                                                    => 'hqx',
            'application/x-mac-binhex40'                                                => 'hqx',
            'text/html'                                                                 => 'html',
            'image/x-icon'                                                              => 'ico',
            'image/x-ico'                                                               => 'ico',
            'image/vnd.microsoft.icon'                                                  => 'ico',
            'text/calendar'                                                             => 'ics',
            'application/java-archive'                                                  => 'jar',
            'application/x-java-application'                                            => 'jar',
            'application/x-jar'                                                         => 'jar',
            'image/jp2'                                                                 => 'jp2',
            'video/mj2'                                                                 => 'jp2',
            'image/jpx'                                                                 => 'jp2',
            'image/jpm'                                                                 => 'jp2',
            'image/jpeg'                                                                => 'jpeg',
            'image/pjpeg'                                                               => 'jpeg',
            'application/x-javascript'                                                  => 'js',
            'application/json'                                                          => 'json',
            'text/json'                                                                 => 'json',
            'application/vnd.google-earth.kml+xml'                                      => 'kml',
            'application/vnd.google-earth.kmz'                                          => 'kmz',
            'text/x-log'                                                                => 'log',
            'audio/x-m4a'                                                               => 'm4a',
            'audio/mp4'                                                                 => 'm4a',
            'application/vnd.mpegurl'                                                   => 'm4u',
            'audio/midi'                                                                => 'mid',
            'application/vnd.mif'                                                       => 'mif',
            'video/quicktime'                                                           => 'mov',
            'video/x-sgi-movie'                                                         => 'movie',
            'audio/mpeg'                                                                => 'mp3',
            'audio/mpg'                                                                 => 'mp3',
            'audio/mpeg3'                                                               => 'mp3',
            'audio/mp3'                                                                 => 'mp3',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'application/oda'                                                           => 'oda',
            'audio/ogg'                                                                 => 'ogg',
            'video/ogg'                                                                 => 'ogg',
            'application/ogg'                                                           => 'ogg',
            'font/otf'                                                                  => 'otf',
            'application/x-pkcs10'                                                      => 'p10',
            'application/pkcs10'                                                        => 'p10',
            'application/x-pkcs12'                                                      => 'p12',
            'application/x-pkcs7-signature'                                             => 'p7a',
            'application/pkcs7-mime'                                                    => 'p7c',
            'application/x-pkcs7-mime'                                                  => 'p7c',
            'application/x-pkcs7-certreqresp'                                           => 'p7r',
            'application/pkcs7-signature'                                               => 'p7s',
            'application/pdf'                                                           => 'pdf',
            'application/octet-stream'                                                  => 'pdf',
            'application/x-x509-user-cert'                                              => 'pem',
            'application/x-pem-file'                                                    => 'pem',
            'application/pgp'                                                           => 'pgp',
            'application/x-httpd-php'                                                   => 'php',
            'application/php'                                                           => 'php',
            'application/x-php'                                                         => 'php',
            'text/php'                                                                  => 'php',
            'text/x-php'                                                                => 'php',
            'application/x-httpd-php-source'                                            => 'php',
            'image/png'                                                                 => 'png',
            'image/x-png'                                                               => 'png',
            'application/powerpoint'                                                    => 'ppt',
            'application/vnd.ms-powerpoint'                                             => 'ppt',
            'application/vnd.ms-office'                                                 => 'ppt',
            'application/msword'                                                        => 'doc',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/x-photoshop'                                                   => 'psd',
            'image/vnd.adobe.photoshop'                                                 => 'psd',
            'audio/x-realaudio'                                                         => 'ra',
            'audio/x-pn-realaudio'                                                      => 'ram',
            'application/x-rar'                                                         => 'rar',
            'application/rar'                                                           => 'rar',
            'application/x-rar-compressed'                                              => 'rar',
            'audio/x-pn-realaudio-plugin'                                               => 'rpm',
            'application/x-pkcs7'                                                       => 'rsa',
            'text/rtf'                                                                  => 'rtf',
            'text/richtext'                                                             => 'rtx',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'application/x-stuffit'                                                     => 'sit',
            'application/smil'                                                          => 'smil',
            'text/srt'                                                                  => 'srt',
            'image/svg+xml'                                                             => 'svg',
            'application/x-shockwave-flash'                                             => 'swf',
            'application/x-tar'                                                         => 'tar',
            'application/x-gzip-compressed'                                             => 'tgz',
            'image/tiff'                                                                => 'tiff',
            'font/ttf'                                                                  => 'ttf',
            'text/plain'                                                                => 'txt',
            'text/x-vcard'                                                              => 'vcf',
            'application/videolan'                                                      => 'vlc',
            'text/vtt'                                                                  => 'vtt',
            'audio/x-wav'                                                               => 'wav',
            'audio/wave'                                                                => 'wav',
            'audio/wav'                                                                 => 'wav',
            'application/wbxml'                                                         => 'wbxml',
            'video/webm'                                                                => 'webm',
            'image/webp'                                                                => 'webp',
            'audio/x-ms-wma'                                                            => 'wma',
            'application/wmlc'                                                          => 'wmlc',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
            'font/woff'                                                                 => 'woff',
            'font/woff2'                                                                => 'woff2',
            'application/xhtml+xml'                                                     => 'xhtml',
            'application/excel'                                                         => 'xl',
            'application/msexcel'                                                       => 'xls',
            'application/x-msexcel'                                                     => 'xls',
            'application/x-ms-excel'                                                    => 'xls',
            'application/x-excel'                                                       => 'xls',
            'application/x-dos_ms_excel'                                                => 'xls',
            'application/xls'                                                           => 'xls',
            'application/x-xls'                                                         => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
            'application/vnd.ms-excel'                                                  => 'xlsx',
            'application/xml'                                                           => 'xml',
            'text/xml'                                                                  => 'xml',
            'text/xsl'                                                                  => 'xsl',
            'application/xspf+xml'                                                      => 'xspf',
            'application/x-compress'                                                    => 'z',
            'application/x-zip'                                                         => 'zip',
            'application/zip'                                                           => 'zip',
            'application/x-zip-compressed'                                              => 'zip',
            'application/s-compressed'                                                  => 'zip',
            'multipart/x-zip'                                                           => 'zip',
            'text/x-scriptzsh'                                                          => 'zsh',
        ];

        return isset($mime_map[$mime]) ? $mime_map[$mime] : false;
    }


}