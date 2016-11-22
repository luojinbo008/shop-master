<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2016/8/25
 * Time: 15:58
 */

namespace Backend\Controllers;

class OrderController extends BaseController
{
    /**
     * 获得订单列表
     * @return array
     */
    public function indexAction()
    {
        if ($this->request->isAjax() === true) {
            $query = $this->request->get();
            $filter_order_id = isset($query['filter_order_id']) && !empty($query['filter_order_id']) ? $query['filter_order_id'] : null;
            $filter_customer = isset($query['filter_customer']) && !empty($query['filter_customer']) ? $query['filter_customer'] : null;
            $filter_order_status = isset($query['filter_order_status']) ? $query['filter_order_status'] : null;
            $filter_total = isset($query['filter_total']) && !empty($query['filter_total']) ? $query['filter_total'] : null;
            $filter_date_added = isset($query['filter_date_added']) && !empty($query['filter_date_added']) ? $query['filter_date_added'] : null;
            $filter_date_modified = isset($query['filter_date_modified']) && !empty($query['filter_date_modified']) ? $query['filter_date_modified'] : null;
            $iDisplayStart = $this->request->get("iDisplayStart");
            $iDisplayLength = $this->request->get("iDisplayLength");
            $filter_data = [
                'filter_order_id'       => $filter_order_id,
                'filter_customer'	    => $filter_customer,
                'filter_order_status'   => $filter_order_status,
                'filter_total'          => $filter_total,
                'filter_date_added'     => $filter_date_added,
                'filter_date_modified'  => $filter_date_modified,
                'start'                 => $iDisplayStart,
                'limit'                 => $iDisplayLength
            ];
            $this->httpClient->setApiName("order/getOrderList.json");
            $this->httpClient->setParameters($filter_data);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                return ["iTotalRecords"=> 0, "iTotalDisplayRecords"=> 0, 'aaData' => []];
            }
            $orders = [];
            foreach ($result['data']['list'] as $info) {
                $orders[] = [
                    'order_id'          => $info['order_id'],
                    'customer'          => $info['customer'],
                    'order_status_id'   => $info['order_status_id'],
                    'total'             => '￥' . $info['total'],
                    'date_added'        => date('Y-m-d', strtotime($info['date_added'])),
                    'date_modified'     => date('Y-m-d', strtotime($info['date_modified'])),
                ];
            }
            return ["iTotalRecords"=> $result['data']['count'], "iTotalDisplayRecords"=> $result['data']['count'], "aaData" => $orders];
        }
    }

    /**
     * 查看订单 基本信息
     */
    public function viewAction()
    {
        $query = $this->request->get();
        $this->httpClient->setApiName("order/getOrderInfo.json");
        $this->httpClient->setParameters([
            'order_id'  => (int)$query['order_id']
        ]);
        $result = $this->httpClient->request("GET");
        $order_info = $result['data']['info'];
        $order_info['reward'] = 0;
        foreach ($order_info['product_list'] as $product) {
            $order_info['reward'] += $product['reward'];
        }
        if(in_array($order_info['order_status_id'], [ORDER_STATUS_START, ORDER_STATUS_PAY,
            ORDER_STATUS_CANCEL, ORDER_STATUS_CLOSE])) {
            $order_info['payment'] = '支付中';
            switch ($order_info['order_status_id']) {
                case ORDER_STATUS_START :
                case ORDER_STATUS_PAY :
                    $order_info['payment'] = '支付中';
                    break;
                case ORDER_STATUS_CANCEL :
                    $order_info['payment'] = '用户主动取消';
                    break;
                case ORDER_STATUS_CLOSE :
                    $order_info['payment'] = '订单支付超时，系统关闭订单';
                    break;
                default :
                    $order_info['payment'] = '未知';
                    break;
            }
            $order_info['payment'] = '支付中';
        } else {
            if ($order_info['payment'] == 'wxpay') {
                $order_info['payment'] = '微信支付';
            } else if ($order_info['payment'] == 'alipay') {
                $order_info['payment'] = '支付宝支付';
            } else {
                $order_info['payment'] = '未知支付方式';
            }
            if(in_array($order_info['order_status_id'], [ORDER_STATUS_REFUND_START, ORDER_STATUS_REFUND_END, ORDER_STATUS_REFUND_FAIL])) {
                switch ($order_info['order_status_id']) {
                    case ORDER_STATUS_REFUND_START :
                        $order_info['payment'] .= ',玩家发起退款';
                        break;
                    case ORDER_STATUS_REFUND_END :
                        $order_info['payment'] .= ',玩家发起退款,并退审核成功';
                        break;
                    case ORDER_STATUS_REFUND_FAIL:
                        $order_info['payment'] .= ',玩家发起退款,并退审核失败';
                        break;
                    default :
                        $order_info['payment'] .= ',未知操作';
                        break;
                }
            }
        }
        $this->view->setVars([
            'orderInfo'         => $order_info,
        ]);
    }

    /**
     * 订单操作日志
     * @return array
     */
    public function getOrderHistoriesAction()
    {
        $iDisplayStart = $this->request->get("iDisplayStart");
        $iDisplayLength = $this->request->get("iDisplayLength");
        $order_id = $this->request->get("order_id");
        $this->httpClient->setApiName("order/getOrderHistories.json");
        $this->httpClient->setParameters([
            'order_id'  => (int)$order_id,
            'start'     => (int)$iDisplayStart,
            'limit'     => (int)$iDisplayLength,
        ]);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            return ["iTotalRecords"=> 0, "iTotalDisplayRecords"=> 0, 'aaData' => []];
        }
        return ["iTotalRecords"=> $result['data']['count'], "iTotalDisplayRecords"=> $result['data']['count'],
            "aaData" => $result['data']['list']];
    }

    /**
     * 审核用户退款
     * @return array
     * @throws \Exception
     */
    public function refundSubmitAction()
    {
        $order_id = $this->request->getPost("order_id");
        $status =  $this->request->getPost("status");
        $comment =  $this->request->getPost("comment");
        $this->httpClient->setApiName("order/refundSubmitOrder.json");
        $this->httpClient->setParameters([
            'order_id'  => (int)$order_id,
            'status'    => (int)$status,
            'comment'   => $comment,
        ]);
        $result = $this->httpClient->request("POST");
        if (!$result || 0 != $result['code']) {
            $message = !$result ? "服务失败，联系管理员" : $result['message'];
            throw new \Exception($message);
        }
        return ["status" => 0, "info" => "success", "data" => []];
    }
}