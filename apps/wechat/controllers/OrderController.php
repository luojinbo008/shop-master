<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-6-20
 * Time: 下午11:43
 */

namespace Wechat\Controllers;
use Library\Util\Util;

class OrderController extends BaseController
{
    /**
     * 根据商品直接生成订单
     * @return array
     */
    public function createOrderByProductAction()
    {
        $product_id = $this->request->getPost("product_id");
        $option = $this->request->getPost("option");
        $quantity = $this->request->getPost("quantity");
        if ($product_id) {
            $this->httpClient->setApiName("order/addOrderByProduct.json");
            $params = [
                'store_id'      => $this->store_id,
                'product_id'    => $product_id,
                'customer_id'   => $this->customer_id,
                'quantity'      => (int)$quantity,
                'option'        => (array)$option,
                'ip'            => Util::getClientIp()
            ];
            $this->httpClient->setParameters($params);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                $message = !$result ? "服务失败，联系管理员" : $result['message'];
                throw new \Exception($message);
            }
        }
        return ["status" => 0, "info" => "success", "data" => ["order_id" => $result["data"]["order_id"]]];
    }

    /**
     * 订单详细信息
     * @throws \Exception
     */
    public function detailAction()
    {
        $order_id = $this->request->get('order_id');
        $this->httpClient->setApiName("order/getMyOrderInfo.json");
        $params = [
            'store_id'      => (int)$this->store_id,
            'order_id'      => (int)$order_id,
            'customer_id'   => (int)$this->customer_id,
        ];
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            throw new \Exception("用户获取订单失败");
        }
        $info = $result['data']['info'];
        foreach ($info['product_list'] as &$product) {
            $product['image'] = Util::resizeImage($product['image'], 200, 200);
        }
        unset($product);
        foreach ($info['address_list'] as &$address) {
            $address['shipping_custom_field'] = json_decode($address['shipping_custom_field'], true);
            $address['shipping_telephone'] = substr($address['shipping_telephone'], 0, 3) . '*****'
                . substr($address['shipping_telephone'], 8, strlen($address['shipping_telephone']));
            $address['shipping_custom_field']['idcard'] = isset($address['shipping_custom_field']['idcard'])
                ? (strlen($address['shipping_custom_field']['idcard']) == 15 ?
                    substr_replace($address['shipping_custom_field']['idcard'], "****", 8, 4)
                    : (strlen($address['shipping_custom_field']['idcard']) == 18 ?
                    substr_replace($address['shipping_custom_field']['idcard'], "****",10,4) : "")) : '';
            $address['shipping_custom_field']['wechat_user'] = isset($address['shipping_custom_field']['wechat_user'])
                ? $address['shipping_custom_field']['wechat_user'] : '';
        }
        $this->httpClient->setApiName("customer/getCustomerAddressList.json");
        $params = [
            'customer_id'   => $this->customer_id,
        ];
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            $message = !$result ? "获得地址失败, 联系管理员！" : $result['message'];
            throw new \Exception($message);
        }
        $addressList = $result['data']['list'];
        foreach ($addressList as &$row) {
            $row['shipping_telephone'] = substr($row['shipping_telephone'], 0, 3) . '*****'
                . substr($row['shipping_telephone'], 8, strlen($row['shipping_telephone']));
            $tmp = json_decode($row['custom_field'], true);
            $row['idcard'] = isset($tmp['idcard']) ? (strlen($tmp['idcard']) == 15 ?
                substr_replace($tmp['idcard'], "****", 8, 4) : (strlen($tmp['idcard']) == 18 ?
                    substr_replace($tmp['idcard'], "****",10,4) : "")) : '';
            $row['wechat_user'] = isset($tmp['wechat_user']) ? $tmp['wechat_user'] : '';
        }
        unset($row);
        if (in_array($info['order_status_id'], [ORDER_STATUS_START, ORDER_STATUS_PAY])) {
            $this->regWechatJs();
        }
        $this->view->setViewsDir(__DIR__ . '/../views/common/');
        $this->view->setVars([
            "order_id"      => $order_id,
            "info"          => $info,
            "addressList"   => $addressList
        ]);
    }

    /**
     * 我的订单列表
     * @return array
     */
    public function listAction()
    {
        $orderStatusArr = [
           0 => [ORDER_STATUS_START, ORDER_STATUS_PAY],
           1 => [ORDER_STATUS_END],
           2 => [ORDER_STATUS_REFUND_END, ORDER_STATUS_REFUND_START, ORDER_STATUS_REFUND_FAIL],
        ];
        $type = (int)$this->request->get('type');
        $start = (int)$this->request->get('start');
        if (!in_array($type, [0, 1, 2])) {
            $type = 0;
        }
        $this->httpClient->setApiName("order/getMyOrderList.json");
        $params = [
            'store_id'      => $this->store_id,
            'customer_id'   => $this->customer_id,
            'start'         => $start,
            'status'        => $orderStatusArr[$type],
            'limit'         => 20,
        ];
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            throw new \Exception("获得订单列表失败，联系管理员！");
        }
        foreach ($result['data']['list'] as &$order) {
            foreach ($order['products'] as &$product) {
                $product['image'] = Util::resizeImage($product['image'], 80, 80);
            }
            switch ($order['order_status_id']) {
                case ORDER_STATUS_START:
                case ORDER_STATUS_PAY:
                    $order['status_name'] = "待支付";
                    break;
                case ORDER_STATUS_END:
                    $order['status_name'] = "已支付";
                    break;
                case ORDER_STATUS_REFUND_END:
                    $order['status_name'] = "退款成功";
                    break;
                case ORDER_STATUS_REFUND_START:
                    $order['status_name'] = "退款处理中";
                    break;
                case ORDER_STATUS_REFUND_FAIL:
                    $order['status_name'] = "退款审核失败";
                    break;
                default:
                    $order['status_name'] = "未知订单";
            }
        }
        unset($product);
        $this->view->setViewsDir(__DIR__ . '/../views/common/');
        $this->view->setVars([
            'orderList'     => $result['data']['list'],
            'type'          => $type,
        ]);
    }

    /**
     * 选择订单地址
     * @return array
     */
    public function checkAddressAction()
    {
        $order_id = (int)$this->request->get('order_id');
        $address_id = (int)$this->request->get('address_id');
        $this->httpClient->setApiName("order/checkShippingAddress.json");
        $params = [
            'customer_id'   => $this->customer_id,
            'order_id'      => $order_id,
            'address_id'    => $address_id,
        ];
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("POST");
        if (!$result || 0 != $result['code']) {
            $message = !$result ? "服务失败，联系管理员" : $result['message'];
            return ["status" => 1, "info" => $message, "data" => []];
        }
        return ["status" => 0, "info" => "选择成功！", "data" => []];
    }

    /**
     * 支付下单
     * @return array
     */
    public function checkoutAction()
    {
        $order_id = $this->request->get('order_id');
        $payment = $this->request->get('payment');
        $this->httpClient->setApiName("order/payOrder.json");
        $params = [
            'order_id'      => $order_id,
            'payment'       => $payment,
            'customer_id'   => $this->customer_id,
            'name'          => $this->store_name,
        ];
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("POST");
        if (!$result || 0 != $result['code']) {
            $message = !$result ? "服务失败，联系管理员" : $result['message'];
            throw new \Exception($message);
        }
        if ("jsWechat" == $payment) {
            return ["status" => 0, "info" => "success", "data" => [
                'jsParams' => $result['data']['info']]
            ];
        } else if ("alipayWap" == $payment) {
            $this->view->setVars([
                "html"  => $result['data']['info']
            ]);
        }
    }

    /**
     * 用户申请退款
     */
    public function refundAction()
    {
        $order_id = (int)$this->request->getPost('order_id');
        $comment = $this->request->getPost('comment');
        $this->httpClient->setApiName("order/refundOrder.json");
        $params = [
            'order_id'      => $order_id,
            'customer_id'   => $this->customer_id,
            'comment'       => $comment,
        ];
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("POST");
        if (!$result || 0 != $result['code']) {
            $message = !$result ? "服务失败，联系管理员" : $result['message'];
            throw new \Exception($message);
        }
        return ["status" => 0, "info" => "退货申请成功，请等待商家处理！", "data" => []];
    }
} 