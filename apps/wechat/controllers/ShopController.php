<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 16-6-15
 * Time: 下午6:09
 */

namespace Wechat\Controllers;
use Library\Util\Util;

class ShopController extends BaseController
{
    /**
     * 商城首页
     */
    public function indexAction()
    {
        $storeInfo = $this->getStoreInfo();
        $advertList = [];
        if(!empty($storeInfo['advert_image'])) {
            foreach($storeInfo['advert_image'] as $tmp){
                $advertList[] = [
                    'image' => Util::resizeImage($tmp['image'], 384, 210),
                    'title' => $tmp['title'],
                    'link'  => $tmp['link'],
                ];
            }
        } else {
            $advertList[] = [
                'image' =>  Util::resizeImage('no_image.png', 384, 210),
                'title' => "请设置广告",
                'link'  => "",
            ];
        }
        switch ($storeInfo['industry']) {
            case 'tour' :
                $hotProductData = $this->getHotProducts();
                $hotProductList = [];
                foreach($hotProductData['list'] as $product){
                    $hotProductList[] = [
                        "name"          => $product['name'],
                        "meta_title"    => $product['meta_title'],
                        "image"         => Util::resizeImage($product['image'], 300, 100),
                        "product_id"    => $product['product_id'],
                    ];
                }
                $this->view->setVars([
                    'hotProducts'   => $hotProductList
                ]);
                break;
            case 'serve' :
                $categories = $this->getTopCategories();
                foreach($categories['list'] as &$category) {
                    foreach ($category['products']['list'] as $key => &$product) {
                        switch ($key) {
                            case 0 :
                                $product['image'] = Util::resizeImage($product['image'], 383, 484);
                                break;
                            default :
                                $product['image'] = Util::resizeImage($product['image'], 384, 241);
                        }
                    }
                }
                unset($product, $category);
                $this->view->setVars([
                    'categories'   => $categories['list']
                ]);
                break;
        }
        $this->view->setVars([
            'advertList'   => $advertList
        ]);
    }
} 