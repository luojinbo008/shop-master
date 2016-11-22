<?php
namespace Backend\Controllers;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Dispatcher;
use Library\Util\Util;
/**
 * Class ControllerBase
 * @package WeiXin\Front\Controllers
 */

class BaseController extends Controller
{
    protected $currentMenu = [];
    protected $currentRoute;

    static $messageType = [
        'success', 'error', 'notice', 'warning'
    ];
    public $appid = null;
    public $httpClient = null;



    /**
     * 解析route之前
     * @param Dispatcher $dispatcher
     */
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $this->httpClient = $this->di->get('httpClient');
        $appInfo = $this->di->get('appInfo');
        $this->appid = $appInfo['appid'];
        if (!$this->request->isAjax()) {
            $backend_login_info = $this->session->get('backend_login_info');
            $this->userid =  $backend_login_info['user_id'];
            $this->username = $backend_login_info['username'];
            $this->rolename =  $backend_login_info['rolename'];

            $menus = $this->getMenu();
            $this->analysisCurrentMenu($menus, $this->currentMenu, 0);
            $this->view->setVars([
                    'currentMenu'   => $this->currentMenu,
                    'menus'         => $menus,
                    'rolename'      => $this->rolename,
                    'userid'        => $this->userid,
                    'username'      => $this->username,
                    'appname'       => $appInfo['name']
                ]
            );
        }
    }

    /**
     * 解析
     * @param $menu
     */
    private function analysisCurrentMenu($menus, &$path, $len)
    {
        $url = $this->di->get('url');
        $currentUrl = $url->getBaseUri() .  trim($this->request->getURI(), '/');

        if (strpos($currentUrl, '?') !== false) {
            $currentUrl = strstr($currentUrl, '?', true);
        }
        foreach($menus as $key => $parentVal) {
            $path[$len]  = [
                'for'       => isset($parentVal['for']) ? $parentVal['for'] : null ,
                'name'      => $parentVal['name'],
            ];
            if (isset($parentVal['for']) && !empty($parentVal['for'])) {
                $href = $url->get(['for' => $parentVal['for']]);
                if ($currentUrl == $href) {
                    $children = isset($parentVal['children']) ? $parentVal['children'] : [];
                    $tmps['top']    = [];
                    $tmps['table']  = [];
                    foreach ($children as $tmp) {
                        if ($tmp['layout'] == 'table') {
                            $tmps['table'][]  = $tmp;
                        } else if($tmp['layout'] == 'top') {
                            $tmps['top'][]  = $tmp;
                        }
                    }
                    $path[$len]['children'] = $tmps;
                    for ($i = $len + 1; $i <= count($path); $i++){
                        unset($path[$i]);
                    }
                    return true;
                }
            }
            if (isset($parentVal['children']) && count($parentVal['children'])) {
                if ($this->analysisCurrentMenu($parentVal['children'], $path, $len + 1)) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param Dispatcher $dispatcher
     * @return mixed
     */
    protected function afterExecuteRoute(Dispatcher $dispatcher)
    {
        if ($this->request->isAjax() === true)
        {
            $this->view->disableLevel([
                View::LEVEL_ACTION_VIEW => true,
                View::LEVEL_LAYOUT => true,
                View::LEVEL_MAIN_LAYOUT => true,
                View::LEVEL_AFTER_TEMPLATE => true,
                View::LEVEL_BEFORE_TEMPLATE => true
            ]);
            $this->response->setContentType('application/json', 'UTF-8');
            $data = $dispatcher->getReturnedValue();
            if (is_array($data)) {
                $data['status'] = isset($data['status'])  ? $data['status'] : 1;
                $data['info'] = isset($data['info']) ? $data['info'] : '';
                $data = json_encode($data);
            }
            $this->response->setContent($data);
        }
        return $this->response->send();
    }

    /**
     * 图片上传至图片服务器
     * @param $files
     * @param $module
     * @param string $thumb
     * @param bool $cover
     * @return bool
     */
    protected function uploadImage($files, $module, $directory = '')
    {
        if(empty($files) || !$this->request->hasFiles()) {
            return false;
        }
        $fileTmp = [
            'tmp_name'=> $files->getTempName(),
            'type'=> $files->getRealType(),
            'name' => $files->getName()
        ];
        $res = Util::upload($this->appid, $fileTmp, "main", $module, $directory);
        if(is_array($res))
        {
            return $res[0];
        }
        return false;
    }

    /**
     * 获得商店列表
     * @throws \Exception
     */
    protected function getStores()
    {
        $this->httpClient->setApiName("setting/getStoreList.json");
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            if (!$result) {
                throw new \Exception("接口网络错误，联系管理员！");
            } else {
                throw new \Exception($result["message"]);
            }
        }
        return $result['data']['list'];
    }

    /**
     * 获得客户组列表
     * @return array
     */
    protected function getCustomerGroups()
    {
        $this->httpClient->setApiName("customer/getCustomerGroupList.json");
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            return [];
        }
        return $result['data']['list'];
    }


    /**
     * 获得库存状态名次列表
     * @return array
     */
    protected function getStockStatuses()
    {
        $this->httpClient->setApiName('product/getStockStatus.json');
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            return [];
        }
        return $result['data']['list'];
    }

    /**
     * 系统跳转
     * @param $for
     * @param $message
     * @param int $type
     * @param array $args
     * @return mixed
     */
    protected function redirect($for, $message, $type = 0, $args = [])
    {
        $url = $this->di->get('url');
        $path = $url->get($for, $args);
        $this->layoutMessage($message, $type);
        return $this->response->redirect($path);
    }

    /**
     * @param $route
     * @param string $message
     * @param int $type
     * @return mixed
     */
    protected function layoutMessage($message = '', $type = 0)
    {
        if ($message) {
            $this->flashSession->message(self::$messageType[$type], $message);
        }
    }

    /**
     * 获得菜单
     */
    protected function getMenu()
    {
        $this->httpClient->setApiName('setting/getMenus.json');
        $result = $this->httpClient->request("GET");
        if (!$result || 0 != $result['code']) {
            if (!$result) {
                throw new \Exception("接口网络错误，联系管理员！");
            } else {
                throw new \Exception($result["message"]);
            }
        }
        return $result['data']['list'];
    }
}
