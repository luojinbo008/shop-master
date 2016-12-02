<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2016/11/23
 * Time: 9:57
 */

namespace Backend\Controllers;
use Library\Util\Util,
    Library\Models\User\UserModel;
class BlogController extends BaseController
{
    /**
     * 分类列表
     */
    public function categoryAction()
    {
        if ($this->request->isAjax() === true) {
            $iDisplayStart = $this->request->get("iDisplayStart");
            $iDisplayLength = $this->request->get("iDisplayLength");
            $this->httpClient->setApiName("content/getBlogCategoryList.json");
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
     * 新增分类
     * @return mixed
     */
    public function addCategoryAction()
    {
        $data = $this->request->getPost();
        $placeholder = $thumb = Util::resizeImage('no_image.png', 100, 100);
        if (!empty($data)) {
            if (empty($data['blog_category_name']) || empty($data['blog_category_meta_title'])) {
                $this->layoutMessage("必填项不能为空！", 1);
                if (empty($data['blog_category_name'])) {
                    $this->view->setVars([
                        'error_name' => '博客分类名称不能为空！'
                    ]);
                }
                if (empty($data['blog_category_meta_title'])) {
                    $this->view->setVars([
                        'error_meta_title'  => 'Meta Tag 标题不能为空！'
                    ]);
                }
                $this->view->setVars($data);
            } else {
                $this->httpClient->setApiName("content/addBlogCategory.json");
                $this->httpClient->setParameters([
                    'blog_category_name'                => $data['blog_category_name'],
                    'blog_category_description'         => $data['blog_category_description'],
                    'blog_category_meta_title'          => $data['blog_category_meta_title'],
                    'blog_category_meta_description'    => $data['blog_category_meta_description'],
                    'blog_category_meta_keyword'        => $data['blog_category_meta_keyword'],
                    'parent_id'                         => (int)$data['parent_id'],
                    'blog_category_store'               => isset($data['blog_category_store']) ? $data['blog_category_store'] : [],
                    'image'                             => $data['image'],
                    'sort_order'                        => (int)$data['sort_order'],
                    'status'                            => (int)$data['status'],
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
                    $this->layoutMessage("新增博客分类成功！");
                    return $this->response->redirect([
                        'for' => 'backend/blog/category'
                    ]);
                }
            }
        }
        $stores = $this->getStores();
        $this->view->setVars([
            'stores'        => $stores,
            'thumb'         => $thumb,
            'placeholder'   => $placeholder
        ]);
    }

    /**
     * 编辑分类
     * @throws \Exception
     */
    public function editCategoryAction()
    {
        $data = $this->request->getPost();
        $placeholder = $thumb = Util::resizeImage('no_image.png', 100, 100);
        if (!empty($data)) {
            if (empty($data['blog_category_name']) || empty($data['blog_category_meta_title'])) {
                $this->layoutMessage("必填项不能为空！", 1);
                if (empty($data['blog_category_name'])) {
                    $this->view->setVars([
                        'error_name' => '博客分类名称不能为空！'
                    ]);
                }
                if (empty($data['blog_category_meta_title'])) {
                    $this->view->setVars([
                        'error_meta_title'  => 'Meta Tag 标题不能为空！'
                    ]);
                }
                $this->view->setVars($data);
            } else {
                $this->httpClient->setApiName("content/editBlogCategory.json");
                $this->httpClient->setParameters([
                    'blog_category_id'                  => $data['blog_category_id'],
                    'blog_category_name'                => $data['blog_category_name'],
                    'blog_category_description'         => $data['blog_category_description'],
                    'blog_category_meta_title'          => $data['blog_category_meta_title'],
                    'blog_category_meta_description'    => $data['blog_category_meta_description'],
                    'blog_category_meta_keyword'        => $data['blog_category_meta_keyword'],
                    'parent_id'                         => (int)$data['parent_id'],
                    'blog_category_store'               => isset($data['blog_category_store']) ? $data['blog_category_store'] : [],
                    'image'                             => $data['image'],
                    'sort_order'                        => (int)$data['sort_order'],
                    'status'                            => (int)$data['status'],
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
                    $this->layoutMessage("编辑博客分类成功！");
                    return $this->response->redirect([
                        'for' => 'backend/blog/category'
                    ]);
                }
            }
        } else {
            $blog_category_id = $this->request->get('blog_category_id');
            $this->httpClient->setApiName("content/getBlogCategory.json");
            $this->httpClient->setParameters([
                'blog_category_id'  => $blog_category_id,
                'get_store'         => 1,
            ]);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']) {
                if (!$result) {
                    throw new \Exception("接口网络错误，联系管理员！");
                } else {
                    throw new \Exception($result["message"]);
                }
            }
            $blogCategoryInfo = $result['data']['info'];
            $image = "";
            if (!empty($blogCategoryInfo) && !empty($blogCategoryInfo['image'])) {
                $thumb = Util::resizeImage($blogCategoryInfo['image'], 100, 100);
                $image = $blogCategoryInfo['image'];
            } else {
                $thumb = Util::resizeImage('no_image.png', 100, 100);
            }
            $this->view->setVars([
                'blog_category_id'                  => $blogCategoryInfo['blog_category_id'],
                'blog_category_name'                => $blogCategoryInfo['name'],
                'blog_category_description'         => $blogCategoryInfo['description'],
                'blog_category_meta_title'          => $blogCategoryInfo['meta_title'],
                'blog_category_meta_description'    => $blogCategoryInfo['meta_description'],
                'blog_category_meta_keyword'        => $blogCategoryInfo['meta_keyword'],
                'parent_id'                         => (int)$blogCategoryInfo['parent_id'],
                'blog_category_store'               => $blogCategoryInfo['store_ids'],
                'image'                             => $image,
                'sort_order'                        => (int)$blogCategoryInfo['sort_order'],
                'status'                            => (int)$blogCategoryInfo['status'],
                'path_name'                         => $blogCategoryInfo['path'],
            ]);
        }
        $stores = $this->getStores();
        $this->view->setVars([
            'stores'        => $stores,
            'thumb'         => $thumb,
            'placeholder'   => $placeholder
        ]);
    }

    /**
     * 重构分类
     */
    public function repairCategoryAction()
    {
        $this->httpClient->setApiName("content/repairBlogCategory.json");
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            if (!$result) {
                throw new \Exception("接口网络错误，联系管理员！");
            } else {
                throw new \Exception($result["message"]);
            }
        }
        $this->layoutMessage("重构博客分类成功！");
        return $this->response->redirect([
            'for' => 'backend/blog/category'
        ]);
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function deleteCategoryAction()
    {
        $data = $this->request->get();
        if (isset($data['selected'])) {
            $this->httpClient->setApiName("content/deleteBlogCategory.json");
            $this->httpClient->setParameters([
                'blog_category_ids' => $data['selected'],
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
     * 获得博客分类列表
     * @return array
     * @throws \Exception
     */
    public function autoCategoryCompleteAction()
    {
        $json = [];
        $data = $this->request->get();
        if (isset($data['filter_name'])) {
            $this->httpClient->setApiName("content/getBlogCategoryList.json");
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
                    'blog_category_id'  => $result['blog_category_id'],
                    'name'              => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
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

    /**
     * 获得博客列表
     * @return array
     * @throws \Exception
     */
    public function listAction()
    {
        if ($this->request->isAjax() === true) {
            $iDisplayStart = $this->request->get("iDisplayStart");
            $iDisplayLength = $this->request->get("iDisplayLength");
            $this->httpClient->setApiName("content/getBlogList.json");
            $params = [
                'start' => $iDisplayStart,
                'limit' => $iDisplayLength,
            ];
            $filter_title = $this->request->get("filter_title");
            if ($filter_title) {
                $params['filter_title'] = $this->request->get("filter_title");
            }
            $filter_status = (int)$this->request->get("filter_status");
            if (2 != $filter_status) {
                $params['filter_status'] = $filter_status;
            }
            $this->httpClient->setParameters($params);
            $result = $this->httpClient->request("GET");
            if (!$result || 0 != $result['code']){
                return ["iTotalRecords"=> 0, "iTotalDisplayRecords"=> 0, 'aaData' => []];
            }
            return ["iTotalRecords"=> $result['data']['count'], "iTotalDisplayRecords"=> $result['data']['count'],
                'aaData' => $result['data']['list']];
        }
    }

    /**
     * 新增博客
     * @throws \Exception
     */
    public function addAction()
    {
        $data = $this->request->getPost();
        $placeholder = $thumb = Util::resizeImage('no_image.png', 100, 100);
        if (!empty($data)) {
            if (empty($data['title']) || empty($data['meta_title'])) {
                $this->layoutMessage("必填项不能为空！", 1);
                if (empty($data['title'])) {
                    $this->view->setVars([
                        'error_title' => '博客标题不能为空！'
                    ]);
                }
                if (empty($data['meta_title'])) {
                    $this->view->setVars([
                        'error_meta_title'  => 'Meta Tag 标题不能为空！'
                    ]);
                }
            } else {
                $this->httpClient->setApiName("content/addBlog.json");
                $this->httpClient->setParameters([
                    'title'                 => $data['title'],
                    'meta_title'            => $data['meta_title'],
                    'status'                => $data['status'],
                    'user_id'               => $data['user_id'],
                    'hits'                  => $data['hits'],
                    'image'                 => $data['image'],
                    'video_code'            => $data['video_code'],
                    'featured'              => $data['featured'],
                    'sort_order'            => $data['sort_order'],
                    'brief'                 => $data['brief'],
                    'description'           => $data['description'],
                    'meta_keyword'          => $data['meta_keyword'],
                    'meta_description'      => $data['meta_description'],
                    'tag'                   => $data['tag'],
                    'created'               => $data['created'],
                    'blog_blog_category'    => $data['blog_blog_category'],
                    'blog_store'            => isset($data['blog_store']) ? $data['blog_store'] : [],
                    'product_related'       => isset($data['product_related']) ? $data['product_related'] : [],
                    'blog_related'          => isset($data['blog_related']) ? $data['blog_related'] : [],
                ]);
                $result = $this->httpClient->request("POST");
                if (!$result || 0 != $result['code']) {
                    if (!$result) {
                        $error_warning = "接口网络错误，联系管理员！";
                    } else {
                        $error_warning = $result["message"];
                        $this->view->setVars($result['data']);
                    }
                    $this->layoutMessage($error_warning, 1);
                } else {
                    $this->layoutMessage("新增博客分类成功！");
                    return $this->response->redirect([
                        'for' => 'backend/blog/list'
                    ]);
                }
            }
            $product_relateds = [];
            if (isset($data['product_related']) && !empty($data['product_related'])) {
                $this->httpClient->setApiName("product/getProductList.json");
                $this->httpClient->setParameters([
                    'filter_product_ids' => $data['product_related'],
                ]);
                $result = $this->httpClient->request("GET");
                if (!$result || 0 != $result['code']) {
                    if (!$result) {
                        throw new \Exception("接口网络错误，联系管理员！");
                    } else {
                        throw new \Exception($result["message"]);
                    }
                }
                $product_relateds = $result['data']['list'];
            }
            $blog_relateds = [];
            if (isset($data['blog_related']) && !empty($data['blog_related'])) {
                $this->httpClient->setApiName("content/getBlogList.json");
                $this->httpClient->setParameters([
                    'filter_blog_ids' => $data['blog_related'],
                ]);
                $result = $this->httpClient->request("GET");
                if (!$result || 0 != $result['code']) {
                    if (!$result) {
                        throw new \Exception("接口网络错误，联系管理员！");
                    } else {
                        throw new \Exception($result["message"]);
                    }
                }
                $blog_relateds = $result['data']['list'];
            }
            if ($data['image']) {
                $thumb = Util::resizeImage($data['image'], 100, 100);
            }
            $this->view->setVars($data);
            $this->view->setVars([
                'blog_relateds'     => $blog_relateds,
                'product_relateds'  => $product_relateds,
            ]);
        } else {
            $this->view->setVars([
                'user_id' => $this->userid
            ]);
        }
        $this->httpClient->setApiName("content/getBlogCategoryList.json");
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            throw new \Exception(!$result ? "系统错误,联系管理员" : $result['message']);
        }
        $blog_categories = $result['data']['list'];
        $stores = $this->getStores();
        $model = new UserModel();
        $users = $model->getUserList($this->appid);
        $this->view->setVars([
            'users'                 => $users,
            'stores'                => $stores,
            'blog_categories'       => $blog_categories,
            'thumb'                 => $thumb,
            'placeholder'           => $placeholder
        ]);
    }

    /**
     * 删除博客
     * @return array
     * @throws \Exception
     */
    public function deleteAction()
    {
        $data = $this->request->get();
        if (isset($data['selected'])) {
            $this->httpClient->setApiName("content/deleteBlog.json");
            $this->httpClient->setParameters([
                'blog_ids' => $data['selected'],
            ]);
            $result = $this->httpClient->request("GET");

            var_dump($result);
            die();
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
     * @return array
     * @throws \Exception
     */
    public function autoCompleteAction()
    {
        $json = [];
        $data = $this->request->get();
        if (isset($data['filter_name'])) {
            $this->httpClient->setApiName("content/getBlogList.json");
            $this->httpClient->setParameters([
                'filter_name' => $data['filter_name'],
                'sort'        => 'name',
                'order'       => 'ASC',
                'start'       => 0,
                'limit'       => 10
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
                    'blog_id'   => $result['blog_id'],
                    'name'      => strip_tags(html_entity_decode($result['title'], ENT_QUOTES, 'UTF-8'))
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