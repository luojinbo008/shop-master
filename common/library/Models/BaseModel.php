<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-6-1
 * Time: 上午12:21
 */

namespace Library\Models;
use Phalcon\Mvc\Model;

class BaseModel extends Model{

    public function initialize(){

    }

    public function escape($value) {
        if(is_null($value) || empty($value)){
            return '';
        }
   		return str_replace(["\\", "\0", "\n", "\r", "\x1a", "'", '"'],
            ["\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"'], $value);
   	}
} 