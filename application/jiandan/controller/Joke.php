<?php
/**
 * Created by PhpStorm.
 * User: zjt57
 * Date: 2018/8/21
 * Time: 12:09
 */

namespace app\jiandan\controller;

use app\jiandan\Base;
use app\jiandan\logic\Joke as JokeLogic;

class Joke extends Base
{
    /**
     * 拉取段子
     * @return \think\response\Json
     */
    public function pullJokeList()
    {
        $joke_logic = \think\Loader::model('joke','logic');
        $res = $joke_logic->pullJokeHot();
        return parent::jsonResponse($res);
    }

    /**
     * 获取jokeList
     * @return \think\response\Json
     */
    public function getJokeList()
    {
        $result = JokeLogic::getJokeList();
        return parent::jsonResponse($result);
    }

}