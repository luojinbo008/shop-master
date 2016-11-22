<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 15-11-17
 * Time: 下午6:49
 */
namespace Library\Client;

class WechatClient extends HttpWechat
{
    static $init = null;

    /**
     * 初始化
     * @return WechatCallbackApi|null
     */
    public static function init()
    {
        if(null == self::$init) {
            $url = "https://api.weixin.qq.com/";
            self::$init =  new self($url);
        }
        return self::$init;
    }

    /**
     * @param $path
     * @param array $params
     * @param string $method
     * @return array|bool
     * @throws Exception
     */
    public function callApi($path, $params = [], $method = 'GET')
    {
        if (!isset($this->url)) {
            throw  new Exception(" API HOST  IS NULL.");
        }
        $result = false;
        if (strtoupper($method) === 'GET') {
            $result = $this->get($path,  $params);
        } else if (strtoupper($method) === 'POST') {
            $result = $this->post($path, $params);
        } else if (strtoupper($method) === 'FILE') {
            $result = $this->file($path, $params);
        }
        return $result;
    }
}

class HttpWechat
{
    public $url;
    public $path;
    public $method;
    public $cookies = [];

    /**
     * HttpClient constructor.
     * @param $url
     */
    function __construct($url)
    {
        if (is_string($url) && substr($url, 0, 8) !== 'https://' && substr($url, 0, 7) !== 'http://') {
            throw new Exception("url protocal error, must be http:// or https://");
        }
        $this->url = $url;
    }

    /**
     * @param $path
     * @param bool $params
     * @param bool $cookies
     * @param bool $header
     * @return array
     */
    function get($path, $params = false, $cookies = false, $header = false)
    {
        $this->path = $path;
        $this->method = 'GET';
        $curl = $this->url.$this->path;
        return self::quickRequest($curl, $params, $cookies, $header, $this->method);
    }

    /**
     * @param $path
     * @param array $params
     * @param bool $cookies
     * @param bool $header
     * @return array
     */
    function post($path, $params = array(), $cookies = false, $header = false)
    {
        $this->path = $path;
        $this->method = 'POST';
        $curl = $this->url.$this->path;
        return self::quickRequest($curl, $params, $cookies, $header, $this->method);
    }

    /**
     * @param $path
     * @param array $params
     * @param bool $cookies
     * @param bool $header
     * @return array
     */
    function file($path, $params = array(), $cookies = false, $header = false)
    {
        $this->path = $path;
        $this->method = 'FILE';
        $curl = $this->url.$this->path;
        return self::quickRequest($curl, $params, $cookies, $header, $this->method);
    }

    /**
     * @param $params
     * @return string
     */
    static public function buildQueryString($params)
    {
        return http_build_query($params);
    }

    /**
     * @param $cookies
     * @return string
     */
    static public function buildCookieString($cookies)
    {
        $cookie_string = '';
        if (is_array($cookies)) {
            foreach ($cookies as $key => $value) {
                if($value) {
                    array_push($cookie_string, $key . '=' . $value);
                }
            }
            $cookie_string = join('; ', $cookie_string);
        } else {
            $cookie_string = $cookies;
        }
        return $cookie_string;
    }

    /**
     * 执行一个 HTTP 请求
     *
     * @param string 	$url
     * @param $params 表单参数(array)
     * @param $cookie cookie参数 (array)
     * @param string	$method (post / get)
     * @return array 结果数组
     */
    static public function quickRequest($url, $params = false, $cookie= false, $header= false, $method='get')
    {
        if ('FILE' == strtoupper($method)) {
            $query_string = json_encode($params,JSON_UNESCAPED_UNICODE);
        } else {
            $query_string = self::buildQueryString($params);
        }
        $cookie_string = self::buildCookieString($cookie);
        $ch = curl_init();

        if ('GET' == strtoupper($method)) {
            if(!empty($query_string)) {
                curl_setopt($ch, CURLOPT_URL, $url."?".$query_string);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        } elseif ('FILE' == strtoupper($method)) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json; charset=utf-8',
                    'Content-Length: ' . strlen($query_string)]
            );
        } else {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $query_string);
        }

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);

        // disable 100-continue
        if (is_array($header)) {
            $header[] = 'Expect:';
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        }

        if (!empty($cookie_string)) {
            curl_setopt($ch, CURLOPT_COOKIE, $cookie_string);
        }

        if (stripos($url, 'https://') === 0) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        $result = curl_exec($ch);
        $err = curl_error($ch);

        if (false === $result || !empty($err)) {
            $errno = curl_errno($ch);
            $info = curl_getinfo($ch);
            curl_close($ch);

            return [
                'succ'      => false,
                'errno'     => $errno,
                'errmsg'    => $err,
                'info'      => $info,
            ];
        }
        curl_close($ch);
        $result = str_replace("&&&START&&&","",$result);
        return [
            'succ'      => true,
            'result'    => $result,
        ];
    }
}