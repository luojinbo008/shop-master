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
class FilterController extends BaseController
{
    /**
     * 过滤分组列表
     * @return array
     */
    public function indexAction()
    {
        if ($this->request->isAjax() === true) {
            $iDisplayStart = $this->request->get("iDisplayStart");
            $iDisplayLength = $this->request->get("iDisplayLength");
            $this->httpClient->setApiName("product/getGroupList.json");
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
     * 新增分组
     * @return mixed
     */
    public function addAction()
    {
        $data = $this->request->getPost();
        if($data) {
            $this->httpClient->setApiName("product/addFilterGroup.json");
            $this->httpClient->setParameters([
                'sort_order'    => (int)$data['sort_order'],
                'group_name'    => $data['filter_group_name'],
                'filters'       => $data['filters']
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
                    'for' => 'backend/filter'
                ]);
            }
        }
    }

    /**
     * 编辑分类
     * @return mixed
     */
    public function editAction()
    {
        $data = $this->request->getPost();
        if ($data) {
            $this->httpClient->setApiName("product/editFilterGroup.json");
            $this->httpClient->setParameters([
               'group_id'      => (int)$data['filter_group_id'],
               'sort_order'    => (int)$data['sort_order'],
               'group_name'    => $data['filter_group_name'],
               'filters'       => $data['filters']
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
                    'for' => 'backend/filter'
                ]);
            }
        } else {
            $filter_group_id = (int)$this->request->get("filter_group_id");
            $this->httpClient->setApiName("product/getGroupInfo.json");
            $this->httpClient->setParameters([
                'group_id'    => $filter_group_id,
            ]);
            $result = $this->httpClient->request('GET');
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    throw new \Exception("接口网络错误，联系管理员！");
                } else {
                    throw new \Exception($result["message"]);
                }
            }
            $this->view->setVars([
                'filter_group_id'   => $filter_group_id,
                'filter_group_name' => $result['data']['info']['name'],
                'filters'           => $result['data']['info']['list'],
                'sort_order'        => $result['data']['info']['sort_order'],
            ]);
        }
    }

    /**
     * 删除
     */
    public function deleteAction()
    {
        if ($this->request->getPost('selected')) {
            $selected = $this->request->getPost('selected');
            $this->httpClient->setApiName("product/deleteFilterGroup.json");
            $this->httpClient->setParameters([
                'group_ids'    => $selected,
            ]);
            $this->httpClient->request('GET');
        }
        return $this->response->redirect([
            'for' => 'backend/filter'
        ]);
    }

    /**
     * 列表
     * @return array
     */
    public function autoCompleteAction()
    {
        $json = [];
        $data = $this->request->get();
        if (isset($data['filter_name'])) {
            $this->httpClient->setApiName("product/getFilterList.json");
            $this->httpClient->setParameters([
                'filter_name' => $data['filter_name'],
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
            foreach ($result['data']['list'] as $filter) {
                $json[] = [
                    'filter_id' => $filter['filter_id'],
                    'name'      => strip_tags(html_entity_decode($filter['group'] . ' &gt; ' . $filter['name'], ENT_QUOTES, 'UTF-8'))
                ];
            }
        }
        $sort_order = [];
        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }
        array_multisort($sort_order, SORT_ASC, $json);
        return ["status"=>0, "info" => "success", "data" => $json];
    }

}