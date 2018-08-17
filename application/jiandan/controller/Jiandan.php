<?php

namespace app\jiandan\Controller;

use app\jiandan\Base;
use DOMDocument;
use DOMXPath;
use think\Db;

/**
 * Created by PhpStorm.
 * User: itapp
 * Date: 2018/8/13
 * Time: 17:27
 */
class Jiandan extends Base
{

    public function imgList(){
        $type     = $this->request->get('type',1);

        $dataList = Db::table('img')
            ->where('type = '. $type)
            ->field('id,path,created_at')
            ->order('id desc')
            ->paginate(10)
            ->toArray();

        if(!empty($dataList['data'])){
            return parent::successReturn($dataList);
        }
        return parent::failReturn();
    }

    public function getMeiziList($pageIndex)
    {
        if(is_null($pageIndex)){
            $pageIndex=35;//一共45页。抓取后面10页，从35页开始抓
        }
        if($pageIndex>45){
            return parent::successReturn("抓取成功");
        }
        $tagetUrl = 'http://jandan.net/ooxx/page-' . $pageIndex . '#comments';

        $xpath =$this->getXpath($tagetUrl);
        //根據規則抓取妹子圖的數據
        $hrefs = $xpath->evaluate("//*[@id=\"comments\"]/ol/li/div/div/div[2]/p");

        for ($i = 0; $i < $hrefs->length; $i++) {
            $href = $hrefs->item($i);
            $linktext = $href->nodeValue;
            $url = base64_decode($linktext);
            $src = $this->imagePathHandle($url);
            if ($src) $data[] = $src;
        }
        if ($data) {
            $strPath = implode(',', $data);
            $existsPath = Db::table('img')->where(['path' => ['in', $strPath]])->field('path')->select();
            $i = 0;
            $currentTime = date('Y-m-d H:i:s', time());
            $saveData = array();
            if ($existsPath) {//不存在数据库
                foreach($existsPath as $k => $value){//处理数组为 in_array()函数调用
                    $arrExistsPath[] = $value['path'];
                }
                foreach ($data as $key => &$dat) {//去除存在数据库的数据
                    if (in_array($dat, $arrExistsPath)) { unset($dat); continue; }
                    $saveData[$i]['path'] = $dat;
                    $saveData[$i]['created_at'] = $currentTime;
                    $saveData[$i]['updated_at'] = $currentTime;
                    $i++;
                }
            } else {
                foreach ($data as $item) {//重新构建保存入库数组
                    $saveData[$i]['path'] = $item;
                    $saveData[$i]['created_at'] = $currentTime;
                    $saveData[$i]['updated_at'] = $currentTime;
                    $i++;
                }
            }
            if($saveData){
                $res = Db::table('img')->insertAll($saveData);
                if ($res) {
                    echo"添加成功 页数:".$pageIndex."\n";
//                    return parent::successReturn($data);
                }else{
                    echo"添加失败 页数:".$pageIndex."\n";
                }
            }else{
                echo"添加失败 页数:".$pageIndex."\n";
            }
        }
//        return parent::failReturn();
         return $this->getMeiziList($pageIndex+1);
    }

    public function splitData($data, $str)
    {
        if (strpos($str, '.gif')) {
            $content = explode('.gif', $str);
            array_push($data, str_replace('//', 'https://', $content[0] . '.gif'));
            $this->splitData($data, base64_decode($content[1]));
        } else if (strpos($str, '.jpg')) {
            $content = explode('.jpg', $str);
            array_push($data, str_replace('//', 'https://', $content[0] . '.jpg'));
            $this->splitData($data, base64_decode($content[1]));
        } else {
            array_push($data, str_replace('//', 'https://', $str));
        }
        return $data;
    }


    function imagePathHandle($src)
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

        return ;
    }


    public function getDuanziList(){
        $tagetUrl = 'http://jandan.net/top-duan';
        $xpath =$this->getXpath($tagetUrl);
        //根據規則抓取热门段子的數據
        $content = $xpath->evaluate("//*[@id=\"duan\"]/ol/li/div/div/div[2]/p|//*[@id=\"duan\"]/ol/li/div/div/div[1]/strong");
        for ($i = 0; $i < $content->length; $i+=2) {
            $author = $content->item($i)->nodeValue;
            $duanzi=$content->item($i+1)->nodeValue;
            echo '作者:'.$author."<br/>"."段子:".$duanzi."<br/>";
        }
    }


    //获取Xpath对象
    public function getXpath($url){
        $data=array();
        $header[] = "X-Client-ID:7e43c50781295f355";
        $header[] = "X-Access-Token:4dc049e83308fe6c66ee08a1833577f90298bcec3dca66cc1d20";
        $html = doCurlPostRequest($url, $data, $header);
        //创建一个DomDocument对象，用于处理一个HTML
        $dom = new DOMDocument();
        //从一个字符串加载HTML
        @$dom->loadHTML($html);
        //使该HTML规范化
        $dom->normalize();
        //用DOMXpath加载DOM，用于查询
        $xpath = new DOMXPath($dom);
        return $xpath;
    }

}