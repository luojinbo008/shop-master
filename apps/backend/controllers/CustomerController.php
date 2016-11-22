<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2016/8/22
 * Time: 13:13
 */

namespace Backend\Controllers;


class CustomerController extends BaseController
{
    /**
     * 用户管理首页
     * @return array
     */
    public function indexAction()
    {
        $groups = $this->getCustomerGroups();
        if ($this->request->isAjax() === true) {
            $iDisplayStart = $this->request->get("iDisplayStart");
            $iDisplayLength = $this->request->get("iDisplayLength");
            $request = $this->request->get();
            $filter_name = isset($request['filter_name']) && !empty($request['filter_name']) ? $request['filter_name'] : null;
            $filter_telephone = isset($request['filter_telephone']) && !empty($request['filter_telephone']) ? $request['filter_telephone'] : null;
            $filter_customer_group_id = isset($request['filter_customer_group_id']) && !empty($request['filter_customer_group_id']) ? $request['filter_customer_group_id'] : null;
            $filter_status = isset($request['filter_status']) && !empty($request['filter_status']) ? $request['filter_status'] : 0;
            $filter_date_added = isset($request['filter_date_added']) && !empty($request['filter_date_added']) ? $request['filter_date_added'] : null;
            $filter_ip = isset($request['filter_ip']) && !empty($request['filter_ip']) ? $request['filter_ip'] : null;
            $filter = [
                'start'                     => $iDisplayStart,
                'limit'                     => $iDisplayLength,
                'filter_name'               => $filter_telephone,
                'filter_telephone'          => $filter_name,
                'filter_customer_group_id'  => $filter_customer_group_id,
                'filter_status'             => $filter_status,
                'filter_date_added'         => $filter_date_added,
                'filter_ip'                 => $filter_ip,
            ];
            $this->httpClient->setApiName("customer/getCustomerList.json");
            $this->httpClient->setParameters($filter);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                return ["iTotalRecords"=> 0,"iTotalDisplayRecords"=> 0,'aaData' => []];
            }
            $customers = $result['data']['list'];
            $customers_total = $result['data']['count'];
            return ["iTotalRecords"=> $customers_total,"iTotalDisplayRecords"=> $customers_total,'aaData' => $customers];
        }
        $this->view->setVars([
            'groups' => $groups
        ]);
    }

    /**
     * 查看会员基本信息
     */
    public function viewAction()
    {
        $customer_id = $this->request->get('customer_id');
        $this->httpClient->setApiName("customer/getCustomerInfo.json");
        $this->httpClient->setParameters([
            'customer_id'   => $customer_id
        ]);
        $result = $this->httpClient->request("GET");

        if (!$result || 0 != $result['code']) {
            if (!$result) {
                throw new \Exception("接口网络错误，联系管理员！");
            } else {
                throw new \Exception($result["message"]);
            }
        }
        $customerInfo = $result["data"]["info"];
        $groups = $this->getCustomerGroups();
        $this->view->setVars([
            'groups'        => $groups,
            'customerInfo'  => $customerInfo
        ]);
    }

    /**
     * 编辑用户
     * @return array
     */
    public function editAction()
    {
        $customer_id = $this->request->get('customer_id');
        $status = (int)$this->request->get('status');
        $customer_group_id = $this->request->get('customer_group_id');
        $this->httpClient->setApiName("customer/updateCustomerInfo.json");
        $this->httpClient->setParameters([
            'customer_id'       => $customer_id,
            'status'            => $status,
            'customer_group_id' => $customer_group_id
        ]);
        $result = $this->httpClient->request("POST");
        if (!$result || 0 != $result['code']) {
            if (!$result) {
                $error_warning = "接口网络错误，联系管理员！";
            } else {
                $error_warning = $result["message"];
            }
            $this->layoutMessage($error_warning, 1);
        } else {
            $this->layoutMessage("成功编辑！", 0);
        }
        $url = $this->di->get('url');
        $this->response->redirect($url->get(['for' => "backend/customer/view"]) . "?customer_id=" . $customer_id)->sendHeaders();
    }
}