<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2016/10/29
 * Time: 11:28
 */

namespace Backend\Controllers;

class WechatController extends BaseController
{
    /**
     * 获得
     */
    public function indexAction()
    {

    }

    /**
     * 微信公众号授权
     */
    public function authorizationAction()
    {
        $authCode = $this->request->get("auth_code");
        $params = [];
        if ($authCode) {
            $params['authCodeValue'] = $authCode;
        }
        $this->httpClient->setApiName("wechat/getThirdWechatInfo.json");
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("GET");
        if (!$result) {
            throw new \Exception("接口错误，联系管理员！");
        }
        $status = 1;
        // 没有授权过订单
        if (2 == $result['code']) {
            $result['data']['info'] = [];
            $status = 0;
            $this->httpClient->setApiName("wechat/authorization.json");
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                throw new \Exception("接口错误，联系管理员！");
            }
            $this->view->setVars([
                'url'   => $result['data']['url']
            ]);
        } else if (0 != $result['code']) {
            throw new \Exception($result['message']);
        }
        $this->view->setVars([
            'status'    => $status,
            'info'      => $result['data']['info'],
        ]);
    }

    /**
     * 取消微信授权
     * @return array
     * @throws \Exception
     */
    public function unAuthorizationAction()
    {
        $this->httpClient->setApiName("wechat/unAuthorization.json");
        $result = $this->httpClient->request("POST");
        if (!$result || 0 != $result['code']) {
            $msg = isset($result['message']) ? $result['message'] : "接口错误，联系管理员！";
            throw new \Exception($msg);
        }
        return ['status' => 0, "info" => "success", "data" => []];
    }
}