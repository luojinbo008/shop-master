<?php
namespace Backend;
/**
 * 过滤器
 * Class Filter
 * @package Front
 */
use Phalcon\Mvc\Url;
class Filter{
    public $di;
    public function __construct($di){
        $this->di = $di;
    }
    /**
     * 登入过滤器
     * @return bool
     */
    public function authLogin(){
        $session = $this->di->get('session');
        $loginInfo = $session->get("backend_login_info");;
        if(!$loginInfo){
            $this->di->get('response')->redirect('backend/common/login')->sendHeaders();
            die();
        }else{
            return true;
        }

    }
}

