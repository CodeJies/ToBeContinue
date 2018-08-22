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
    public function pullMeiziList($pageIndex)
    {
        $file_path = ROOT_PATH . "record_file.txt";
        if (!file_exists($file_path)) {
            file_put_contents($file_path, $pageIndex);
        }
        if($pageIndex){
            $page = $pageIndex;
        }else{
            $record_file = fopen($file_path, 'rb');
            $page        = fread($record_file, filesize($file_path));
            fclose($record_file);
        }

        $meizi_logic = \think\Loader::model('meizi','logic');
        $result      = $meizi_logic->pullMeiziList($page);
        if($result){
            return self::pullMeiziList($page+1);
        }else{
            $record_file = fopen($file_path, 'w+');
            fwrite($record_file, ($page-1));
            fclose($record_file);
        }
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