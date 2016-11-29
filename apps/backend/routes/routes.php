<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 16-5-27
 * Time: 下午2:41
 */
use Backend\Filter;
$common = new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'common']);
$common->setPrefix('/backend/common');
$common->add('/login', ['action' => 'login'])->setName('backend/common/login');
$common->add('/loginOut', ['action' => 'loginOut'])->setName('backend/common/loginOut');
$common->add('/ashboard', ['action' => 'ashboard'])->setName('backend/common/ashboard')->beforeMatch([new Filter($di), 'authLogin']);
$common->add('/chart', ['action' => 'chart'])->setName('backend/common/chart')->beforeMatch([new Filter($di), 'authLogin']);
$common->add('/fileManager', ['action' => 'fileManager'])->setName('backend/common/fileManager')->beforeMatch([new Filter($di), 'authLogin']);
$common->add('/fileUpload', ['action' => 'fileUpload'])->setName('backend/common/fileUpload')->beforeMatch([new Filter($di), 'authLogin']);
$common->add('/fileFolder', ['action' => 'fileFolder'])->setName('backend/common/fileFolder')->beforeMatch([new Filter($di), 'authLogin']);
$common->add('/fileDelete', ['action' => 'fileDelete'])->setName('backend/common/fileDelete')->beforeMatch([new Filter($di), 'authLogin']);
$common->add('/uploadFileEditor', ['action' => 'uploadFileEditor'])->setName('backend/common/uploadFileEditor')->beforeMatch([new Filter($di), 'authLogin']);
$router->mount($common);

$filter = new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'filter']);
$filter->setPrefix('/backend/filter')->beforeMatch([new Filter($di), 'authLogin']);
$filter->add('', ['action' => 'index'])->setName('backend/filter');
$filter->add('/add', ['action' => 'add'])->setName('backend/filter/add');
$filter->add('/edit', ['action' => 'edit'])->setName('backend/filter/edit');
$filter->add('/delete', ['action' => 'delete'])->setName('backend/filter/delete');
$filter->add('/autoComplete', ['action' => 'autoComplete'])->setName('backend/filter/autoComplete');
$router->mount($filter);

$category = new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'category']);
$category->setPrefix('/backend/category')->beforeMatch([new Filter($di), 'authLogin']);
$category->add('', ['action' => 'index'])->setName('backend/category');
$category->add('/repair', ['action' => 'repair'])->setName('backend/category/repair');
$category->add('/add', ['action' => 'add'])->setName('backend/category/add');
$category->add('/edit', ['action' => 'edit'])->setName('backend/category/edit');
$category->add('/delete', ['action' => 'delete'])->setName('backend/category/delete');
$category->add('/autoComplete', ['action' => 'autoComplete'])->setName('backend/category/autoComplete');
$router->mount($category);

$attribute = new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'attribute']);
$attribute->setPrefix('/backend/attribute')->beforeMatch([new Filter($di), 'authLogin']);
$attribute->add('/group', ['action' => 'group'])->setName('backend/attribute/group');
$attribute->add('/group/add', ['action' => 'addGroup'])->setName('backend/attribute/group/add');
$attribute->add('/group/delete', ['action' => 'deleteGroup'])->setName('backend/attribute/group/delete');
$attribute->add('/group/edit', ['action' => 'editGroup'])->setName('backend/attribute/group/edit');
$attribute->add('/index', ['action' => 'index'])->setName('backend/attribute/index');
$attribute->add('/add', ['action' => 'add'])->setName('backend/attribute/add');
$attribute->add('/delete', ['action' => 'delete'])->setName('backend/attribute/delete');
$attribute->add('/edit', ['action' => 'edit'])->setName('backend/attribute/edit');
$attribute->add('/autoComplete', ['action' => 'autoComplete'])->setName('backend/attribute/autoComplete');
$router->mount($attribute);

$product =  new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'product']);
$product->setPrefix('/backend/product')->beforeMatch([new Filter($di), 'authLogin']);
$product->add('', ['action' => 'index'])->setName('backend/product');
$product->add('/add', ['action' => 'add'])->setName('backend/product/add');
$product->add('/edit', ['action' => 'edit'])->setName('backend/product/edit');
$product->add('/delete', ['action' => 'delete'])->setName('backend/product/delete');
$product->add('/autoComplete', ['action' => 'autoComplete'])->setName('backend/product/autoComplete');
$router->mount($product);

$option = new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'option']);
$option->setPrefix('/backend/option')->beforeMatch([new Filter($di), 'authLogin']);
$option->add('', ['action' => 'index'])->setName('backend/option');
$option->add('/add', ['action' => 'add'])->setName('backend/option/add');
$option->add('/delete', ['action' => 'delete'])->setName('backend/option/delete');
$option->add('/edit', ['action' => 'edit'])->setName('backend/option/edit');
$option->add('/autoComplete', ['action' => 'autoComplete'])->setName('backend/option/autoComplete');
$router->mount($option);

$manufacturer = new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'manufacturer']);
$manufacturer->setPrefix('/backend/manufacturer')->beforeMatch([new Filter($di), 'authLogin']);
$manufacturer->add('', ['action' => 'index'])->setName('backend/manufacturer');
$manufacturer->add('/add', ['action' => 'add'])->setName('backend/manufacturer/add');
$manufacturer->add('/edit', ['action' => 'edit'])->setName('backend/manufacturer/edit');
$manufacturer->add('/autoComplete', ['action' => 'autoComplete'])->setName('backend/manufacturer/autoComplete');
$manufacturer->addPost('/delete', ['action' => 'delete'])->setName('backend/manufacturer/delete');
$router->mount($manufacturer);

$order =  new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'order']);
$order->setPrefix('/backend/order')->beforeMatch([new Filter($di), 'authLogin']);
$order->add('', ['action' => 'index'])->setName('backend/order');
$order->add('/view', ['action' => 'view'])->setName('backend/order/view');
$order->addPost('/refundSubmit', ['action' => 'refundSubmit'])->setName('backend/order/refundSubmit');
$order->add('/getOrderHistories', ['action' => 'getOrderHistories'])->setName('backend/order/getOrderHistories');
$router->mount($order);

$customer = new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'customer']);
$customer->setPrefix('/backend/customer')->beforeMatch([new Filter($di), 'authLogin']);
$customer->add('', ['action' => 'index'])->setName('backend/customer');
$customer->add('/online', ['action' => 'online'])->setName('backend/customer/online');
$customer->add('/autoComplete', ['action' => 'autoComplete'])->setName('backend/customer/autoComplete');
$customer->add('/view', ['action' => 'view'])->setName('backend/customer/view');
$customer->addPost('/edit', ['action' => 'edit'])->setName('backend/customer/edit');
$router->mount($customer);

$store = new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'store']);
$store->setPrefix('/backend/store')->beforeMatch([new Filter($di), 'authLogin']);
$store->add('', ['action' => 'index'])->setName('backend/store');
$store->add('/add', ['action' => 'add'])->setName('backend/store/add');
$store->add('/delete', ['action' => 'delete'])->setName('backend/store/delete');
$store->add('/edit', ['action' => 'edit'])->setName('backend/store/edit');
$router->mount($store);

$wechat = new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'wechat']);
$wechat->setPrefix('/backend/wechat')->beforeMatch([new Filter($di), 'authLogin']);
$wechat->add('/index', ['action' => 'index'])->setName('backend/wechat/index');
$wechat->add('/authorization', ['action' => 'authorization'])->setName('backend/wechat/authorization');
$wechat->add('/unAuthorization', ['action' => 'unAuthorization'])->setName('backend/wechat/unAuthorization');
$router->mount($wechat);

$blog = new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'blog']);
$blog->setPrefix('/backend/blog')->beforeMatch([new Filter($di), 'authLogin']);
//博客分类
$blog->add('/category', ['action' => 'category'])->setName('backend/blog/category');
$blog->add('/category/add', ['action' => 'addCategory'])->setName('backend/blog/category/add');
$blog->add('/category/repair', ['action' => 'repairCategory'])->setName('backend/blog/category/repair');
$blog->add('/category/delete', ['action' => 'deleteCategory'])->setName('backend/blog/category/delete');
$blog->add('/category/autoComplete', ['action' => 'autoCategoryComplete'])->setName('backend/blog/category/autoComplete');
$blog->add('/category/edit', ['action' => 'editCategory'])->setName('backend/blog/category/edit');
//博客文章
$blog->add('/list', ['action' => 'list'])->setName('backend/blog/list');
$blog->add('/delete', ['action' => 'delete'])->setName('backend/blog/delete');
$router->mount($blog);

$payment = new \Phalcon\Mvc\Router\Group(['module' => 'backend','namespace' => 'Backend\Controllers',
    'controller' => 'payment']);
$payment->setPrefix('/backend/payment')->beforeMatch([new Filter($di), 'authLogin']);
$payment->add('/wechat', ['action' => 'wechat'])->setName('backend/payment/wechat');
$payment->add('/alipay', ['action' => 'alipay'])->setName('backend/payment/alipay');
$router->mount($payment);