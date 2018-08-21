<?php
/**
 * Created by PhpStorm.
 * User: zjt57
 * Date: 2018/8/21
 * Time: 14:26
 */

namespace app\jiandan\logic;

use \app\jiandan\model\Joke as JokeModel;
use think\Db;

class Joke extends JokeModel
{
    public static function getJokeList()
    {
        $data = Db::table('joke')
            ->field('id,author,content,updated_at,created_at')
            ->paginate(10);
        return $data->toArray();
    }

    /**
     * 获取热门段子
     */
    public function pullJokeHot()
    {
        $tagetUrl = 'http://jandan.net/top-duan';
        $xpath = get_x_path($tagetUrl);

        //根據規則抓取热门段子的數據
        $content = $xpath->evaluate("//*[@id=\"duan\"]/ol/li/div/div/div[2]|//*[@id=\"duan\"]/ol/li/div/div/div[1]/strong");

        $data = array();
        $count = 0;
        for ($i = 0; $i < $content->length; $i += 2) {
            $author = $content->item($i)->nodeValue;
            $duanzi = self::jokeHandle($content->item($i + 1)->nodeValue);//段子处理
            if (empty($duanzi['joke'])) continue;
            $data[$count]['author'] = $author;
            $data[$count]['content'] = $duanzi['joke'];
            $data[$count]['tag_num'] = $duanzi['tag_num'];
            $count++;
        }

        if ($data) {
            $arrInContent = array_column($data, 'content');//所有作者组
            $existsJokes = Db::table('joke')->where(['content' => ['in', $arrInContent]])->field('content')->select();
            if ($existsJokes) {//不存在数据库
                foreach ($data as $key => &$dat) {//去除存在数据库的数据
                    foreach ($existsJokes as $k => $value) {//处理数组为 in_array()函数调用
                        if ($dat['content'] == $value['content']) {
                            unset($data[$key]);
                            break;
                        }
                    }
                }
            }

            if ($data) {//保存数据
                $saveData = array_values($data);//重置数组索引 从0开始
                $res      = JokeModel::insertJokeAll($saveData);
                if($res) return $saveData;
            }

            return ;
        }
    }

    /**
     * 段子处理
     * @param $joke
     * @return array
     */
    private function jokeHandle($joke)
    {
        $joke = trim_all($joke);
        $data = ['joke' => ''];
        if (strpos($joke, '@段子')) {
            $arr = explode('@段子', $joke);
            $data['tag_num'] = str_replace('#', "", $arr[0]);//段子标记
            $data['joke'] = $arr[1];//段子内容
        }
        return $data;
    }
}