<?php
/**
 * Created by PhpStorm.
 * User: zjt57
 * Date: 2018/8/21
 * Time: 16:35
 */

namespace app\jiandan\model;


use think\Model;

class Meizi extends Model
{
    public function insertMeiziAll($saveData)
    {
        return $this->insertAll($saveData);
    }
}