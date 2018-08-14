<?php
namespace app\user\model;

use think\Model;
use app\user\model\User as UserModel;

/**
 * Created by PhpStorm.
 * User: itapp
 * Date: 2018/1/30
 * Time: 14:39
 */
class User extends Model
{
    protected $name = 'user';

    public function register($username, $password)
    {
        if (!$this->isVaildUser($username)) {
            $user = new UserModel();
            $user->username = $username;
            $user->password = $password;
            $user->create_date = date("Y-m-d h:i:sa");
            if ($user->save()) {
                $register = array('msg' => '注册成功');
                return jsonString($register);
            } else {
                return jsonString(null, '注册失败', '0');
            }
        } else {
            return jsonString(null, '用户已存在', '0');
        }

    }

    //登录
    public function login($username, $password)
    {
        if ($this->isVaildUser($username)) {
            $user = UserModel::get(["username" => $username]);

            if ($user->password == $password) {
                return jsonString($user->getData());
            } else {
                return jsonString(null, '密码错误', '0');
            }
        } else {
            return jsonString(null, '用户不存在', '0');
        }
    }


    //修改昵称
    public function editUserNickName($id, $nickname, $sex, $signatrue, $birthday)
    {
        if ($this->isExistId($id)) {
            $user = UserModel::get(["id" => $id]);
            if (isset($nickname)) {
                $user->nickname = $nickname;
            }
            if (isset($sex)) {
                $user->sex = $sex;
            }
            if (isset($signatrue)) {
                $user->signatrue = $signatrue;
            }
            if (isset($birthday)) {
                $user->birthday = $birthday;
            }

            if (false !== $user->save()) {
                return jsonString($user->getData());
            } else {
                return jsonString($user->getError(), '修改失败', '0');
            }
        } else {
            return jsonString(null, '用户不存在', '0');
        }
    }

    //判断表中是否存在此username
    private function isVaildUser($username)
    {
        $user = UserModel::get(['username' => $username]);
        if (!is_null($user)) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserInfo($id){
        if ($this->isExistId($id)) {
            $user = UserModel::get(["id" => $id]);
            return jsonString($user->getData());
        }else {
            return jsonString(null, '用户不存在', '0');
        }
    }

    //判断表中是否存在此id

    private function isExistId($id)
    {
        $user = UserModel::get(['id' => $id]);
        if (!is_null($user->id)) {
            return true;
        } else {
            return false;
        }
    }
}