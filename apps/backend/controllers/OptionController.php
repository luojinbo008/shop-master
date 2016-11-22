<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-6-11
 * Time: 下午2:05
 */

namespace Backend\Controllers;
use Phalcon\Mvc\Dispatcher;
use Library\Util\Util;

class OptionController extends BaseController {

    /**
     * 首页列表
     */
    public function indexAction()
    {
        if ($this->request->isAjax() === true) {
            $iDisplayStart = $this->request->get("iDisplayStart");
            $iDisplayLength = $this->request->get("iDisplayLength");
            $this->httpClient->setApiName("product/getOptionList.json");
            $this->httpClient->setParameters([
                'start' => $iDisplayStart,
                'limit' => $iDisplayLength,
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                return ["iTotalRecords"=> 0, "iTotalDisplayRecords"=> 0, 'aaData' => []];
            }
            return ["iTotalRecords"=> $result['data']['count'], "iTotalDisplayRecords"=> $result['data']['count'],
                'aaData' => $result['data']['list']];
        }
    }

    /**
     * 新增选项
     * @return mixed
     */
    public function addAction()
    {
        $data = $this->request->getPost();
        if ($data) {
            $this->httpClient->setApiName("product/addOption.json");
            $params = [
                'name'          => $data['option_name'],
                'type'          => $data['type'],
                'sort_order'    => (int)$data['sort_order'],
            ];
            if (isset($data['option_value'])) {
                $params['option_values'] = $data['option_value'];
            }
            $this->httpClient->setParameters($params);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    $error_warning = "接口网络错误，联系管理员！";
                } else {
                    $error_warning = isset($result['data']['error_warning']) ? $result['data']['error_warning']
                        : $result['message'];
                    $this->view->setVars($result['data']);
                }
                $option_values = [];
                if(isset($data['option_value'])) {
                    foreach ($data['option_value'] as $option_value) {
                        if (!empty($option_value['image'])) {
                            $image = $option_value['image'];
                            $thumb = $option_value['image'];
                        } else {
                            $image = '';
                            $thumb = 'no_image.png';
                        }
                        $option_values[] = [
                            'option_value_id'          => $option_value['option_value_id'],
                            'option_value_name'        => $option_value['option_value_name'],
                            'image'                    => $image,
                            'thumb'                    => Util::resizeImage($thumb, 100, 100),
                            'sort_order'               => $option_value['sort_order']
                        ];
                    }
                }
                $data['option_values'] = $option_values;
                $this->view->setVars($data);
                $this->layoutMessage($error_warning, 1);
                $this->view->setVars([
                    'placeholder'   => Util::resizeImage('no_image.png', 100, 100),
                ]);
            } else {
                return $this->response->redirect([
                    'for' => 'backend/option'
                ]);
            }
        } else {
            $this->view->setVars([
                'type' => 'select',
                'placeholder' => Util::resizeImage('no_image.png', 100, 100),
            ]);
        }


    }

    /**
     * 删除
     * @return mixed
     */
    public function deleteAction()
    {
        $request = $this->request->get();
        if (isset($request['selected'])) {
            $selected = $this->request->getPost('selected');
            $this->httpClient->setApiName("product/deleteOption.json");
            $this->httpClient->setParameters([
                'option_ids'    => $selected,
            ]);
            $result = $this->httpClient->request('GET');
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    $error_warning = "接口网络错误，联系管理员！";
                } else {
                    $error_warning = $result["message"];
                }
                return ["info" => $error_warning, "data" => []];
            } else {
                return ["status" => 0,"info" => "删除成功！", "data" => []];
            }
        }
        return ["info" => "请选着删除项目！", "data" => []];
    }

    /**
     * 编辑选项
     * @throws \Exception
     */
    public function editAction()
    {
        $data = $this->request->getPost();
        if ($data) {
            $this->httpClient->setApiName("product/updateOption.json");
            $params = [
                'option_id'     => $data['option_id'],
                'name'          => $data['option_name'],
                'type'          => $data['type'],
                'sort_order'    => (int)$data['sort_order'],
            ];
            if (isset($data['option_value'])) {
                $params['option_values'] = $data['option_value'];
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
                $option_values = [];
                if(isset($data['option_value'])) {
                    foreach ($data['option_value'] as $option_value) {
                        if (!empty($option_value['image'])) {
                            $image = $option_value['image'];
                            $thumb = $option_value['image'];
                        } else {
                            $image = '';
                            $thumb = 'no_image.png';
                        }
                        $option_values[] = [
                            'option_value_id' => $option_value['option_value_id'],
                            'option_value_name' => $option_value['option_value_name'],
                            'image' => $image,
                            'thumb' => Util::resizeImage($thumb, 100, 100),
                            'sort_order' => $option_value['sort_order']
                        ];
                    }
                }
                $data['option_values'] = $option_values;
                $this->view->setVars($data);
                $this->layoutMessage($error_warning, 1);
                $this->view->setVars([
                    'placeholder'   => Util::resizeImage('no_image.png', 100, 100),
                ]);
            } else {
                return $this->response->redirect([
                    'for' => 'backend/option'
                ]);
            }
        } else {
            $option_id = $this->request->get('option_id');
            $this->httpClient->setApiName("product/getOptionInfo.json");
            $this->httpClient->setParameters([
                'option_id'     => $option_id
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    throw new \Exception("接口网络错误，联系管理员！");
                } else {
                    throw new \Exception($result["message"]);
                }
            }
            $option_info = $result['data']['info'];
            foreach ($option_info['option_values'] as &$option_value) {
                if ($option_value['image']) {
                    $option_value['thumb'] = Util::resizeImage($option_value['image'], 100, 100);
                } else {
                    $option_value['thumb'] = Util::resizeImage('no_image.png', 100, 100);
                }
            }
            unset($option_value);
            $this->view->setVars([
                'option_id' => $option_id,
                'option_name' => $option_info['name'],
                'placeholder' => Util::resizeImage('no_image.png', 100, 100),
                'option_values' => $option_info['option_values'],
                'sort_order' => $option_info['sort_order'],
                'type' => $option_info['type'],
            ]);
        }
    }

    /**
     * 获得列表
     * @return array
     */
    public function autoCompleteAction()
    {
        $json = [];
        $data = $this->request->get();
        if (isset($data['filter_name'])) {
            $this->httpClient->setApiName("product/getOptionList.json");
            $this->httpClient->setParameters([
                'get_value'     => 1,
                'filter_name'   => $data['filter_name'],
                'start'         => 0,
                'limit'         => 5,
            ]);
            $result = $this->httpClient->request("GET");
            foreach ($result['data']['list'] as $option) {
                $type = '';
                if ($option['type'] == 'select' || $option['type'] == 'radio' || $option['type'] == 'checkbox'
                    || $option['type'] == 'image') {
                    $type = "选择";
                }
                if ($option['type'] == 'text' || $option['type'] == 'textarea') {
                    $type = "文字录入";
                }
                if ($option['type'] == 'file') {
                    $type = "文件";
                }
                if ($option['type'] == 'date' || $option['type'] == 'datetime' || $option['type'] == 'time') {
                    $type = "日期";
                }
                $json[] = array(
                    'option_id'     => $option['option_id'],
                    'name'          => strip_tags(html_entity_decode($option['name'], ENT_QUOTES, 'UTF-8')),
                    'category'      => $type,
                    'type'          => $option['type'],
                    'option_value'  => $option['option_value']
                );
            }
        }
        $sort_order = [];
        foreach ($json as $key => $value) {
            $sort_order[$key] = $value['name'];
        }
        array_multisort($sort_order, SORT_ASC, $json);
        return ['status' => 0, "info" => "success", "data" => $json];
    }
} 
