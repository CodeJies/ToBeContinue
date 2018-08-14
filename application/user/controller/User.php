<?php
namespace app\user\Controller;

use think\Controller;
use think\Db;
use app\user\model\User as UserModel;

/**
 * Created by PhpStorm.
 * User: itapp
 * Date: 2018/1/29
 * Time: 18:10
 */
class User extends Controller
{
    public function login(){

        $username=$this->request->post('username');
        $password=$this->request->post('password');
        $usermodel=new UserModel();
        return $usermodel->login($username,$password);
    }

    public function index(){

        $this->assign("data",$data=123);
        return $this->fetch('login');
    }

    public function pcLogin(){
        $username=$this->request->get('username')==null?123:$this->request->get('username');
        $password=$this->request->get('password')==null?123:$this->request->get('password');
        $usermodel=new UserModel();
        $result=$usermodel->login($username,$password);
        $this->assign('data',json_encode($result->getData(), JSON_UNESCAPED_UNICODE));
        return $this->fetch('login');
    }

    public function register(){
        $username=$this->request->post("username");
        $password=$this->request->post("password");
        if(is_null($username)||is_null($password)){
            return '输入正确参数';
        }else{
            $usermodel=new UserModel();
            return $usermodel->register($username,$password);
        }
    }

    public function getUserInfo(){
        $id=$this->request->post('id');
        $usermodel=new UserModel();
        return $usermodel->getUserInfo($id);
    }

    public function editUserInfo(){
        $id=$this->request->post('id');
        $nickname=$this->request->post('nickname');
        $signatrue=$this->request->post('signatrue');
        $sex=$this->request->post('sex');
        $birthday=$this->request->post('birthday');
        $usermodel=new UserModel();
        return $usermodel->editUserNickName($id,$nickname,$sex,$signatrue,$birthday);
    }

}