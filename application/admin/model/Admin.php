<?php
namespace app\admin\model;

/**
 * Created by PhpStorm.
 * User: itapp
 * Date: 2018/2/11
 * Time: 10:56
 */
use think\Model;
use app\admin\model\Admin as AdminModel;

class Admin extends Model
{
    protected $name="admin";


    public function login($username ,$password){
        if($this->isVaildUser($username)){
            $admin=AdminModel::get(["username"=>$username]);

            if($admin->password==$password){
                return jsonString($admin->getData());
            }else {
                return jsonString(null,'密码错误','0');
            }
        }else{
            return jsonString(null,'用户不存在','0');
        }
    }

    //判断表中是否存在此username
    private function isVaildUser($username){
        $admin=AdminModel::get(['username'=>$username]);
        if(!is_null($admin->id)){
            return true;
        }else {
            return false;
        }
    }
}