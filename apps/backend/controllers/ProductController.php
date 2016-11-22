<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2016/8/22
 * Time: 16:00
 */

namespace Backend\Controllers;
use Library\Util\Util;

class ProductController extends BaseController
{
    /**
     * 商品列表
     * @return array
     */
    public function indexAction()
    {
        if ($this->request->isAjax() === true) {
            $data = $this->request->get();
            $iDisplayStart = $this->request->get("iDisplayStart");
            $iDisplayLength = $this->request->get("iDisplayLength");
            $this->httpClient->setApiName("product/getProductList.json");
            $filers = [
                'start' => $iDisplayStart,
                'limit' => $iDisplayLength,
            ];
            if (isset($data['filter_name']) && !empty($data['filter_name'])) {
                $filers['filter_name'] = $data['filter_name'];
            }
            if (isset($data['filter_price']) && !empty($data['filter_price'])) {
                $filers['filter_price'] = $data['filter_price'];
            }
            if (isset($data['filter_quantity']) && !empty($data['filter_quantity'])) {
                $filers['filter_quantity'] = $data['filter_quantity'];
            }
            if (isset($data['filter_product_id']) && !empty($data['filter_product_id'])) {
                $filers['filter_product_id'] = $data['filter_product_id'];
            }
            if (isset($data['filter_status'])) {
                $filers['filter_status'] = $data['filter_status'];
            }
            $this->httpClient->setParameters($filers);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                return ["iTotalRecords"=> 0, "iTotalDisplayRecords"=> 0, 'aaData' => []];
            }
            foreach ($result['data']['list'] as &$product) {
                if ($product['image']) {
                    $product['image'] = Util::resizeImage($product['image'], 100, 100);
                } else {
                    $product['image'] = Util::resizeImage('no-image', 100, 100);
                }
            }
            unset($product);
            return ["iTotalRecords"=> $result['data']['count'], "iTotalDisplayRecords"=> $result['data']['count'],
                'aaData' => $result['data']['list']];
        }
    }

    /**
     * 新增商品页面
     */
    public function addAction()
    {
        $data = $this->request->getPost();
        $thumb = $thumb_no_image = Util::resizeImage("no_image.png", 100, 100);
        if ($data) {
            $this->httpClient->setApiName("product/addProduct.json");
            $this->httpClient->setParameters($data);
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
                $product_filter = [];
                if (isset($data['product_filter']) && !empty($data['product_filter'])) {
                    $this->httpClient->setApiName("product/getFilterList.json");
                    $this->httpClient->setParameters([
                        'filter_ids' => $data['product_filter'],
                    ]);
                    $result = $this->httpClient->request("GET");
                    if (!$result || 0 != $result['code']) {
                        if (!$result) {
                            throw new \Exception("接口网络错误，联系管理员！");
                        } else {
                            throw new \Exception($result["message"]);
                        }
                    }
                    $product_filter = $result['data']['list'];
                }
                $product_category = [];
                if (isset($data['product_category']) && !empty($data['product_category'])) {
                    $this->httpClient->setApiName("product/getCategoryList.json");
                    $this->httpClient->setParameters([
                        'filter_category_ids'    => $data['product_category'],
                    ]);
                    $result = $this->httpClient->request("GET");
                    if (!$result || 0 != $result['code']) {
                        if (!$result) {
                            throw new \Exception("接口网络错误，联系管理员！");
                        } else {
                            throw new \Exception($result["message"]);
                        }
                    }
                    $product_category = $result['data']['list'];
                }
                $product_related = [];
                if (isset($data['product_related']) && !empty($data['product_related'])) {
                    $this->httpClient->setApiName("product/getProductList.json");
                    $this->httpClient->setParameters([
                        'filter_product_ids'    => $data['product_related'],
                    ]);
                    $result = $this->httpClient->request("GET");
                    if (!$result || 0 != $result['code']) {
                        if (!$result) {
                            throw new \Exception("接口网络错误，联系管理员！");
                        } else {
                            throw new \Exception($result["message"]);
                        }
                    }
                    $product_related = $result['data']['list'];
                }
                $option_values = [];
                if (isset($data['product_option']) && !empty($data['product_option'])) {
                    $product_option_ids = array_column($data['product_option'], 'option_id');
                    $this->httpClient->setApiName("product/getOptionList.json");
                    $this->httpClient->setParameters([
                        'filter_option_ids'    => $product_option_ids,
                        'get_value'            => 1
                    ]);
                    $result = $this->httpClient->request("GET");
                    if (!$result || 0 != $result['code']) {
                        if (!$result) {
                            throw new \Exception("接口网络错误，联系管理员！");
                        } else {
                            throw new \Exception($result["message"]);
                        }
                    }
                    $option_values = array_column($result['data']['list'], 'option_value', 'option_id');
                }

                if ($data['image']) {
                    $thumb = Util::resizeImage($data['image'], 100, 100);
                }
                if (isset($data['product_image'])) {
                    foreach ($data['product_image'] as &$product_image_tmp) {
                        if (empty($product_image_tmp['image'])) {
                            $product_image_tmp = [
                                'image'         => '',
                                'thumb'         => $thumb_no_image,
                                'sort_order'    => $product_image_tmp['sort_order']
                            ];
                        } else {
                            $product_image_tmp = [
                                'image'         => $product_image_tmp['image'],
                                'thumb'         => Util::resizeImage($product_image_tmp['image'], 100, 100),
                                'sort_order'    => $product_image_tmp['sort_order']
                            ];
                        }
                    }
                    unset($product_image_tmp);
                }
                $this->view->setVars($data);
                $this->layoutMessage($error_warning, 1);
                $this->view->setVars([
                    'option_value'      => $option_values,
                    'product_related'   => $product_related,
                    'product_filter'    => $product_filter,
                    'product_category'  => $product_category,
                ]);
            } else {
                return $this->response->redirect([
                    'for' => 'backend/product'
                ]);
            }
        } else {
            $this->view->setVars([
                'manufacturer' => ''
            ]);
        }
        $customer_groups = $this->getCustomerGroups();
        $stores = $this->getStores();
        $stock_statuses = $this->getStockStatuses();
        $this->view->setVars([
            'stock_statuses'    => $stock_statuses,
            'stores'            => $stores,
            'thumb'             => $thumb,
            'placeholder'       => $thumb_no_image,
            'customer_groups'   => $customer_groups,
        ]);
    }

    /**
     * 商品页面
     */
    public function editAction()
    {
        $thumb = $thumb_no_image = Util::resizeImage("no_image.png", 100, 100);
        $data = $this->request->getPost();
        if ($data) {
            $this->httpClient->setApiName("product/updateProduct.json");
            $this->httpClient->setParameters($data);
            $result = $this->httpClient->request("POST");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    $error_warning = "接口网络错误，联系管理员！";
                } else {
                    $error_warning = $result["message"];
                    $this->view->setVars($result['data']);
                }
                $product_filter = [];
                if (isset($data['product_filter']) && !empty($data['product_filter'])) {
                    $this->httpClient->setApiName("product/getFilterList.json");
                    $this->httpClient->setParameters([
                        'filter_ids' => $data['product_filter'],
                    ]);
                    $result = $this->httpClient->request("GET");
                    if (!$result || 0 != $result['code']) {
                        if (!$result) {
                            throw new \Exception("接口网络错误，联系管理员！");
                        } else {
                            throw new \Exception($result["message"]);
                        }
                    }
                    $product_filter = $result['data']['list'];
                }
                $data['product_filter'] = $product_filter;
                $product_category = [];
                if (isset($data['product_category']) && !empty($data['product_category'])) {
                    $this->httpClient->setApiName("product/getCategoryList.json");
                    $this->httpClient->setParameters([
                        'filter_category_ids'    => $data['product_category'],
                    ]);
                    $result = $this->httpClient->request("GET");
                    if (!$result || 0 != $result['code']) {
                        if (!$result) {
                            throw new \Exception("接口网络错误，联系管理员！");
                        } else {
                            throw new \Exception($result["message"]);
                        }
                    }
                    $product_category = $result['data']['list'];
                }
                $data['product_category'] = $product_category;
                $this->layoutMessage($error_warning, 1);
            } else {
                return $this->response->redirect([
                    'for' => 'backend/product'
                ]);
            }
        } else {
            $product_id = (int)$this->request->get('product_id');
            $this->httpClient->setApiName("product/getProductInfo.json");
            $this->httpClient->setParameters([
                'product_id'    => $product_id
            ]);
            $result_product = $this->httpClient->request("GET");
            if (!$result_product || 0 != $result_product['code']) {
                if (!$result_product) {
                  throw new \Exception("接口网络错误，联系管理员！");
                } else {
                  throw new \Exception($result_product["message"]);
                }
            }
            $data = $result_product['data']['info'];
        }
        $option_values = [];
        if (isset($data['product_option']) && !empty($data['product_option'])) {
            $product_option_ids = array_column($data['product_option'], 'option_id');
            $this->httpClient->setApiName("product/getOptionList.json");
            $this->httpClient->setParameters([
                'filter_option_ids'    => $product_option_ids,
                'get_value'            => 1
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    throw new \Exception("接口网络错误，联系管理员！");
                } else {
                    throw new \Exception($result["message"]);
                }
            }
            $option_values = array_column($result['data']['list'], 'option_value', 'option_id');
        }
        $data['option_value'] = $option_values;
        if (isset($data['product_image'])) {
           foreach ($data['product_image'] as &$product_image_tmp) {
               if (empty($product_image_tmp['image'])) {
                   $product_image_tmp = [
                       'image'         => '',
                       'thumb'         => $thumb_no_image,
                       'sort_order'    => $product_image_tmp['sort_order']
                   ];
               } else {
                   $product_image_tmp = [
                       'image'         => $product_image_tmp['image'],
                       'thumb'         => Util::resizeImage($product_image_tmp['image'], 100, 100),
                       'sort_order'    => $product_image_tmp['sort_order']
                   ];
               }
           }
           unset($product_image_tmp);
        }
        if ($data['image']) {
           $thumb = Util::resizeImage($data['image'], 100, 100);
        }
        $this->view->setVars($data);
        $product_related = [];
        if (isset($data['product_related'])
            && !empty($data['product_related'])) {
            $this->httpClient->setApiName("product/getProductList.json");
            $this->httpClient->setParameters([
                'filter_product_ids'    => $data['product_related'],
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    throw new \Exception("接口网络错误，联系管理员！");
                } else {
                    throw new \Exception($result["message"]);
                }
            }
            $product_related = $result['data']['list'];
        }

        $customer_groups = $this->getCustomerGroups();
        $stores = $this->getStores();
        $stock_statuses = $this->getStockStatuses();
        $this->view->setVars([
            'placeholder'       => $thumb_no_image,
            'thumb'             => $thumb,
            'product_related'   => $product_related,
            'stock_statuses'    => $stock_statuses,
            'stores'            => $stores,
            'customer_groups'   => $customer_groups,
        ]);
    }

    /**
     * 删除商品
     */
    public function deleteAction()
    {
        $data = $this->request->get();
        if (isset($data['selected'])) {
            $this->httpClient->setApiName("product/deleteProduct.json");
            $this->httpClient->setParameters([
                'product_ids'    => $data['selected'],
            ]);
            $result = $this->httpClient->request("GET");
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
     * 商品列表
     * @return array
     */
    public function autoCompleteAction()
    {
        $data = $this->request->get();
        if (isset($data['filter_name']) || isset($data['filter_model'])) {
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
                'get_option'    => 1,
                'filter_name'   => $filter_name,
                'start'         => 0,
                'limit'         => $limit
            ];
            $this->httpClient->setApiName("product/getProductList.json");
            $this->httpClient->setParameters($filter_data);
            $result = $this->httpClient->request("GET");
            return ["status" => 0, "info" => "success", "data" => $result['data']['list']];
        }
    }
}