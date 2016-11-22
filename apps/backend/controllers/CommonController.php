<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 16-6-3
 * Time: 下午5:58
 */

namespace Backend\Controllers;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Library\Models\User\UserModel;
use Library\Models\User\UserGroupModel;
use Library\Util\Util;
use Library\Util\Pagination;

class CommonController extends BaseController
{
    /**
     * 重构方法
     * @param Dispatcher $dispatcher
     * @return mixed
     */
    protected function afterExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->request->isAjax() === true && 'fileManager' != $dispatcher->getActionName()) {
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
     * 仪盘表
     */
    public function ashboardAction()
    {
        // 订单数量
        $this->httpClient->setApiName('order/getOrderStatistics.json');
        $result_order_total = $this->httpClient->request("GET");
        if (!$result_order_total || 0 != $result_order_total['code']) {
            $order_total = ['today_count' => 0, 'yesterday_count' => 0, 'all_total' => 0];
        } else {
            $order_total = $result_order_total['data'];
        }
        $difference = $order_total['today_count'] - $order_total['yesterday_count'] ;
        if ($difference && $order_total['today_count']) {
            $data['order_percentage'] = round(($difference / $order_total['today_count']) * 100);
        } else {
            $data['order_percentage'] = 0;
        }
        if ($order_total['all_total'] > 1000000000000) {
            $data['order_total'] = round($order_total['all_total']  / 1000000000000, 1) . 'T';
        } elseif ($order_total['all_total'] > 1000000000) {
            $data['order_total'] = round($order_total['all_total']  / 1000000000, 1) . 'B';
        } elseif ($order_total['all_total'] > 1000000) {
            $data['order_total'] = round($order_total['all_total']  / 1000000, 1) . 'M';
        } elseif ($order_total['all_total'] > 1000) {
            $data['order_total'] = round($order_total['all_total']  / 1000, 1) . 'K';
        } else {
            $data['order_total'] = $order_total['all_total'] ;
        }

        // 销售额
        $difference = $order_total['today_sale'] - $order_total['yesterday_sale'];
        if ($difference && (int)$order_total['today_sale']) {
            $data['sale_percentage'] = round(($difference / $order_total['today_sale']) * 100);
        } else {
            $data['sale_percentage'] = 0;
        }
        if ($order_total['all_sale'] > 1000000000000) {
            $data['sale_total'] = round($order_total['all_sale'] / 1000000000000, 1) . 'T';
        } elseif ($order_total['all_sale'] > 1000000000) {
            $data['sale_total'] = round($order_total['all_sale'] / 1000000000, 1) . 'B';
        } elseif ($order_total['all_sale'] > 1000000) {
            $data['sale_total'] = round($order_total['all_sale'] / 1000000, 1) . 'M';
        } elseif ($order_total['all_sale'] > 1000) {
            $data['sale_total'] = round($order_total['all_sale'] / 1000, 1) . 'K';
        } else {
            $data['sale_total'] = round($order_total['all_sale']);
        }

        // 用户数量
        $this->httpClient->setApiName("customer/getCustomerStatistics.json");
        $result_order_total = $this->httpClient->request("GET");
        if (!$result_order_total || 0 != $result_order_total['code']) {
            $customer_total = ['today_count' => 0, 'yesterday_count' => 0, 'all_total' => 0];
        } else {
            $customer_total = $result_order_total['data'];
        }

        $difference = $customer_total['today_count'] - $customer_total['yesterday_count'];
        if ($difference && $customer_total['today_count']) {
            $data['customer_percentage'] = round(($difference / $customer_total['today_count']) * 100);
        } else {
            $data['customer_percentage'] = 0;
        }

        if ($customer_total['all_total'] > 1000000000000) {
            $data['customer_total'] = round($customer_total['all_total'] / 1000000000000, 1) . 'T';
        } elseif ($customer_total['all_total'] > 1000000000) {
            $data['customer_total'] = round($customer_total['all_total'] / 1000000000, 1) . 'B';
        } elseif ($customer_total['all_total'] > 1000000) {
            $data['customer_total'] = round($customer_total['all_total'] / 1000000, 1) . 'M';
        } elseif ($customer_total['all_total'] > 1000) {
            $data['customer_total'] = round($customer_total['all_total'] / 1000, 1) . 'K';
        } else {
            $data['customer_total'] = $customer_total['all_total'];
        }

        // 在线人数
        if ($customer_total['online_total'] > 1000000000000) {
            $data['online_total'] = round($customer_total['online_total']  / 1000000000000, 1) . 'T';
        } elseif ($customer_total['online_total']  > 1000000000) {
            $data['online_total'] = round($customer_total['online_total']  / 1000000000, 1) . 'B';
        } elseif ($customer_total['online_total']  > 1000000) {
            $data['online_total'] = round($customer_total['online_total']  / 1000000, 1) . 'M';
        } elseif ($customer_total['online_total']  > 1000) {
            $data['online_total'] = round($customer_total['online_total']  / 1000, 1) . 'K';
        } else {
            $data['online_total'] = $customer_total['online_total'];
        }

        // 订单列表
        $data['orders'] = [];
        $filter_data = [
            'sort'  => 'o.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => 10
        ];
        $this->httpClient->setApiName("order/getOrderList.json");
        $this->httpClient->setParameters($filter_data);
        $result_order_list = $this->httpClient->request("GET");
        if (!$result_order_list || 0 != $result_order_list['code']) {
            $order_list = [];
        } else {
            $order_list = $result_order_list['data']['list'];
        }

        foreach($order_list as &$result){
            switch ($result['order_status_id']) {
                case ORDER_STATUS_START :
                    $result['status_name'] = "新建订单";
                    break;
                case ORDER_STATUS_PAY :
                    $result['status_name'] = "支付中";
                    break;
                case ORDER_STATUS_CANCEL :
                    $result['status_name'] = "用户主动取消";
                    break;
                case ORDER_STATUS_END :
                    $result['status_name'] = "订单结束（支付完成，第三方回调成功）";
                    break;
                case ORDER_STATUS_CLOSE :
                    $result['status_name'] = "订单支付超时，系统关闭订单";
                    break;
                case ORDER_STATUS_COMMENT :
                    $result['status_name'] = "用户已评价";
                    break;
                case ORDER_STATUS_REFUND_START :
                    $result['status_name'] = "玩家发起退款申请";
                    break;
                case ORDER_STATUS_REFUND_END :
                    $result['status_name'] = "管理员审核退款，并退款成功";
                    break;
                default :
                    $result['status_name'] = "未知状态";
            }
        }
        unset($result);
        $data['orders'] = $order_list;
        $this->view->setVars($data);
    }

    /**
     * 图表
     * @return array
     */
    public function chartAction()
    {

        $data['order'] = [];
        $data['customer'] = [];
        $data['xaxis'] = [];
        $data['order']['label'] = "订单";
        $data['customer']['label'] = "会员";

        $query = $this->request->get();
        if (isset($query['range'])) {
            $range = $query['range'];
        } else {
            $range = 'day';
        }
        $this->httpClient->setApiName("customer/getCustomerStatisticsDetail.json");
        $this->httpClient->setParameters([
            'type' => $range
        ]);
        $result_customer = $this->httpClient->request("GET");
        if (!$result_customer || 0 != $result_customer['code']) {
            $data['customer']['data'] = [];
        } else {
            $data['customer']['data'] = $result_customer['data']['list'];
        }
        $this->httpClient->setApiName("order/getOrderStatisticsDetail.json");
        $this->httpClient->setParameters([
            'type' => $range
        ]);
        $result_order = $this->httpClient->request("GET");
        if (!$result_order || 0 != $result_order['code']) {
            $data['order']['data'] = [];
        } else {
            $data['order']['data'] = $result_order['data']['list'];
        }
        switch ($range) {
            default:
            case 'day':
                for ($i = 0; $i < 24; $i++) {
                    $data['xaxis'][] = array($i, $i);
                }
                break;
            case 'week':
                $date_start = strtotime('-' . date('w') . ' days');
                for ($i = 0; $i < 7; $i++) {
                    $date = date('Y-m-d', $date_start + ($i * 86400));
                    $data['xaxis'][] = array(date('w', strtotime($date)), date('D', strtotime($date)));
                }
                break;
            case 'month':
                for ($i = 1; $i <= date('t'); $i++) {
                    $date = date('Y') . '-' . date('m') . '-' . $i;
                    $data['xaxis'][] = array(date('j', strtotime($date)), date('d', strtotime($date)));
                }
                break;
            case 'year':
                for ($i = 1; $i <= 12; $i++) {
                    $data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i)));
                }
                break;
        }
        return ["status" => 0, "info" => "success", "data" => $data];
    }

    /**
     * 订单数量统计
     * @param $filter_date_added
     * @return int
     */
    protected function getTotalOrders($filter_date_added)
    {
        $this->httpClient->setApiName("order/getTotalOrders.json");
        $this->httpClient->setParameters([
            'filter_date_added' => $filter_date_added,
        ]);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            return 0;
        }
        return $result['data']['count'];
    }

    /**
     * 上传文件管理
     * @throws \Exception
     */
    public function fileManagerAction()
    {
        $request = $this->request->get();
        if (isset($request['filter_name'])) {
            $filter_name = $request['filter_name'];
        } else {
            $filter_name = '';
        }
        if (isset($request['directory'])) {
            $directory =  $request['directory'];
        } else {
            $directory = '';
        }
        if (isset($request['page'])) {
            $page = $request['page'];
        } else {
            $page = 1;
        }
        $result = Util::fileManage($this->appid, $filter_name, $directory, $page);
        if (!$result) {
            throw new \Exception("获得文件列表失败！");
        }

        $images = [];
        foreach ($result['images'] as $image) {
            if('directory' === $image['type']) {
                $url = '';
                if (isset($request['target'])) {
                    $url .= '&target=' . $request['target'];
                }
                if (isset($request['thumb'])) {
                    $url .= '&thumb=' . $request['thumb'];
                }
                $image['href'] = $this->url->get(['for' => 'backend/common/fileManager']) .  '?directory='
                    . urlencode($image['path']) . $url;
            } else {
                $image['thumb'] = IMAGE_HTTP . $image['thumb'];
                $image['href'] = IMAGE_HTTP . $image['path'];
            }
            $images[] = $image;
        }

        $url = [];
        if (isset($request['directory'])) {
            $pos = strrpos($request['directory'], '/');
            if ($pos) {
                $url[] = 'directory=' . urlencode(substr($request['directory'], 0, $pos));
            }
        }
        if (isset($request['target'])) {
            $url[] = 'target=' . $request['target'];
        }
        if (isset($request['thumb'])) {
            $url[] = 'thumb=' . $request['thumb'];
        }
        $parent_url =  $this->url->get(['for' => 'backend/common/fileManager']);
        if(!empty($url)){
            $parent_url .= "?" . implode('&', $url);
        }

        $url = [];
        if (isset($request['directory'])) {
            $url[] = 'directory=' . urlencode($request['directory']);
        }
        if (isset($request['target'])) {
            $url[] = 'target=' . $request['target'];
        }
        if (isset($request['thumb'])) {
            $url[] = 'thumb=' . $request['thumb'];
        }
        $refresh_url =  $this->url->get(['for' => 'backend/common/fileManager']);
        if (!empty($url)){
            $refresh_url .= "?" . implode('&', $url);
        }

        $this->view->setVars([
            'directory'     => isset($request['directory']) ? urlencode($request['directory']) : '',
            'filter_name'   => isset($request['filter_name']) ? $request['filter_name'] : '',
            'target'        => isset($request['target']) ? urlencode($request['target']) : '',
            'thumb'         => isset($request['thumb']) ? urlencode($request['thumb']) : '',
            'parent'        => $parent_url,
            'refresh'       => $refresh_url
        ]);

        $pagination = new Pagination();
        $pagination->total = $result['image_total'];
        $pagination->page = $page;
        $pagination->limit = 16;
        $pagination->url = '?'. implode('&', $url) . '&page={page}';

        $this->view->setVars([
            'pagination'    => $pagination->render(),
            'images'    => $images
        ]);
    }

    /**
     * 创建目录
     * @return array
     * @throws \Exception
     */
    public function fileFolderAction()
    {
        $request = $this->request->get();
        $result = Util::createFile($this->appid, $request['folder'], $request['directory']);
        if (!$result) {
            throw new \Exception("创建文件失败！");
        }
        return ["status" => 0, "info" => "success", "data" => $result];
    }

    /**
     * 删除文件
     * @return array
     */
    public function fileDeleteAction()
    {
        $request = $this->request->get();
        if (isset($request['path'])) {
            $paths = $request['path'];
        } else {
            $paths = [];
        }
        $result = Util::delFile($this->appid, $paths);
        if (!$result) {
            throw new \Exception("删除文件失败！");
        }
        return ["status" => 0, "info" => "success", "data" => $result];
    }

    /**
     * 上传文件
     * @return array
     */
    public function fileUploadAction()
    {
        if ($this->request->hasFiles() == true) {
            $request = $this->request->get  ();
            if (isset($request['directory'])) {
                $directory = $request['directory'];
            } else {
                $directory = '';
            }
            $files = $this->request->getUploadedFiles();
            $file = $files[0];
            $result = $this->uploadImage($file, 'catalog', $directory);
            if (!$result) {
                throw new \Exception("上传文件失败！");
            }
        }
        return ["status"=>0, "info"=>"上传文件成功", "data" => $result];
    }

    /**
     * 登陆后台
     */
    public function loginAction()
    {
        $data = $this->request->getPost();
        if($data) {
            $userInfo = UserModel::findFirst("username = '" . $data['username'] . "'
                AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $data['password'] . "')))))
                OR password = '" . md5($data['password']) . "') AND status = '1' AND appid = " . (int)$this->appid);
            if($userInfo) {
                $backend_login_info['user_id'] = $userInfo->user_id;
                $backend_login_info['username']  = $userInfo->username;
                $backend_login_info['user_group_id']  = $userInfo->user_group_id;
                $userGroupInfo = UserGroupModel::findFirst("user_group_id = " . $userInfo->user_group_id);
                $backend_login_info['rolename'] = $userGroupInfo->name;
                $this->session->set('backend_login_info', $backend_login_info);
                $this->response->redirect("backend/common/ashboard")->sendHeaders();
            }else {
                $this->view->setVars([
                    'error_warning' => '用户名或密码错误。'
                ]);
            }
        }
    }

    /**
     * 登出后台
     */
    public function loginOutAction()
    {
        $this->session->destroy();
        $this->response->redirect("backend/common/login")->sendHeaders();
    }

    /**
     * 编辑器上传文件
     * @return array
     */
    public function uploadFileEditorAction()
    {
        $files = $this->request->getUploadedFiles();
        $file = $files[0];
        $res = $this->uploadImage($file, "editor");
        if ($res) {
            return ["status" => 0 , "info" => "success", "data" => ["image" => IMAGE_HTTP . $res]];
        }else {
            return ["info" => $res, "data" => []];
        }
    }
} 
