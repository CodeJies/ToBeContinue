<?php
/**
 * Created by PhpStorm.
 * User: zjt57
 * Date: 2018/8/21
 * Time: 14:03
 */

namespace app\jiandan\model;

use think\Model;

class Joke extends Model
{
    public function insertJokeAll($saveData){
        return $this->insertAll($saveData);
    }
}