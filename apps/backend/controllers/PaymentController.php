<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2016/11/11
 * Time: 14:14
 */

namespace Backend\Controllers;


class PaymentController extends BaseController
{

    /**
     * 微信支付配置
     */
    public function wechatAction()
    {
        if ($this->request->isPost()) {
            $this->httpClient->setApiName("setting/setPaymentSetting.json");
            $mchid = $this->request->getPost('mchid');
            $key = $this->request->getPost('key');
            $status = (int)$this->request->getPost('status');
            $authorization = (int)$this->request->getPost('authorization');
            $is_https = (int)$this->request->getPost('is_https');
            $data = [
                'mchid'         => $mchid,
                'key'           => $key,
                'status'        => $status,
                'authorization' => $authorization,
                'is_https'      => $is_https,
            ];
            $this->httpClient->setParameters([
                'type'  => 'wechat',
                'data'  => $data
            ]);
            $result = $this->httpClient->request("POST");

            $code = 0;
            $message = "配置成功！";
            if (!$result || 0 !== $result['code']) {
                $code = 1;
                $message = !$result ? "接口网络错误，联系管理员！" : $result['message'];
            }
            return $this->redirect('backend/payment/wechat', $message, $code);
        }

        $this->httpClient->setApiName("setting/getPaymentSetting.json");
        $this->httpClient->setParameters([
            'type' => 'wechat'
        ]);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 !== $result['code']) {
            throw new \Exception(!$result ? "接口网络错误，联系管理员！" : $result['message']);
        }
        $this->view->setVars($result['data']['config']);
    }

    /**
     * 支付宝支付配置
     */
    public function alipayAction()
    {
        if ($this->request->isPost()) {
            $this->httpClient->setApiName("setting/setPaymentSetting.json");
            $partner= $this->request->getPost('partner');
            $seller_id = $this->request->getPost('seller_id');
            $private_key_path = $this->request->getPost('private_key_path');
            $ali_public_key_path = $this->request->getPost('ali_public_key_path');
            $sign_type = $this->request->getPost('sign_type');
            $data = [
                'partner'               => $partner,
                'seller_id'             => $seller_id,
                'private_key_path'      => $private_key_path,
                'ali_public_key_path'   => $ali_public_key_path,
                'sign_type'             => $sign_type
            ];
            $this->httpClient->setParameters([
                'type'  => 'alipay',
                'data'  => $data
            ]);
            $result = $this->httpClient->request("POST");
            $code = 0;
            $message = "配置成功！";
            if (!$result || 0 !== $result['code']) {
                $code = 1;
                $message = !$result ? "接口网络错误，联系管理员！" : $result['message'];
            }
            return $this->redirect('backend/payment/alipay', $message, $code);
        }

        $this->httpClient->setApiName("setting/getPaymentSetting.json");
        $this->httpClient->setParameters([
            'type' => 'alipay'
        ]);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 !== $result['code']) {
            throw new \Exception(!$result ? "接口网络错误，联系管理员！" : $result['message']);
        }
        $this->view->setVars($result['data']['config']);
    }
}