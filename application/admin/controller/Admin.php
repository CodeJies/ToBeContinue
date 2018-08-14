<?php
namespace app\admin\controller;

/**
 * Created by PhpStorm.
 * User: itapp
 * Date: 2018/2/11
 * Time: 10:54
 */
use think\Controller;
use app\admin\model\Admin as AdminModel;

class Admin extends Controller
{

    public function index(){
        return $this->fetch('index');
    }


    public function login(){
        $username=$this->request->post('username');
        $password=$this->request->post('password');
        $usermodel=new AdminModel();
        return $usermodel->login($username,$password);
    }


}