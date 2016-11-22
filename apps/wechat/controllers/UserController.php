<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-6-19
 * Time: 上午4:46
 */

namespace Wechat\Controllers;
use Library\Util\Util;
class UserController extends BaseController
{
    /**
     * 编辑地址信息
     * @return array
     * @throws \Exception
     */
    public function editAddressAction()
    {
        if ($this->request->isPost()) {
            $address_id = $this->request->getPost('address_id');
            $fullname = $this->request->getPost('fullname');
            $idcard = $this->request->getPost('idcard');
            $wechat_user = $this->request->getPost('wechat_user');
            $shipping_telephone = $this->request->getPost('shipping_telephone');
            $this->httpClient->setApiName("customer/updateCustomerAddress.json");
            $params = [
                'address_id'            => $address_id,
                'store_id'              => $this->store_id,
                'customer_id'           => $this->customer_id,
                'fullname'              => $fullname,
                'shipping_telephone'    => $shipping_telephone,
                'custom_field'          => [
                    'idcard'         => $idcard,
                    'wechat_user'    => $wechat_user,
                ]
            ];
            $this->httpClient->setParameters($params);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                $message = !$result ? "编辑地址失败, 联系管理员！" : $result['message'];
                return ["info" => $message, "data" => []];
            }
            return ['status' => 0, 'info' => 'success', 'data' => []];
        }
        $address_id = $this->request->get('address_id');
        $this->httpClient->setApiName("customer/getAddressInfo.json");
        $params = [
            'customer_id'   => (int)$this->customer_id,
            'address_id'    => (int)$address_id
        ];
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            $message = !$result ? "获得地址失败, 联系管理员！" : $result['message'];
            throw new \Exception($message);
        }
        $result['data']['info']['custom_field'] = json_decode($result['data']['info']['custom_field'], true);
        $this->view->setViewsDir(__DIR__ . '/../views/common/');
        $this->view->setVars([
            'info' => $result['data']['info']
        ]);
    }

    /**
     * 删除地址
     * @return array
     */
    public function deleteAddressAction()
    {
        if ($this->request->isPost()) {
            $address_id = $this->request->getPost('address_id');
            $this->httpClient->setApiName("customer/deleteAddress.json");
            $params = [
                'address_id'            => $address_id,
                'store_id'              => $this->store_id,
                'customer_id'           => $this->customer_id
            ];
            $this->httpClient->setParameters($params);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                $message = !$result ? "删除地址失败, 联系管理员！" : $result['message'];
                return ["info" => $message, "data" => []];
            }
            return ['status' => 0, 'info' => 'success', 'data' => []];
        }
    }

    /**
     * 设置默认地址
     * @return array
     */
    public function setAddressDefaultAction()
    {
        if ($this->request->isPost()) {
            $address_id = $this->request->getPost('address_id');
            $this->httpClient->setApiName("customer/setAddressDefault.json");
            $params = [
                'customer_id'   => (int)$this->customer_id,
                'address_id'    => (int)$address_id
            ];
            $this->httpClient->setParameters($params);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                $message = !$result ? "设置默认地址失败, 联系管理员！" : $result['message'];
                return ["info" => $message, "data" => []];
            }
            return ["status" => 0, "info" => "设置成功", "data" => []];
        }
    }

    /**
     * 新增配货地址
     */
    public function addAddressAction()
    {
        if ($this->request->isPost()) {
            $fullname = $this->request->getPost('fullname');
            $idcard = $this->request->getPost('idcard');
            $wechat_user = $this->request->getPost('wechat_user');
            $shipping_telephone = $this->request->getPost('shipping_telephone');
            $this->httpClient->setApiName("customer/addCustomerAddress.json");
            $params = [
                'store_id'              => $this->store_id,
                'customer_id'           => $this->customer_id,
                'fullname'              => $fullname,
                'shipping_telephone'    => $shipping_telephone,
                'custom_field'          => [
                    'idcard'         => $idcard,
                    'wechat_user'    => $wechat_user,
                ]
            ];
            $this->httpClient->setParameters($params);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                $message = !$result ? "新增地址失败, 联系管理员！" : $result['message'];
                return ["info" => $message, "data" => []];
            }
            return ["status" => 0, "info" => "新增成功", "data" => []];
        }
        $this->view->setViewsDir(__DIR__ . '/../views/common/');
    }

    /**
     * 新增配货地址
     */
    public function addressListAction()
    {
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

        foreach ($result['data']['list'] as &$row) {
            $row['shipping_telephone'] = substr($row['shipping_telephone'], 0, 3) . '*****'
                . substr($row['shipping_telephone'], 8, strlen($row['shipping_telephone']));
            $tmp = json_decode($row['custom_field'], true);
            $row['idcard'] = isset($tmp['idcard']) ? (strlen($tmp['idcard']) == 15 ?
                substr_replace($tmp['idcard'], "****", 8, 4) : (strlen($tmp['idcard']) == 18 ?
                    substr_replace($tmp['idcard'], "****",10,4) : "")) : '';
            $row['wechat_user'] = isset($tmp['wechat_user']) ? $tmp['wechat_user'] : '';
        }
        unset($row);
        $this->view->setViewsDir(__DIR__ . '/../views/common/');
        $this->view->setVars([
            "list"      => $result['data']['list'],
            "count"     => $result['data']['count'],
        ]);
    }

    /**
     * 个人中心首页
     * @throws \Exception
     */
    public function indexAction()
    {
        if (!$this->customer_id) {
            $this->response->redirect("wechat/user/register")->sendHeaders();
        }
        $this->httpClient->setApiName("customer/getStoreCustomerPoints.json");
        $params = [
            'customer_id'   => $this->customer_id,
        ];
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            $points = 0;
        } else {
            $points = $result['data']['points'];
        }
        $this->view->setViewsDir(__DIR__ . '/../views/common/');
        $this->view->setVars([
            'points'    => $points,
            "nickname"  => $this->customer_nickname
        ]);
    }

    /**
     * 个人信息管理
     * @throws \Exception
     */
    public function manageAction()
    {
        $type = $this->request->getPost('type');
        if (!$this->customer_id) {
            $this->response->redirect("wechat/user/register")->sendHeaders();
        }

        if ('edit' !== $type) {
            if ($this->customer_telephone) {
                $telephone = substr($this->customer_telephone, 0, 3) . '*****'
                    . substr($this->customer_telephone, 8, strlen($this->customer_telephone));
            }
            if ($this->customer_idcard) {
                $idcard = strlen($this->customer_idcard) == 15 ? substr_replace($this->customer_idcard, "****", 8, 4) :
                    (strlen($this->customer_idcard) == 18 ? substr_replace($this->customer_idcard, "****",10,4) : "");
            }
            $type = "show";
        } else {
            $telephone = $this->customer_telephone;
            $idcard = $this->customer_idcard;
        }
        $this->view->setViewsDir(__DIR__ . '/../views/common/');
        $this->view->setVars([
            "fullname"  => $this->customer_fullname,
            "nickname"  => $this->customer_nickname,
            "telephone" => $telephone,
            "idcard"    => $idcard,
            "type"      => $type
        ]);
    }

    /**
     * 更新用户
     * @return array
     * @throws \Exception
     */
    public function updateAction()
    {
        $openid = $this->session->get($this->store_id . '_wechat_openid');
        if (!$openid) {
            throw new \Exception("请从微信端登陆！");
        }
        $data = $this->request->getPost();
        $error = $this->registerValidate($data);
        if ($error) {
            $this->view->setVars($data);
            $this->view->setVars($error);
        } else {
            $this->httpClient->setApiName("customer/updateCustomerInfo.json");
            $params = [
                'customer_id'       => $this->customer_id,
                'fullname'          => $data['fullname'],
                'nickname'          => $data['nickname'],
                'telephone'         => $data['telephone'],
                'idcard'            => $data['idcard'],
            ];
            $this->httpClient->setParameters($params);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                $message = !$result ? "更新失败, 联系管理员！" : $result['message'];
                return ["info" => $message, "data" => []];
            }
            $this->session->destroy();
            return ["status" => 0, "info" => "更新成功！", "data" => []];
        }
    }

    /**
     * 注册页面
     * @return array
     * @throws \Exception
     */
    public function registerAction()
    {
        $openid = $this->session->get($this->store_id . '_wechat_openid');
        if (!$openid) {
            throw new \Exception("请从微信端登陆！");
        }
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $error = $this->registerValidate($data);
            if ($error) {
                $this->view->setVars($data);
                $this->view->setVars($error);
            } else {
                $this->httpClient->setApiName("customer/registerByWechat.json");
                $params = [
                    'store_id'  => $this->store_id,
                    'open_id'   => $openid,
                    'fullname'  => $data['fullname'],
                    'telephone' => $data['telephone'],
                    'nickname'  => $data['nickname'],
                    'ip'        => Util::getClientIp(),
                    'idcard'    => $data['idcard'],
                ];
                $this->httpClient->setParameters($params);
                $result = $this->httpClient->request("POST");
                if (!$result || 0 != $result['code']) {
                    $message = !$result ? "注册失败, 联系管理员！" : $result['message'];
                    return ["info" => $message, "data" => []];
                }
                return ["status" => 0, "info" => "注册成功！", "data" => []];
            }
        }
        $this->view->setViewsDir(__DIR__ . '/../views/common/');
    }

    /**
     * 验证注册数据
     * @throws \Exception
     */
    private function registerValidate($data)
    {
        if (!$data) {
            throw new \Exception("注册数据无法找到！");
        }
        if (!isset($data['fullname']) || (mb_strlen(trim($data['fullname'])) < 2)
            || (mb_strlen(trim($data['fullname'])) > 32)) {
            throw new \Exception("姓名必须为 2-32 字符！");
        }
        if (!isset($data['telephone']) ||
            !preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $data['telephone'])) {
            throw new \Exception("手机号码错误！");
        }
        if (!isset($data['idcard']) ||
            !preg_match('/^(\d{6})(18|19|20)?(\d{2})([01]\d)([0123]\d)(\d{3})(\d|X)?$/',$data['idcard'])) {
            throw new \Exception("身份证号码错误！");
        }
    }
} 