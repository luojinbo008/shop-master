<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-5-29
 * Time: 下午10:19
 */

namespace Backend\Controllers;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Library\Util\Util;

class CategoryController extends BaseController
{
    /**
     * 获取列表
     * @return array
     */
    public function indexAction()
    {
        if ($this->request->isAjax() === true) {
            $iDisplayStart = $this->request->get("iDisplayStart");
            $iDisplayLength = $this->request->get("iDisplayLength");
            $this->httpClient->setApiName("product/getCategoryList.json");
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
     * 新增逻辑
     */
    public function addAction()
    {
        $data = $this->request->getPost();
        $thumb = Util::resizeImage('no_image.png', 100, 100);
        if (!empty($data)) {
            $this->httpClient->setApiName("product/addCategory.json");
            $this->httpClient->setParameters([
                'image'             => $data['image'],
                'parent_id'         => (int)$data['parent_id'],
                'top'               => (int)$data['top'],
                'column'            => (int)$data['column'],
                'sort_order'        => (int)$data['sort_order'],
                'status'            => (int)$data['status'],
                'name'              => $data['name'],
                'description'       => $data['description'],
                'meta_title'        => $data['meta_title'],
                'meta_keyword'      => $data['meta_keyword'],
                'meta_description'  => $data['meta_description'],
                'filter_id'         => $data['category_filter'],
                'store_id'          => $data['category_store'],
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
        if (!empty($data['category_filter'])) {
            $this->httpClient->setApiName("product/getFilterList.json");
            $this->httpClient->setParameters([
                'filter_ids'    => $data['category_filter'],
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    throw new \Exception("接口网络错误，联系管理员！");
                } else {
                    throw new \Exception($result["message"]);
                }
            }
            $filters = $result['data']['list'];
        }
        $categoryFilters = [];
        foreach ($filters as $filter_info) {
            if ($filter_info) {
                $categoryFilters[] = [
                    'filter_id' => $filter_info['filter_id'],
                    'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
                ];
            }
        }
        $this->view->setVars([
            "category_filters"  => $categoryFilters,
            'stores'            => $stores,
            'thumb'             => $thumb
        ]);
    }

    /**
     * 编辑逻辑
     */
    public function editAction()
    {
        $data = $this->request->getPost();
        if ($data) {
            $this->httpClient->setApiName("product/editCategory.json");
            $this->httpClient->setParameters([
                'category_id'       => (int)$data['category_id'],
                'image'             => $data['image'],
                'parent_id'         => (int)$data['parent_id'],
                'top'               => (int)$data['top'],
                'column'            => (int)$data['column'],
                'sort_order'        => (int)$data['sort_order'],
                'status'            => (int)$data['status'],
                'name'              => $data['name'],
                'description'       => $data['description'],
                'meta_title'        => $data['meta_title'],
                'meta_keyword'      => $data['meta_keyword'],
                'meta_description'  => $data['meta_description'],
                'filter_id'         => $data['category_filter'],
                'store_id'          => $data['category_store'],
            ]);
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
                    'for' => 'backend/category'
                ]);
            }
        } else {
            $category_id = $this->request->get('category_id');
            $this->httpClient->setApiName("product/getCategory.json");
            $this->httpClient->setParameters([
                'get_filter'    => 1,
                'category_id'   => $category_id,
                'get_store'     => 1,
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    throw new \Exception("接口网络错误，联系管理员！");
                } else {
                    throw new \Exception($result["message"]);
                }
            }
            $categoryInfo = $result['data']['info'];
            $image = "";
            if (!empty($categoryInfo) && !empty($categoryInfo['image'])) {
                $thumb = Util::resizeImage($categoryInfo['image'], 100, 100);
                $image = $categoryInfo['image'];
            } else {
                $thumb = Util::resizeImage('no_image.png', 100, 100);
            }
            $this->view->setVars([
               "name" => $categoryInfo['name'],
               "description" => $categoryInfo['description'],
               "meta_title" => $categoryInfo['meta_title'],
               "meta_description" => $categoryInfo['meta_description'],
               "meta_keyword" => $categoryInfo['meta_keyword'],
               "parent_id" => $categoryInfo['parent_id'],
               "path" => $categoryInfo['path'],
               "category_store" => [],
               "keyword" => $categoryInfo['keyword'],
               "thumb" => $thumb,
               "image" => $image,
               "top" => $categoryInfo['top'],
               "column" => $categoryInfo['column'],
               "sort_order" => $categoryInfo['sort_order'],
               "status" => $categoryInfo['status'],
            ]);
            $filters = $categoryInfo['filter_ids'];
            $category_store = $categoryInfo['store_ids'];
            $this->view->setVars([
                'category_store' => $category_store
            ]);

        }
        if (!empty($filters)) {
            $this->httpClient->setApiName("product/getFilterList.json");
            $this->httpClient->setParameters([
                'filter_ids'    => $filters,
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    throw new \Exception("接口网络错误，联系管理员！");
                } else {
                    throw new \Exception($result["message"]);
                }
            }
            $filters = $result['data']['list'];
        }
        $categoryFilters = [];
        foreach ($filters as $filter_info) {
          if ($filter_info) {
              $categoryFilters[] = [
                  'filter_id' => $filter_info['filter_id'],
                  'name'      => $filter_info['group'] . ' &gt; ' . $filter_info['name']
              ];
          }
        }
        $stores = $this->getStores();
        $placeholder = Util::resizeImage('no_image.png', 100, 100);
        $this->view->setVars([
            'category_id' => $this->request->get('category_id'),
            "category_filters" => $categoryFilters,
            "stores" => $stores,
            "placeholder" => $placeholder,
        ]);
    }

    /**
     * 删除分类
     */
    public function deleteAction()
    {
        $data = $this->request->get();
        if (isset($data['selected'])) {
            $this->httpClient->setApiName("product/deleteCategory.json");
            $this->httpClient->setParameters([
                'category_ids' => $data['selected'],
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

    /**
     * 重构分类
     */
    public function repairAction()
    {
        $this->httpClient->setApiName("product/repairCategory.json");
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            if (!$result) {
                throw new \Exception("接口网络错误，联系管理员！");
            } else {
                throw new \Exception($result["message"]);
            }
        }
        return $this->response->redirect([
            'for' => 'backend/category'
        ]);
   	}

    /**
     * 获得分类列表
     * @return array
     * @throws \Exception
     */
    public function autoCompleteAction()
    {
        $json = [];
        $data = $this->request->get();
        if (isset($data['filter_name'])) {
            $this->httpClient->setApiName("product/getCategoryList.json");
            $this->httpClient->setParameters([
                'filter_name' => $data['filter_name'],
                'sort'        => 'name',
                'order'       => 'ASC',
                'start'       => 0,
                'limit'       => 5
            ]);
            $result = $this->httpClient->request('GET');
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    throw new \Exception("接口网络错误，联系管理员！");
                } else {
                    throw new \Exception($result["message"]);
                }
            }
            foreach ($result['data']['list'] as $result) {
                $json[] = [
                    'category_id' => $result['category_id'],
                    'name'        => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
                ];
            }
        }
        $sort_order = [];
        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }
        array_multisort($sort_order, SORT_ASC, $json);
        return ["status" => 0, "info" => "success", "data" => $json];
    }
}