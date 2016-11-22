<?php
namespace Library\Client;
class HttpClient
{
    private static $init = null;

    /**
     *
     * @param type $apiName
     * @param array $options
     * @return type
     */
    public static function init(array $config = [])
    {
        if (self::$init == null) {
            self::$init = new HttpBase($config);
        }
        return self::$init;
    }

}

class HttpBase
{
    protected $gatewayUri = 'http://interface.azbzo.com/';
    protected $debugGatewayUri = 'http://interface.azbzo.local/';
    protected $params = [];
    protected $apiName = null;
    protected $debug = false;
    public function __construct(array $options = [])
    {
        $this->params = $options;
    }

    public function setDebug($mode = false)
    {
        $this->debug = $mode;
    }

    public function setApiName($apiName)
    {
        $this->apiName = $apiName;
    }

    /**
     * 设置参数组
     * @param array $options
     */
    public function setParameters(array $options)
    {
        foreach ($options as $key => $value) {
            $this->setParameter($key, $value);
        }
    }
    /**
     * 设置参数
     * @param type $key
     * @param type $value
     */
    public function setParameter($key, $value)
    {
        $this->params[$key] = $value;
    }
    /**
     *
     * @param type $apiName
     * @param type $method
     * @param array $options
     * @return type
     */
    public function request($method = 'GET', $apiName = '', array $options = [])
    {
        if (!empty($options)) {
            $this->setOptions($options);
        }
        if (!empty($apiName)) {
            $this->apiName = $apiName;
        }
        $tranMethod = strtoupper($method);
        $url = $this->generateUri($tranMethod);
        return $this->send($url, $tranMethod);
    }
    /**
     *
     * @return type
     * @throws \RuntimeException
     */
    protected function generateSign()
    {
        if (empty($this->params['secret'])) {
            throw new \RuntimeException('The app secret is not empty!');
        }
        $secret = $this->params['secret'];
        unset($this->params['secret']);
        unset($this->params['sign']);
        ksort($this->params);
        $sign = base64_encode(md5(http_build_query($this->params) . $secret));
        $this->params['sign'] = $sign;
        $this->params['secret'] = $secret;
        return $sign;
    }
    /**
     *
     * @param type $method
     * @return type
     * @throws \RuntimeException
     */
    protected function generateUri($method = 'GET')
    {
        $this->generateSign();
        if (empty($this->apiName)) {
            throw new \RuntimeException('The app apiname is not empty!');
        }
        $apiName = trim($this->apiName, '/.');
        $url = $this->gatewayUri;
        if ($this->debug) {
            $url = $this->debugGatewayUri;
        }
        $params = $this->params;
        unset($params['secret']);
        if ($method == 'GET') {
            return $url . $apiName . '?' . http_build_query($params);
        }
        return $url . $apiName;
    }
    /**
     *
     * @param type $url
     * @param type $method
     * @return type
     * @throws \Exception
     */
    protected function send($url, $method = 'GET')
    {
        $ch = curl_init();
        $options[CURLOPT_URL]  = $url;
        if ($method === 'POST') {
            $options[CURLOPT_POST] = true;
            $params = $this->params;
            unset($params['secret']);
            $options[CURLOPT_POSTFIELDS] = http_build_query($params);
        }
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
        return json_decode($reponse, true);
    }
}
