<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-6-28
 * Time: 下午11:02
 */

namespace Wechat\Controllers;
use Library\Util\Util;

class ProductController extends BaseController
{
    /**
     * 商品列表
     */
    public function productListAction()
    {
        if ($this->request->isAjax() === true)
        {
            $category_id = $this->request->getPost('category_id');
            $filter_name = $this->request->getPost('filter_name');
            $page = (int)$this->request->getPost('page');
            $order = $this->request->getPost('order');
            $tmp_order = explode(',', $order);
            $filter = [
                'customer_group_id' => $this->customer_group_id ? $this->customer_group_id : $this->default_customer_group_id,
                'store_id'          => $this->store_id,
            ];
            if (count($tmp_order) == 2) {
                $filter['sort'] = $tmp_order[0];
                $filter['order'] = $tmp_order[1];
            }
            if ($category_id) {
                $filter['filter_category_id'] = $category_id;
            }
            if ($filter_name) {
                $filter['filter_name'] = $filter_name;
            }
            $filter['start'] = ($page - 1) * 8;
            $filter['limit'] = 8;
            $this->httpClient->setApiName("product/getProductsByStore.json");
            $this->httpClient->setParameters($filter);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                throw new \Exception("获得商品信息列表失败！");
            }
            $no_image = Util::resizeImage('no_image.png', 100, 90);
            foreach($result['data']['list'] as &$product){
                $product['url'] = $this->url->get(["for" => "product-detail"]) . "?product_id=" . $product['product_id'];
                if ($product['image'] ) {
                    $product['image'] = Util::resizeImage($product['image'], 100, 90);
                } else {
                    $product['image'] = $no_image;
                }
            }
            unset($product);
            return ["status" => 0, "info" => "success",
                "data" => [
                    'page'          => $page,
                    'total_page'    => floor($result['data']['count'] / 8) + 1,
                    'category_id'   => $category_id,
                    'products'      => $result['data']['list']
                ]
            ];
        }
        $category_id = (int)$this->request->get('category_id');
        $filter_name = $this->request->get('filter_name');

        if ($category_id == 0) {
            $titleName = '热门商品';
        } else {
            $titleName = '商品列表';
        }
        // 商品用通用模板
        $this->view->setViewsDir(__DIR__ . '/../views/common/');
        $this->view->setVars([
            'titleName'     => $titleName,
            'filter_name'   => $filter_name,
            'category_id'   => $category_id,
            'page'          => 0,
            'total_page'    => 0,
        ]);
    }

    /**
     * 获得商品详细信息
     * @throws \Exception
     */
    public function productDetailAction()
    {
        $product_id = $this->request->get('product_id');
        $filter = [];
        $filter['customer_group_id'] = $this->customer_group_id ?
            $this->customer_group_id : $this->default_customer_group_id;
        $filter['product_id'] = (int)$product_id;
        $filter['store_id'] = (int)$this->store_id;
        $this->httpClient->setApiName("product/getProductInfoByStore.json");
        $this->httpClient->setParameters($filter);
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            throw new \Exception("获得商品信息失败！");
        }
        $productInfo = $result['data']['info'];
        foreach($productInfo['images'] as &$image) {
            $image['image'] = Util::resizeImage($image['image'], 200, 200);
        }
        unset($image);
        $manufacturerName = '品牌';
        switch ($this->industry) {
            case 'serve' :
                $manufacturerName = '品牌';
                $buyName = '马上购买';
                break;
            case 'tour':
                $manufacturerName = '领队';
                $buyName = '马上参加';
                break;
        }
        $this->view->setViewsDir(__DIR__ . '/../views/common/');
        $this->view->setVars([
            'buyName'           => $buyName,
            'manufacturerName'  => $manufacturerName,
            'cartCount'         => $this->getCartProductCount(),
            'productInfo'       => $productInfo,
        ]);
    }
} 