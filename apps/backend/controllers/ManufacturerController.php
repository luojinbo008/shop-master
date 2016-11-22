<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2016/9/26
 * Time: 10:03
 */

namespace Backend\Controllers;
use Library\Util\Util;

class ManufacturerController extends BaseController
{
    /**
     * 获得品牌，生产商列表
     * @return array
     */
    public function indexAction()
    {
        if ($this->request->isAjax() === true) {
            $iDisplayStart = $this->request->get("iDisplayStart");
            $iDisplayLength = $this->request->get("iDisplayLength");
            $this->httpClient->setApiName("product/getManufacturerList.json");
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
     * 新增品牌，生产商列表
     */
    public function addAction()
    {
        $data = $this->request->getPost();
        $thumb = $thumb_no_image = Util::resizeImage("no_image.png", 100, 100);
        if ($data) {
            $this->httpClient->setApiName("product/addManufacturer.json");
            $this->httpClient->setParameters($data);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    $error_warning = "接口网络错误，联系管理员！";
                } else {
                    $error_warning = $result["message"];
                }
                if (!empty($data['image'])) {
                    $thumb = Util::resizeImage($data['image'], 100, 100);
                }
                $this->view->setVars($data);
                $this->layoutMessage($error_warning, 1);
            } else {
                return $this->response->redirect([
                    'for' => 'backend/manufacturer'
                ]);
            }
        }
        $stores = $this->getStores();
        $this->view->setVars([
            'thumb'                 => $thumb,
            'manufacturer_store'    => [],
            'stores'                => $stores,
            'placeholder'           => $thumb_no_image,
        ]);
    }

    /**
     * 编辑品牌，生产商列表
     */
    public function editAction()
    {
        $thumb = $thumb_no_image = Util::resizeImage("no_image.png", 100, 100);
        $data = $this->request->getPost();
        if ($data) {
            $manufacturer_id = (int)$data['manufacturer_id'];
            $this->httpClient->setApiName("product/updateManufacturer.json");
            $this->httpClient->setParameters($data);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    $error_warning = "接口网络错误，联系管理员！";
                } else {
                    $error_warning = $result["message"];
                }
                if (!empty($data['image'])) {
                    $thumb = Util::resizeImage($data['image'], 100, 100);
                }
                $this->view->setVars($data);
                $this->layoutMessage($error_warning, 1);
            } else {
                return $this->response->redirect([
                    'for' => 'backend/manufacturer'
                ]);
            }
        } else {
            $manufacturer_id = (int)$this->request->get('manufacturer_id');
            $this->httpClient->setApiName("product/getManufacturerInfo.json");
            $this->httpClient->setParameters([
                'manufacturer_id'    => $manufacturer_id
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    throw new \Exception("接口网络错误，联系管理员！");
                } else {
                    throw new \Exception($result["message"]);
                }
            }
            $data = $result['data']['info'];
        }
        if ($data['image']) {
            $thumb = Util::resizeImage($data['image'], 100, 100);
        }
        $this->view->setVars($data);
        $stores = $this->getStores();
        $this->view->setVars([
            'manufacturer_id'   => $manufacturer_id,
            'stores'            => $stores,
            'placeholder'       => $thumb_no_image,
            'thumb'             => $thumb,
        ]);

    }

    /**
     * 删除品牌，生产商列表
     */
    public function deleteAction()
    {
        $data = $this->request->get();
        if (isset($data['selected'])) {
            $this->httpClient->setApiName("product/deleteManufacturer.json");
            $this->httpClient->setParameters([
                'manufacturer_ids'    => $data['selected'],
            ]);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    $message = "接口网络错误，联系管理员！";
                } else {
                    $message = $result["message"];
                }
                return ["status" => 1, "info" => $message, "data" => []];
            }
        }
        return ["status" => 0, "info" => "删除成功！", "data" => []];
    }

    /**
     * 筛选品牌，生产商列表
     * @return array
     */
    public function autoCompleteAction()
    {
        $data = $this->request->get();
        if (isset($data['filter_name'])) {
            if (isset($data['filter_name'])) {
                $filter_name = $data['filter_name'];
            } else {
                $filter_name = '';
            }
            if (isset($data['limit'])) {
                $limit = $data['limit'];
            } else {
                $limit = 5;
            }
            $filter_data = [
                'filter_name' => $filter_name,
                'start' => 0,
                'limit' => $limit
            ];
            $this->httpClient->setApiName("product/getManufacturerList.json");
            $this->httpClient->setParameters($filter_data);
            $result = $this->httpClient->request("GET");
            return ["status" => 0, "info" => "success", "data" => $result['data']['list']];
        }
    }
}