<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2016/11/23
 * Time: 9:57
 */

namespace Backend\Controllers;
use Library\Util\Util;

class BlogController extends BaseController
{

    /**
     * 分类列表
     */
    public function categoryAction()
    {
        if ($this->request->isAjax() === true) {
            $iDisplayStart = $this->request->get("iDisplayStart");
            $iDisplayLength = $this->request->get("iDisplayLength");
            $this->httpClient->setApiName("content/getBlogCategoryList.json");
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


    public function addCategoryAction()
    {
        $data = $this->request->getPost();
        $thumb = Util::resizeImage('no_image.png', 100, 100);
        if (!empty($data)) {
            $this->httpClient->setApiName("product/addCategory.json");
            $this->httpClient->setParameters([
                'image' => $data['image'],
                'parent_id' => (int)$data['parent_id'],
                'top' => (int)$data['top'],
                'column' => (int)$data['column'],
                'sort_order' => (int)$data['sort_order'],
                'status' => (int)$data['status'],
                'name' => $data['name'],
                'description' => $data['description'],
                'meta_title' => $data['meta_title'],
                'meta_keyword' => $data['meta_keyword'],
                'meta_description' => $data['meta_description'],
                'filter_id' => $data['category_filter'],
                'store_id' => $data['category_store'],
            ]);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    $error_warning = "接口网络错误，联系管理员！";
                } else {
                    $error_warning = $result["message"];
                    $this->view->setVars($result['data']);
                }
                if (!empty($data['image'])) {
                    $thumb = Util::resizeImage($data['image'], 100, 100);
                }
                $this->view->setVars($data);
                $this->layoutMessage($error_warning, 1);
            } else {
                return $this->response->redirect([
                    'for' => 'backend/category'
                ]);
            }
        }

        $stores = $this->getStores();
        $this->view->setVars([
            'stores' => $stores,
            'thumb' => $thumb
        ]);
    }
}