<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-7-9
 * Time: 下午3:40
 */

namespace Wechat\Controllers;
use Library\Util\Util;

class CartController extends BaseController
{

    /**
     * 增加商品到购物车
     * @return array
     * @throws \Exception
     */
    public function addProductAction()
    {
        $product_id = $this->request->getPost("product_id");
        $option = $this->request->getPost("option");
        $quantity = $this->request->getPost("quantity");
        if ($product_id) {
            $this->httpClient->setApiName("customer/addProductToCart.json");
            $params = [
                'store_id'      => $this->store_id,
                'product_id'    => $product_id,
                'customer_id'   => $this->customer_id,
                'session_id'    => $this->session->getId(),
                'quantity'      => (int)$quantity,
                'option'        => (array)$option,

            ];
            $this->httpClient->setParameters($params);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                return ["info" => "error", "data" => []];
            }
            return ["status" => 0, "info" => "success", "data" => ['count' => $result['data']['count']]];
        }
        return ["info" => "error", "data" => []];
    }

    /**
     * 更新购物车商品数量
     * @return array
     */
    public function changeProductQuantityAction()
    {
        $cart_id = $this->request->getPost("cart_id");
        $quantity = $this->request->getPost("quantity");
        $this->httpClient->setApiName("customer/changeCartProductQuantity.json");
        $params = [
            'cart_id'     => $cart_id,
            'quantity'    => $quantity
        ];
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("POST");
        if (!$result || 0 != $result['code']) {
            return ["info" => "更新购物车失败，请稍后再试！", "data" => []];
        }
        return ["status" => 0, "info" => "更新购物车成功！", "data" => ['price' => $result['data']['price']]];
    }

    /**
     * 购物车商品列表
     */
    public function productsAction()
    {
        $this->httpClient->setApiName("customer/getCartProducts.json");
        $params = [
            'store_id'      => $this->store_id,
            'customer_id'   => $this->customer_id,
            'session_id'    => $this->session->getId(),
        ];
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("GET");

        if (!$result || 0 != $result['code']) {
            throw new \Exception("获得购物车信息失败，请重试");
        }
        $products = $result['data']['list'];
        $total = 0;
        foreach ($products as &$product) {
            $total += $product['total'];
            $product['image'] = Util::resizeImage($product['image'], 100, 100);
        }
        unset($product);
        $this->view->setViewsDir(__DIR__ . '/../views/common/');
        $this->view->setVars([
            "total" => $total,
            "product_list" => $products
        ]);
    }

    /**
     * 根据购物车生成订单
     * @return array
     * @throws \Exception
     */
    public function checkoutCartAction()
    {
        $this->httpClient->setApiName("customer/checkoutCart.json");
        $cart_id = $this->request->getPost("cart_ids");
        $params = [
            'cart_id'       => $cart_id,
            'store_id'      => $this->store_id,
            'customer_id'   => $this->customer_id,
            'session_id'    => $this->session->getId(),
            'ip'            => Util::getClientIp(),
        ];
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("POST");
        if (!$result || 0 != $result['code']) {
            $message = !$result ? "服务失败，联系管理员" : $result['message'];
            throw new \Exception($message);
        }
        return ["status" => 0, "info" => "success", "data" => ["order_id" => $result["data"]["order_id"]]];
    }

    /**
     * 删除购物车
     * @return array
     * @throws \Exception
     */
    public function deleteCartAction()
    {
        $cart_id = $this->request->getPost("cart_id");
        $params = [
            'store_id'      => $this->store_id,
            'customer_id'   => $this->customer_id,
            'session_id'    => $this->session->getId(),
            'cart_id'       => $cart_id,
        ];
        $this->httpClient->setApiName("customer/deleteCart.json");
        $this->httpClient->setParameters($params);
        $result = $this->httpClient->request("POST");
        if (!$result || 0 != $result['code']) {
            $message = !$result ? "服务失败，联系管理员" : $result['message'];
            throw new \Exception($message);
        }
        return ["status" => 0, "info" => "success", "data" => []];
    }
} 