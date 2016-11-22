<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 16-5-27
 * Time: 下午2:41
 */
use Wechat\Filter;
$shop = new \Phalcon\Mvc\Router\Group(['module' => 'wechat','namespace' => 'Wechat\Controllers',
    'controller' => 'shop']);
$shop->setPrefix('/wechat/shop');
$shop->add('/index', ['action' => 'index'])->setName('shop-index');
$router->mount($shop);

$category = new \Phalcon\Mvc\Router\Group(['module' => 'wechat','namespace' => 'Wechat\Controllers',
    'controller' => 'category']);
$category->setPrefix('/wechat/category');
$category->add('/index', ['action' => 'index'])->setName('category-index');
$router->mount($category);

$product = new \Phalcon\Mvc\Router\Group(['module' => 'wechat','namespace' => 'Wechat\Controllers',
    'controller' => 'product']);
$product->setPrefix('/wechat/product')->beforeMatch([new Filter($di), 'filterOpenid']);
$product->add('/productList', ['action' => 'productList'])->setName('product-list');
$product->add('/productDetail', ['action' => 'productDetail'])->setName('product-detail');
$router->mount($product);

$cart = new \Phalcon\Mvc\Router\Group(['module' => 'wechat','namespace' => 'Wechat\Controllers',
    'controller' => 'cart']);
$cart->setPrefix('/wechat/cart')->beforeMatch([new Filter($di), 'authLogin']);
$cart->addPost('/addProduct', ['action' => 'addProduct'])->setName('cart-add');
$cart->add('/products', ['action' => 'products'])->setName('cart-products');
$cart->addPost('/changeProductQuantity', ['action' => 'changeProductQuantity'])->setName('cart-changeQuantity');
$cart->addPost('/checkoutCart', ['action' => 'checkoutCart'])->setName('cart-checkout');
$cart->add('/deleteCart', ['action' => 'deleteCart'])->setName('cart-delete');
$router->mount($cart);

$order = new \Phalcon\Mvc\Router\Group(['module' => 'wechat','namespace' => 'Wechat\Controllers',
    'controller' => 'order']);
$order->setPrefix('/wechat/order')->beforeMatch([new Filter($di), 'authLogin']);
$order->add('/list', ['action' => 'list'])->setName('order-list');
$order->add('/detail', ['action' => 'detail'])->setName('order-detail');
$order->addPost('/checkAddress', ['action' => 'checkAddress'])->setName('order-checkAddress');
$order->addPost('/createOrderByProduct', ['action' => 'createOrderByProduct'])->setName('order-createOrderByProduct');
$order->addPost('/refund', ['action' => 'refund'])->setName('order-refund');
$order->add('/checkout', ['action' => 'checkout'])->setName('order-checkout');
$router->mount($order);

$user = new \Phalcon\Mvc\Router\Group(['module' => 'wechat','namespace' => 'Wechat\Controllers',
    'controller' => 'user']);
$user->setPrefix('/wechat/user');
$user->add('/index', ['action' => 'index'])->setName('user-index')->beforeMatch([new Filter($di), 'authLogin']);
$user->add('/manage', ['action' => 'manage'])->setName('user-manage')->beforeMatch([new Filter($di), 'authLogin']);
$user->addPost('/update', ['action' => 'update'])->setName('user-update')->beforeMatch([new Filter($di), 'authLogin']);
$user->add('/addAddress', ['action' => 'addAddress'])->setName('user-addAddress')->beforeMatch([new Filter($di), 'authLogin']);
$user->add('/editAddress', ['action' => 'editAddress'])->setName('user-editAddress')->beforeMatch([new Filter($di), 'authLogin']);
$user->addPost('/setAddressDefault', ['action' => 'setAddressDefault'])->setName('user-setAddressDefault')->beforeMatch([new Filter($di), 'authLogin']);
$user->add('/addressList', ['action' => 'addressList'])->setName('user-addressList')->beforeMatch([new Filter($di), 'authLogin']);
$user->addPost('/deleteAddress', ['action' => 'deleteAddress'])->setName('user-deleteAddress')->beforeMatch([new Filter($di), 'authLogin']);
$user->add('/register', ['action' => 'register'])->setName('user-register')->beforeMatch([new Filter($di), 'filterOpenid']);
$router->mount($user);

$paysubmit = new \Phalcon\Mvc\Router\Group(['module' => 'wechat','namespace' => 'Wechat\Controllers',
    'controller' => 'paysubmit']);
$paysubmit->setPrefix('/wechat/paysubmit');
$paysubmit->add('/wechat', ['action' => 'wechatSubmit'])->setName('user-wechatSubmit');
$paysubmit->add('/alipay', ['action' => 'alipaySubmit'])->setName('user-alipaySubmit');
$router->mount($paysubmit);