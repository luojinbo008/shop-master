<?php
/**
 * Created by PhpStorm.
 * User: luojinbo
 * Date: 2016/9/1
 * Time: 10:18
 */
namespace Library\Models\AppConfig;
use Library\Models\BaseModel;

class AppSignModel extends BaseModel
{
    public $id;
    public $appid;
    public $sign;
    public $host;
    public $name;

    /**
     * @return string
     */
    public function getSource()
    {
        return 'mcc_app_sign';
    }

    /**
     * 获得App配置信息
     * @param $host
     */
    public function getAppConfigByHost($host)
    {
        $obj = self::findFirst('host = "' . $host . '"');
        if (!$obj) {
            return false;
        }
        return $obj->toArray();
    }
}