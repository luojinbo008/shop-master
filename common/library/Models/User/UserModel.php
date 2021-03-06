<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 16-6-13
 * Time: 下午3:54
 */

namespace Library\Models\User;


use Library\Models\BaseModel;

class UserModel extends BaseModel
{
    public $appid;
    public $user_id;
    public $user_group_id;
    public $username;
    public $password;
    public $salt;
    public $fullname;
    public $email;
    public $image;
    public $code;
    public $ip;
    public $status;
    public $date_added;

    public function getSource()
    {
        return 'mcc_user';
    }

    /**
     * 获得用户列表
     * @return mixed
     */
    public function getUserList($appid)
    {
        $userList = self::find("status = '1' AND appid = " . (int)$appid);
        if (!$userList) {
            return [];
        }
        return $userList->toArray();
    }
} 