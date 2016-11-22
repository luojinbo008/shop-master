<?php
namespace Wechat\Controllers;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
/**
 * Class ControllerBase
 * @package WeiXin\Front\Controllers
 */

class BaseController extends Controller
{

    public $store_id = null;
    public $store_name = null;
    public $default_customer_group_id = null;
    public $industry = null;
    public $customer_group_id = null;
    public $customer_id = null;
    public $customer_fullname = null;
    public $customer_nickname = null;
    public $customer_groupname = null;
    public $customer_telephone = null;
    public $customer_idcard = null;
    /**
     * @param Dispatcher $dispatcher
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $storeInfo = $this->getStoreInfo();
        $this->store_id = $storeInfo['store_id'];
        $this->store_name = $storeInfo['name'];
        $this->customer_id = $this->session->get($this->store_id . '_' . 'customer_id');
        $this->customer_fullname = $this->session->get($this->store_id . '_' . 'customer_fullname');
        $this->customer_nickname = $this->session->get($this->store_id . '_' . 'customer_nickname');
        $this->customer_groupname = $this->session->get($this->store_id . '_' . 'customer_group_name');
        $this->customer_telephone = $this->session->get($this->store_id . '_' . 'customer_telephone');
        $this->customer_idcard = $this->session->get($this->store_id . '_' . 'customer_idcard');

        $this->default_customer_group_id = $storeInfo['customer_group_id'];
        $this->industry = $storeInfo['industry'];
        if (!$this->request->isAjax()) {
            if (!empty($this->industry)) {
                $this->view->setViewsDir(__DIR__ . '/../views/' . $this->industry . '/');
            }
            $this->view->setVars([
                    'store_name' => $this->store_name,
                ]
            );
        }
    }

    /**
     * 活动当前商店基本信息
     * @return mixed
     * @throws \Exception
     */
    protected function getStoreInfo()
    {
        $url = $this->url->get(['for' => 'shop-index']);
        $keyValue = md5('SHOP_INFO_' . $url);
        $storeInfo = $this->cache->get($keyValue);
        if (!$storeInfo) {
            $this->httpClient->setApiName("setting/getStoreInfo.json");
            $this->httpClient->setParameters([
                'value' => $url,
                'type'  => 'url'
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                throw new \Exception("获得商店基本信息失败！");
            }
            $storeInfo = $result['data']['info'];
            $this->cache->save($keyValue, $storeInfo);
        }
        return $storeInfo;
    }

    /**
     * 获得热门商品
     * @param int $start
     * @param int $limit
     * @return mixed
     * @throws \Exception
     */
    protected function getHotProducts($start = 0, $limit = 8)
    {
        $filter = [
            'customer_group_id' => $this->customer_group_id ? $this->customer_group_id : $this->default_customer_group_id,
            'store_id'          => $this->store_id,
            'start'             => $start,
            'limit'             => $limit,
            'sort'              => 'quantity',
            'order'             => 'ASC'
        ];
        $this->httpClient->setApiName("product/getProductsByStore.json");
        $this->httpClient->setParameters($filter);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            throw new \Exception("获得商品信息列表失败！");
        }
        return $result['data'];
    }

    /**
     * 获得置顶分类（商品）
     */
    public function getTopCategories($product_limit = 3)
    {
        $filter = [
            'customer_group_id' => $this->customer_group_id ? $this->customer_group_id : $this->default_customer_group_id,
            'store_id'          => $this->store_id,
            'product_limit'     => $product_limit,
        ];
        $this->httpClient->setApiName("product/getStoreTopCategoryList.json");
        $this->httpClient->setParameters($filter);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            throw new \Exception("获得商品信息列表失败！");
        }
        return $result['data'];
    }

    /**
     * 获得我的购物车商品数量
     * @return int
     */
    protected function getCartProductCount()
    {
        if (!$this->customer_id) {
            return 0;
        }
        $session_id = $this->di->get("session")->getId();
        $filter = [
            'store_id'          => $this->store_id,
            'customer_id'       => $this->customer_id,
            'session_id'        => $session_id
        ];
        $this->httpClient->setApiName("customer/getCartProductCountByCustomer.json");
        $this->httpClient->setParameters($filter);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            return 0;
        }
        return $result['data']['count'];
    }

    /**
     * @param Dispatcher $dispatcher
     * @return mixed
     */
    protected function afterExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->request->isAjax() === true){
            $this->view->disableLevel([
                View::LEVEL_ACTION_VIEW => true,
                View::LEVEL_LAYOUT => true,
                View::LEVEL_MAIN_LAYOUT => true,
                View::LEVEL_AFTER_TEMPLATE => true,
                View::LEVEL_BEFORE_TEMPLATE => true
            ]);
            $this->response->setContentType('application/json', 'UTF-8');
            $data = $dispatcher->getReturnedValue();
            if (is_array($data)) {
                $data['status'] = isset($data['status'])  ? $data['status'] : 1;
                $data['info'] = isset($data['info']) ? $data['info'] : '';
                $data = json_encode($data);
            }
            $this->response->setContent($data);
        }
        return $this->response->send();
    }


    /**
     * 注册wechat-js权限
     */
    protected function regWechatJs()
    {
        $storeInfo = $this->getStoreInfo();
        $wechatConfig = isset($storeInfo['config']['wechat']) ? $storeInfo['config']['wechat'] : [];
        if (empty($wechatConfig)) {
            throw new \Exception("微信后端没有配置，联系管理员！");
        }
        $host = $this->request->getHttpHost();
        $params = $this->request->get();
        unset($params['_url']);
        $params_str = http_build_query($params);
        $params = [
            'url'           => 'http://' . $host  . $this->request->get('_url') . "?" . $params_str,
            'jsapi_ticket'  => $this->getJsApiTicket($wechatConfig),
            'noncestr'      => $this->createNoncestr(),
            'timestamp'     => CURRENT_TIME
        ];
        ksort($params);
        $signature = sha1($this->ToUrlParams($params));
        $this->view->setVars([
            'appid'         => $wechatConfig['wechat_appid'],
            'jsapi_ticket'  => $params['jsapi_ticket'],
            'noncestr'      => $params['noncestr'],
            'timestamp'     => $params['timestamp'],
            'signature'     => $signature
        ]);
    }

    /**
     * 获得js凭证
     * @return mixed
     * @throws \Exception
     */
    protected function getJsApiTicket($wechatConfig)
    {
        $cacheKey = "WECHAT_JS_API_TICKET";
        $ticket = $this->cache->get($cacheKey);
        if (!$ticket) {
            $path = "cgi-bin/ticket/getticket";
            $params = [
                'access_token'  => $this->getAccessToken($wechatConfig),
                'type'          => 'jsapi',
            ];
            $api = \Library\Client\WechatClient::init();
            $res = $api->callApi($path, $params);
            $r = json_decode($res["result"], true);
            if (0 != $r['errcode']) {
                throw new \Exception("微信获得js凭证失败，res=" . $res["result"]);
            } else {
                $ticket= $r['ticket'];
                $expiresIn = $r['expires_in'];
                $this->cache->save($cacheKey, $ticket, $expiresIn - 180);
            }
        }
        return $ticket;
    }

    /**
     * 获得微信js页面接口凭证
     * @return bool|mixed
     */
    protected function getAccessToken($wechatConfig)
    {
        $cacheKey = "WECHAT_ACCESS_TOKEN_" . $this->store_id;
        $accessToken = $this->cache->get($cacheKey);
        if (!$accessToken) {
            $path = "cgi-bin/token";
            $params = [
                'grant_type'    => "client_credential",
                'appid'         => $wechatConfig['wechat_appid'],
                'secret'        => $wechatConfig['wechat_secret']
            ];
            $api = \Library\Client\WechatClient::init();
            $res = $api->callApi($path, $params);
            $r = json_decode($res["result"], true);
            if(isset($r['errcode'])) {
                throw new \Exception("微信获得ACCESS_TOKEN失败，res=" . $res["result"]);
            }else{
                $accessToken = $r['access_token'];
                $expiresIn = $r['expires_in'];
                $this->cache->save($cacheKey, $accessToken, $expiresIn-180);
            }
            return $accessToken;
        }
        return $accessToken;
    }

    /**
     * @param $values
     * @return string
     */
    protected  function ToUrlParams($values)
    {
        $buff = "";
        foreach ($values as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 生成随机数
     * @return string
     */
    protected  function createNoncestr()
    {
        $random = new \Phalcon\Security\Random();
        return $random->base64(16);
    }
}