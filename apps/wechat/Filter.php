<?php
namespace Wechat;
/**
 * 过滤器
 * Class Filter
 * @package Front
 */
use Phalcon\Mvc\Url;
class Filter
{
    public $di;
    public $storeInfo = null;
    public $wechatConfig = null;

    public function __construct($di)
    {
        $this->di = $di;
    }

    /**
    * 登入过滤器 wechat
    * @return bool
    */
    public function authLogin()
    {
        if (null === $this->storeInfo) {
            $this->storeInfo = $this->getStoreInfo();
        }
        if (null === $this->wechatConfig) {
            $this->wechatConfig = $this->getWechatConfig();
        }

        $storeId = $this->storeInfo['store_id'];
        if ($this->di->get('session')->get($storeId . '_' . 'customer_id')) {
           return true;
        }
        $user_agent = $this->di->get('request')->getServer('HTTP_USER_AGENT');
        if (strpos($user_agent, 'MicroMessenger') == true) {
            // 微信浏览器 自动登陆
            $openId = $this->di->get('session')->get($storeId . '_wechat_openid');
            if (!$openId) {
               if ($this->di->get('request')->isAjax()) {
                   // 异步上来，无法跳转
                   $this->di->get('response')->setContentType('application/json', 'UTF-8');
                   $this->di->get('response')->setJsonContent(["status" => 202, "info" => "get open_id error"]);
                   $this->di->get('response')->send();
                   die();
               }
               $openId = $this->GetOpenid();
               $this->di->get('session')->set($storeId . '_wechat_openid', $openId);
            }
            $httpClient = $this->di->get('httpClient');
            $httpClient->setApiName("customer/getStoreCustomerInfo.json");
            $httpClient->setParameters([
               'store_id'  => $storeId,
               'type'      => 'open_id',
               'value'     => $openId,
            ]);
            $result = $httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                if ($this->di->get('request')->isAjax()) {
                    // 异步上来，无法跳转
                    $this->di->get('response')->setContentType('application/json', 'UTF-8');
                    $this->di->get('response')->setJsonContent(["status" => 203, "info" => "get customer error"]);
                    $this->di->get('response')->send();
                    die();
                }
                $this->di->get('response')->redirect("wechat/user/register")->sendHeaders();
                die();
            }
            $customerInfo = $result['data']['info'];
            $this->di->get('session')->set($storeId . '_' . 'customer_id', $customerInfo['customer_id']);
            $this->di->get('session')->set($storeId . '_' . 'customer_group_id',  $customerInfo['customer_group_id']);
            $this->di->get('session')->set($storeId . '_' . 'customer_telephone',  $customerInfo['telephone']);
            $this->di->get('session')->set($storeId . '_' . 'customer_idcard',  $customerInfo['idcard']);
            $this->di->get('session')->set($storeId . '_' . 'customer_fullname',  $customerInfo['fullname']);
            $this->di->get('session')->set($storeId . '_' . 'customer_nickname',  $customerInfo['nickname']);
            $this->di->get('session')->set($storeId . '_' . 'customer_group_name',  $customerInfo['group_name']);
            return true;
        }
        $this->di->get('response')->redirect("wechat/user/register")->sendHeaders();
        die();
    }

    /**
     * wechat 浏览器验证
     * @return bool
     * @throws \Exception
     */
    public function filterOpenid()
    {
        $user_agent = $this->di->get('request')->getServer('HTTP_USER_AGENT');
        if (strpos($user_agent, 'MicroMessenger') !== true) {
            if (null === $this->storeInfo) {
                $this->storeInfo = $this->getStoreInfo();
            }
            if (null === $this->wechatConfig) {
                $this->wechatConfig = $this->getWechatConfig();
            }
            $storeId = $this->storeInfo['store_id'];
            if ($this->di->get('session')->get($storeId . '_wechat_openid')) {
                return true;
            }
            //微信浏览器 自动登陆
            $openId = $this->di->get('session')->get('wechat_openid');
            if (!$openId) {
                if ($this->di->get('request')->isAjax()) {
                    //异步上来，无法跳转
                    $this->di->get('response')->setContentType('application/json', 'UTF-8');
                    $this->di->get('response')->setJsonContent(["status" => 202, "info" => "页面过期，请重新打开页面！"]);
                    $this->di->get('response')->send();
                    die();
                }
                $openId = $this->GetOpenid();
                $this->di->get('session')->set($storeId . '_wechat_openid', $openId);
            }
            return true;
        } else {
            throw new \Exception("请从微信端登陆！");
        }
    }

    /**
     * 获得当前商店基本信息
     * @return mixed
     * @throws \Exception
     */
    protected function getStoreInfo()
    {
        $url = $this->di->get('url')->get(['for' => 'shop-index']);
        $keyValue = md5('SHOP_INFO_' . $url);
        $storeInfo = $this->di->get('cache')->get($keyValue);
        if (!$storeInfo) {
            $httpClient = $this->di->get('httpClient');
            $httpClient->setApiName("setting/getStoreInfo.json");
            $httpClient->setParameters([
                'value' => $url,
                'type'  => 'url'
            ]);
            $result = $httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                throw new \Exception("获得商店基本信息失败！");
            }
            $storeInfo = $result['data']['info'];
            $this->di->get('cache')->save($keyValue, $storeInfo);
        }
        return $storeInfo;
    }

    /**
     * 获得授权微信公众号信息
     * @return mixed
     * @throws \Exception
     */
    protected function getWechatConfig()
    {
        $httpClient = $this->di->get('httpClient');
        $httpClient->setApiName("wechat/getConfig.json");
        $result = $httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            throw new \Exception("请授权公众号！");
        }
        $config = $result['data']['info'];
        return $config;
    }

    /**
     * web端获得open_id
     * @return mixed
     */
    private function GetOpenid()
    {
        $appInfo = $this->di->get('appInfo');
        if ('10002' == $appInfo['appid']) {
            return 'oTUf7jk_XjnEs5fEwek1HhflUz_I';
        }
        // 通过code获得openid
        $request = $this->di->get('request');
        $data = $request->get();
        if (!isset($data['code'])){
            $url = $this->__CreateOauthUrlForCode();
            Header("Location: $url");
            exit();
        } else {
            $code = $data['code'];
            $openid = $this->GetOpenidFromMp($code);
            return $openid;
        }
    }

    /**
     * @param $redirectUrl
     * @return string
     */
    private function __CreateOauthUrlForCode()
    {
        $config = $this->wechatConfig;
        $request = $this->di->get('request');
        $redirectUrl = urlencode("http://interface.azbzo.com/wechat/redirect/" . $config['authorizer_appid'] . '/' . urlencode($request->getURI()));
        $urlObj["appid"] = $config['authorizer_appid'];
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE";
        $urlObj["component_appid"] = $config['component_appid'] . "#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }

    /**
     * @param $urlObj
     * @return string
     */
    private function ToUrlParams($urlObj)
    {
        $buff = "";
        foreach ($urlObj as $k => $v) {
            if($k != "sign"){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * @param $code
     * @return mixed
     * @throws \Exception
     */
    private function GetOpenidFromMp($code)
    {
        $url = $this->__CreateOauthUrlForOpenid($code);
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        //取出openid
        $data = json_decode($res,true);
        if(isset($data['errcode'])){
            throw new \Exception($data['errcode'] . "|" . $data["errmsg"]);
        }
        $openid = $data['openid'];
        return $openid;
    }

    /**
     * @param $code
     * @return string
     */
    private function __CreateOauthUrlForOpenid($code)
    {
        $config = $this->wechatConfig;
        $urlObj["appid"] = $config['authorizer_appid'];
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $urlObj["component_appid"] = $config["component_appid"];
        $urlObj["component_access_token"] = $config["component_access_token"];
        $bizString = $this->ToUrlParams($urlObj);

        return "https://api.weixin.qq.com/sns/oauth2/component/access_token?".$bizString;
    }
}