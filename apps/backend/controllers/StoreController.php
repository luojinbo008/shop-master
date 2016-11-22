<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2016/8/26
 * Time: 17:52
 */

namespace Backend\Controllers;
use Library\Util\Util;

class StoreController extends BaseController
{
    /**
     * 商店列表
     */
    public function indexAction()
    {
        if ($this->request->isAjax() === true) {
            $iDisplayStart = $this->request->get("iDisplayStart");
            $iDisplayLength = $this->request->get("iDisplayLength");
            $this->httpClient->setApiName("setting/getStoreList.json");
            $this->httpClient->setParameters([
                'start' => $iDisplayStart,
                'limit' => $iDisplayLength,
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']){
                return ["iTotalRecords"=> 0, "iTotalDisplayRecords"=> 0, 'aaData' => []];
            }
            return ["iTotalRecords"=> $result['data']['count'], "iTotalDisplayRecords"=> $result['data']['count'],
                'aaData' => $result['data']['list']];
        }
    }

    /**
     * 新增商店
     */
    public function addAction()
    {
        $thumb = $thumb_no_image = Util::resizeImage('no_image.png', 100, 100);
        $data = $this->request->getPost();
        if (!empty($data)) {
            $params = [
                'store_url'          => $data['store_url'],
                'meta_title'         => $data['meta_title'],
                'meta_description'   => $data['meta_description'],
                'meta_keyword'       => $data['meta_keyword'],
                'name'               => $data['name'],
                'store_type'         => $data['store_type'],
                'image'              => $data['image'],
                'comment'            => $data['comment'],
                'customer_group_id'  => (int)$data['customer_group_id'],
                'stock_display'      => (int)$data['stock_display'],
            ];
            if (isset($data['advert_image'])) {
                $params['advert_image'] = $data['advert_image'];
            }
            $this->httpClient->setApiName("setting/addStore.json");
            $this->httpClient->setParameters($params);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    $error_warning = "接口网络错误，联系管理员！";
                } else {
                    $error_warning = $result["message"];
                    $this->view->setVars($result['data']);
                }
                $this->layoutMessage($error_warning, 1);
                if (!empty($data['image'])) {
                    $thumb = Util::resizeImage($data['image'], 100, 100);
                }
                $this->view->setVars($data);
            } else {
                return $this->response->redirect([
                    'for' => 'backend/store'
                ]);
            }
        }
        $customer_groups = $this->getCustomerGroups();
        $this->view->setVars([
            'customer_groups'   => $customer_groups,
            'placeholder'       => $thumb_no_image,
            'thumb'             => $thumb
        ]);
    }

    /**
     * 编辑商店信息
     */
    public function editAction()
    {
        $thumb = $thumb_no_image = Util::resizeImage('no_image.png', 100, 100);
        $data = $this->request->getPost();
        if (!empty($data)) {
            $this->httpClient->setApiName("setting/uploadStore.json");
            $params = [
                'store_id'           => (int)$data['store_id'],
                'store_url'          => $data['store_url'],
                'meta_title'         => $data['meta_title'],
                'meta_description'   => $data['meta_description'],
                'meta_keyword'       => $data['meta_keyword'],
                'name'               => $data['name'],
                'store_type'         => $data['store_type'],
                'image'              => $data['image'],
                'comment'            => $data['comment'],
                'customer_group_id'  => (int)$data['customer_group_id'],
                'stock_display'      => (int)$data['stock_display'],
            ];
            if (isset($data['advert_image'])) {
                $params['advert_image'] = $data['advert_image'];
            }
            $this->httpClient->setParameters($params);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    $error_warning = "接口网络错误，联系管理员！";
                } else {
                    $error_warning = $result["message"];
                    $this->view->setVars($result['data']);
                }
                $this->view->setVars($data);
                $this->layoutMessage($error_warning, 1);
            } else {
                return $this->response->redirect([
                    'for' => 'backend/store'
                ]);
            }
        } else {
            $this->httpClient->setApiName("setting/getStoreInfo.json");
            $this->httpClient->setParameters([
                'type'      => 'id',
                'value'     => (int)$this->request->get('store_id')
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                throw new \Exception("获得商店基本信息失败！");
            }
            foreach ($result['data']['info']['advert_image'] as &$row) {
                if ($row['image']) {
                    $row['thumb'] = Util::resizeImage($row['image'], 100, 100);
                } else {
                    $row['thumb'] = $thumb_no_image;
                }
            }
            unset($row);
            $data = $result['data']['info'];
        }
        if (!empty($data['image'])) {
            $thumb = Util::resizeImage($data['image'], 100, 100);
        }
        $customer_groups = $this->getCustomerGroups();
        $this->view->setVars($data);
        $this->view->setVars([
            'customer_groups'   => $customer_groups,
            'placeholder'       => $thumb_no_image,
            'thumb'             => $thumb
        ]);
    }

    /**
     * 删除商店
     */
    public function deleteAction()
    {
        $data = $this->request->getPost();
        if (isset($data['selected'])) {
            $this->httpClient->setApiName("setting/deleteStore.json");
            $this->httpClient->setParameters([
                'store_ids' => $data['selected'],
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    $error_warning = "接口网络错误，联系管理员！";
                } else {
                    $error_warning = $result["message"];
                }
                throw new \Exception($error_warning);
            }
            return ["status" => 0, "info" => "删除成功！", "data" => []];
        }
        return ["info" => "删除失败！", "data" => []];
    }

}