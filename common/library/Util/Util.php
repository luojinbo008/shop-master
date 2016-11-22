<?php
namespace Library\Util;

/* --------------------------------------------*/
/**
    * @Synopsis
 */
/* --------------------------------------------*/
class Util
{

    /* --------------------------------------------*/
    /**
        * @Synopsis gbkToUtf8
        *
        * @Param $str
        *
        * @Returns
     */
    /* --------------------------------------------*/
    public static function gbkToUtf8($str)
    {
        if( ! is_string($str)) {
            $str = strval($str);
        }
        $strs = null;
        try {
            $strs = iconv('GBK', 'UTF-8//IGNORE', $str);
        } catch(\Exception $e) {
            $strs = $str;
        }
        return $strs;
    }/*}}}*/


    /* --------------------------------------------*/
    /**
        * @Synopsis utf8ToGbk
        *
        * @Param $str
        *
        * @Returns
     */
    /* --------------------------------------------*/
    public static function utf8ToGbk($str)
    {
        if( ! is_string($str)) {
            $str = strval($str);
        }
        $strs = null;
        try {
            $strs = iconv('UTF-8', 'GBK//IGNORE', $str);
        } catch(\Exception $e) {
            $strs = $str;
        }
        return $strs;
    }/*}}}*/

    /**
     * @param null $string
     * @return float
     */
    public static function utf8Strlen($string = null)
    {
        //return strlen($string);
        return (mb_strlen($string,"utf8") + strlen($string)) / 2;
    }

    /* --------------------------------------------*/
    /**
        * @Synopsis getClientIp
        *
        * @Returns
     */
    /* --------------------------------------------*/
    public static function getClientIp()
    {/*{{{*/
        if(getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else
            if(getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
                $ip = getenv("HTTP_X_FORWARDED_FOR");
            else
                if(getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
                    $ip = getenv("REMOTE_ADDR");
                else
                    if(isset($_SERVER ['REMOTE_ADDR']) && $_SERVER ['REMOTE_ADDR'] && strcasecmp($_SERVER ['REMOTE_ADDR'], "unknown"))
                        $ip = $_SERVER ['REMOTE_ADDR'];
                    else
                        $ip = "unknown";
        return $ip;
    }/*}}}*/

    /* --------------------------------------------*/
    /**
        * @Synopsis fileGetContentExTen
        *
        * @Param $url
        * @Param $timeout
        *
        * @Returns
     */
    /* --------------------------------------------*/
    public static function fileGetContentExTen($url, $timeout = 6)
    {
        $ctx = stream_context_create(array (
            'http' => array (
                'timeout' => $timeout
            )
        ));
        return file_get_contents($url, 0, $ctx);
    }/*}}}*/


    /* --------------------------------------------*/
    /**
        * @Synopsis Probability
        *
        * @Param $array
        *
        * @Returns
     */
    /* --------------------------------------------*/
    public static function Probability(array $array)
    {
        if (!$array) return 0;
        arsort($array, SORT_NUMERIC);
        $total = array_sum($array);
        $temp = array();
        $add = 0;
        foreach ($array as $k => $val) {
            $add += $val;
            $temp[$k] = $add;
        }
        if (!$temp) return 0;
        $random = mt_rand(1, $total);
        foreach ($temp as $k => $value) {
            if ($random <= $value) {
                return $k;
            }
        }
        return 0;
    }/*}}}*/

    /* --------------------------------------------*/
    /**
        * @Synopsis encryptPrivatekey
        *
        * @Param $bit
        *
        * @Returns
     */
    /* --------------------------------------------*/
    public static function encryptPrivatekey($bit = 128)
    {
        $rsa = new \Library\Encrypt\Crypt\RSA();
        $rsa->setPrivateKeyFormat(CRYPT_RSA_PRIVATE_FORMAT_PKCS1);
        $rsa->setPublicKeyFormat(CRYPT_RSA_PUBLIC_FORMAT_PKCS1);

        $token = $rsa->createKey($bit); /// makes $publickey and $privatekey available
        return $token['privatekey'];
    }/*}}}*/

    /* --------------------------------------------*/
    /**
        * @Synopsis privateKeyReturnPublickey
        *
        * @Param $privatekey
        *
        * @Returns
     */
    /* --------------------------------------------*/
    public static function privateKeyReturnPublickey($privatekey)
    {
        $rsa = new \Library\Encrypt\Crypt\RSA();
		$rsa->loadKey($privatekey);
		$raw = $rsa->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_RAW);
		return $raw['n']->toHex();
    }
    /*}}}*/
    /* --------------------------------------------*/
    /**
        * @Synopsis publicKeyToDecrypt
        *
        * @Param $encrypted
        * @Param $privatekey
        *
        * @Returns
     */
    /* --------------------------------------------*/
    public static function publicKeyToDecrypt($encrypted, $privatekey)
    {
        $rsa = new \Library\Encrypt\Crypt\RSA();
		$encrypted = pack('H*', $encrypted);
		$rsa->loadKey($privatekey);
		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		return $rsa->decrypt($encrypted);
    }/*}}}*/

    /**
     * 上传图片
     * @param $files
     * @param $origin
     * @param $module
     * @param bool $autoName
     * @return bool
     */
    public static function upload($appid, $files, $origin, $module, $directory = '')
    {
        if(!is_array($files) || empty($origin) || empty($module)) {
            return false;
        }
        $ch = curl_init();
        $options = array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HEADER => true,
            CURLOPT_NOBODY => false,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_HTTPHEADER => [
                "Content-Type:multipart/form-data",
                "Authorization:upload.azbzo.com"
            ],
            CURLOPT_URL => 'http://upload.azbzo.com/manage.php?type=upload',
            CURLOPT_TIMEOUT => 3,
        );
        if (function_exists('curl_file_create')) {
            $post['file'] = curl_file_create($files['tmp_name'], $files['type'], $files['name']);
        } else {
            $post['file'] = '@' . $files['tmp_name'] . ';type=' . $files['type'] . ';filename=' . $files['name'];
        }
        $post['origin'] = 'upload_service_' . $origin;
        $post['module'] = $module;
        if ($directory) {
            $post['directory'] = $directory;
        }
        $post['appid'] = (int)$appid;
        $options[CURLOPT_POSTFIELDS] = $post;
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $result = explode("\r\n", $result);
        $result = json_decode(array_pop($result), true);
        if (is_array($result) && isset($result['status']) && (int) $result['status'] === 0) {
            return $result['data'];
        }
        return false;
    }

    public static function resizeImage($image, $width, $height)
    {
        if(empty($image) || empty($width) || empty($height)) {
            return false;
        }
        $ch = curl_init();
        $params = [
            'image'     => $image,
            'width'     => $width,
            'height'    => $height,
        ];
        $options[CURLOPT_URL]  = 'http://upload.azbzo.com/manage.php?type=resize&' . http_build_query($params);
        $options[CURLOPT_RETURNTRANSFER] = 5;
        $options[CURLOPT_CONNECTTIMEOUT] = 5;
        $options[CURLOPT_TIMEOUT] = 30;
        curl_setopt_array($ch, $options);

        $reponse = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        } else {
            $info = curl_getinfo($ch);
            $reponse = substr($reponse, - $info['size_download']);
        }
        curl_close($ch);
        $res = json_decode($reponse, true);
        if (!$res || 0 != $res['status']) {
            return false;
        } else {
            return IMAGE_HTTP . $res['data'];
        }
    }

    public function fileManage($appid, $filter_name = '', $directory = '', $page = 0, $size = 16)
    {
        $ch = curl_init();
        $params = [
            'appid'         => (int)$appid,
            'filter_name'   => $filter_name,
            'directory'     => $directory,
            'page'          => (int)$page,
            'size'          => (int)$size
        ];
        $options[CURLOPT_URL]  = 'http://upload.azbzo.com/manage.php?type=manage&' . http_build_query($params);
        $options[CURLOPT_RETURNTRANSFER] = 5;
        $options[CURLOPT_CONNECTTIMEOUT] = 5;
        $options[CURLOPT_TIMEOUT] = 30;
        curl_setopt_array($ch, $options);

        $reponse = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        } else {
            $info = curl_getinfo($ch);
            $reponse = substr($reponse, - $info['size_download']);
        }
        curl_close($ch);
        $res = json_decode($reponse, true);
        if (!$res || 0 != $res['status']) {
            return false;
        } else {
            return $res['data'];
        }
    }

    /**
     * 创建目录
     * @param $directory
     * @param $folder
     */
    public static function createFile($appid, $folder, $directory = '')
    {
        $ch = curl_init();
        $params = [
            'appid'         => (int)$appid,
            'directory'     => $directory,
            'folder'        => $folder,
        ];
        $options[CURLOPT_URL]  = 'http://upload.azbzo.com/manage.php?type=createFile&' . http_build_query($params);
        $options[CURLOPT_RETURNTRANSFER] = 5;
        $options[CURLOPT_CONNECTTIMEOUT] = 5;
        $options[CURLOPT_TIMEOUT] = 30;
        curl_setopt_array($ch, $options);

        $reponse = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        } else {
            $info = curl_getinfo($ch);
            $reponse = substr($reponse, - $info['size_download']);
        }
        curl_close($ch);
        $res = json_decode($reponse, true);
        if (!$res || 0 != $res['status']) {
            return false;
        } else {
            return $res['data'];
        }
    }

    public static function delFile($appid, $path = [])
    {
        $ch = curl_init();
        $params = [
            'appid' => (int)$appid,
            'path'  => $path,
        ];
        $options[CURLOPT_URL]  = 'http://upload.azbzo.com/manage.php?type=delFile&' . http_build_query($params);
        $options[CURLOPT_RETURNTRANSFER] = 5;
        $options[CURLOPT_CONNECTTIMEOUT] = 5;
        $options[CURLOPT_TIMEOUT] = 30;
        curl_setopt_array($ch, $options);

        $reponse = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception(curl_error($ch));
        } else {
            $info = curl_getinfo($ch);
            $reponse = substr($reponse, - $info['size_download']);
        }
        curl_close($ch);
        $res = json_decode($reponse, true);
        if (!$res || 0 != $res['status']) {
            return false;
        } else {
            return $res['data'];
        }
    }

    public static function ArrayColumn($input, $indexKey, $columnKey)
    {
        $columnKeyIsNumber = (is_numeric($columnKey)) ? true : false;
        $indexKeyIsNull = (is_null($indexKey)) ? true : false;
        $indexKeyIsNumber = (is_numeric($indexKey)) ? true : false;
        $result = array ();
        foreach((array)$input as $key => $row) {
            if($columnKeyIsNumber) {
                $tmp = array_slice($row, $columnKey, 1);
                $tmp = (is_array($tmp) &&  ! empty($tmp)) ? current($tmp) : null;
            } else {
                if (strstr($columnKey, ',')) {
                    $field = explode(',',$columnKey);
                    $c = array();
                    foreach ((array)$field as $fv) {
                        $c[$fv] = isset($row [$fv]) ? $row [$fv] : null;
                    }
                    $tmp = $c;
                } else {
                    $tmp = isset($row [$columnKey]) ? $row [$columnKey] : null;
                }
            }
            if( ! $indexKeyIsNull) {
                if($indexKeyIsNumber) {
                    $key = array_slice($row, $indexKey, 1);
                    $key = (is_array($key) &&  ! empty($key)) ? current($key) : null;
                    $key = is_null($key) ? 0 : $key;
                } else {
                    $key = isset($row [$indexKey]) ? $row [$indexKey] : 0;
                }
            }
            $result [$key] = $tmp;
        }
        return $result;
    }

    /**
     * 二维数组排序
     * @param $array
     * @param $keyid
     * @param string $order
     * @param string $type
     */
    public static function sort_array($array, $keyid, $order='asc', $type='number')
    {
        if(is_array($array)) {
            foreach($array as $val) {
                $order_arr[] = $val[$keyid];
            }
            $order = ($order == 'asc') ? SORT_ASC: SORT_DESC;
            $type  = ($type == 'number') ? SORT_NUMERIC: SORT_STRING;
            array_multisort($order_arr, $order, $type, $array);
            return $array;
        }
    }
}
