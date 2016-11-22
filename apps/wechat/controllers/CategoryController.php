<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 16-7-7
 * Time: 下午7:00
 */

namespace Wechat\Controllers;
use Library\Util\Util;

class CategoryController extends BaseController
{
    /**
     * 获得商店分类列表
     * @throws \Exception
     */
    public function indexAction()
    {
        $this->httpClient->setApiName("product/getStoreCategoryList.json");
        $params = [
            'parent_id'      => 0,
            'store_id'      => (int)$this->store_id,
        ];
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            $message = !$result ? "服务失败，联系管理员" : $result['message'];
            throw new \Exception($message);
        }
        $category_list = [];
        foreach ($result['data']['list'] as $category) {
            $category_list[] = [
                "name" => $category['name'],
                "meta_title" => $category['meta_title'],
                "image" => Util::resizeImage($category['image'], 100, 100),
                "category_id" => $category['category_id']
            ];
        }
        $this->view->setViewsDir(__DIR__ . '/../views/common/');
        $this->view->setVars([
            'category_list' => $category_list,
        ]);
    }
} 