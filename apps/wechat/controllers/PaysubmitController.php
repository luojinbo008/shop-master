<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2016/9/28
 * Time: 14:25
 */

namespace Wechat\Controllers;


class PaysubmitController extends BaseController
{
    /**
     * 微信支付成功回调
     */
    public function wechatSubmitAction()
    {
        $data = $this->request->getRawBody();
        $this->httpClient->setApiName("order/submitOrder.json");
        $this->httpClient->setParameters([
            'payment'   => 'wechat',
            'store_id'  => $this->store_id,
            'data'      => $data
        ]);
        $result = $this->httpClient->request("POST");
        if ($result && 0 == $result['code']) {
            $this->response->appendContent($result['data']['return']);
        }
    }

    /**
     * 支付宝支付回调
     */
    public function alipaySubmitAction()
    {
        $data = $this->request->getPost();
        $this->httpClient->setApiName("order/submitOrder.json");
        $this->httpClient->setParameters([
            'payment'   => 'alipay',
            'store_id'  => $this->store_id,
            'data'      => $data
        ]);
        $result = $this->httpClient->request("POST");
        if ($result && 0 == $result['code']) {
            $this->response->appendContent($result['data']['return']);
        }
    }
}