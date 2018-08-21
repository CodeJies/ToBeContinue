<?php
/**
 * Created by PhpStorm.
 * User: zjt57
 * Date: 2018/8/21
 * Time: 16:33
 */

namespace app\jiandan\controller;

use app\jiandan\Base;
use app\jiandan\logic\Meizi as MeiziLogic;
class Meizi extends Base
{

    /**
     *
     * @param $pageIndex
     * @return \think\response\Json
     */
    public function pullMeiziList()
    {
        if (!file_exists(ROOT_PATH . "record_file.txt")) {
            mkdir(ROOT_PATH . 'record_file.txt', 0777, true);
        }

        $record_file = fopen("record_file.txt", "w");
        $pageIndex = fread($record_file);
        $pageIndex = empty($pageIndex) ? 47 : $pageIndex;
        fclose($record_file);

        $meizi_logic = \think\Loader::model('meizi','logic');
        $result      = $meizi_logic->pullMeiziList($pageIndex);

        return $this->jsonResponse($result);
    }


    /**
     * 获取meizi接口
     * @return \think\response\Json
     */
    public function getMeiziList()
    {
        $type = $this->request->get('type', 1);
        $res  = MeiziLogic::getMeiziList($type);
        return parent::jsonResponse($res);
    }


}