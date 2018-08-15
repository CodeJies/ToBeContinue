<?php
/**
 * Created by PhpStorm.
 * User: laung
 * Date: 2018/2/7 0007
 * Time: 16:40
 */

namespace app\jiandan;

use app\common\handle\JsonHandler;
use think\Controller;
use think\Db;
use think\Request;

class Base extends Controller
{
    //token
    protected $token = '';

    //设备类型
    protected $deviceType = 'wxapp';

    //用户 id
    protected $userId = 0;

    //用户
    protected $user;

    //用户类型
    protected $userType;

    protected $allowedDeviceTypes = ['mobile', 'android', 'iphone', 'ipad', 'web', 'pc', 'mac', 'wxapp'];

    /**
     * 架构函数
     * @param Request $request Request对象
     * @access public
     */
    public function __construct(Request $request = null)
    {
        if (is_null($request)) {
            $request = Request::instance();
        }

        $this->request = $request;

        // 用户验证初始化
//        $this->_initUser();

        // 控制器初始化
        $this->_initialize();

    }

    // 初始化
    protected function _initialize()
    {
    }

    private function _initUser()
    {
        $token      = $this->request->header('XX-Token');
        $deviceType = $this->request->header('XX-Device-Type');

//        if (empty($deviceType)) {
//            return;
//        }
        $deviceType = empty($deviceType)?'wxapp':$deviceType;
        if (!in_array($deviceType, $this->allowedDeviceTypes)) {
            return;
        }

        $this->deviceType = $deviceType;

        if (empty($token)) {
            return;
        }

        $this->token = $token;

        $user = Db::name('user_token')
            ->alias('a')
            ->field('a.expire_time,b.nickname,b.head_pic,b.openid,b.unionid,b.mobile,b.sex,b.app_id')
            ->where(['token' => $token, 'device_type' => $deviceType])
            ->join('__USERS__ b', 'a.user_id = b.user_id')
            ->find();

        if (!empty($user) && ($user['expire_time'] > time())) {
            $this->user     = $user;
            $this->userId   = $user['id'];
        }

    }

    protected function jsonResponse($data)
    {
        if ($data) {
            return $this->successReturn($data);
        }
        return $this->failReturn($data);
    }

    /**
     * 成功返回
     * @param $data
     * @return \think\response\Json
     */
    protected function successReturn($data = null)
    {
        return JsonHandler::successReturn($data);
    }

    /**
     * 失败返回
     * @param $msg
     * @param null $data
     * @return \think\response\Json
     */
    protected function failReturn($msg='获取失败', $data = null)
    {
        return JsonHandler::failReturn($msg, $data);
    }


}