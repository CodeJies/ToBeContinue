<?php
/**
 * Created by PhpStorm.
 * User: zjt57
 * Date: 2018/8/21
 * Time: 16:35
 */

namespace app\jiandan\logic;

use \app\jiandan\model\Meizi as MeiziModel;
use think\Db;

class Meizi extends MeiziModel
{
    public function pullMeiziList($pageIndex)
    {
        $tagetUrl = 'http://jandan.net/ooxx/page-' . $pageIndex . '#comments';

        $xpath = get_x_path($tagetUrl);
        //根據規則抓取妹子圖的數據
        $hrefs = $xpath->evaluate("//*[@id=\"comments\"]/ol/li/div/div/div[2]/p");

        $data = [];
        for ($i = 0; $i < $hrefs->length; $i++) {
            $href = $hrefs->item($i);
            $linktext = $href->nodeValue;
            $url = base64_decode($linktext);
            $src = self::imagePathHandle($url);
            if ($src) $data[] = $src;
        }

        $res = null;
        if ($data) {

            $strPath = implode(',', $data);
            $existsPath = Db::table('meizi')->where(['path' => ['in', $strPath]])->field('path')->select();

            $i = 0;
            $saveData = array();
            if ($existsPath) {//不存在数据库
                foreach ($existsPath as $k => $value) {//处理数组为 in_array()函数调用
                    $arrExistsPath[] = $value['path'];
                }
                foreach ($data as $key => &$dat) {//去除存在数据库的数据
                    if (in_array($dat, $arrExistsPath)) {
                        unset($data[$key]);
                        continue;
                    }
                    $saveData[$i]['path'] = $dat;
                    $i++;
                }
            } else {
                foreach ($data as $item) {//重新构建保存入库数组
                    $saveData[$i]['path'] = $item;
                    $i++;
                }
            }

            if ($saveData) {
                $res = MeiziModel::insertMeiziAll($saveData);
                if ($res) return $saveData;
            }
        }

        return $res;
    }

    public static function getMeiziList($type)
    {
        $dataList = Db::table('meizi')
            ->where('type = ' . $type)
            ->field('id,path,created_at')
            ->order(['id' => 'desc', 'updated_at' => 'desc'])
            ->paginate(10);

        return $dataList->toArray();
    }

//    public function splitData($data, $str)
//    {
//        if (strpos($str, '.gif')) {
//            $content = explode('.gif', $str);
//            array_push($data, str_replace('//', 'https://', $content[0] . '.gif'));
//            $this->splitData($data, base64_decode($content[1]));
//        } else if (strpos($str, '.jpg')) {
//            $content = explode('.jpg', $str);
//            array_push($data, str_replace('//', 'https://', $content[0] . '.jpg'));
//            $this->splitData($data, base64_decode($content[1]));
//        } else {
//            array_push($data, str_replace('//', 'https://', $str));
//        }
//        return $data;
//    }


    private function imagePathHandle($src)
    {
        $start = strpos($src, '//ww');
        $end = strpos($src, '.gif');
        if ($end) {
            return 'https:' . substr($src, $start, ($end - $start + 4));
        }

        $end = strpos($src, '.jpg');
        if ($end) {
            return 'https:' . substr($src, $start, ($end - $start + 4));
        }

        return;
    }
}