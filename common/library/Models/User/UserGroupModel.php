<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 16-6-13
 * Time: 下午3:54
 */

namespace Library\Models\User;


use Library\Models\BaseModel;

class UserGroupModel extends BaseModel
{
    public $appid;
    public $user_group_id;
    public $name;
    public $permission;

    public function getSource()
    {
        return 'mcc_user_group';
    }
} 