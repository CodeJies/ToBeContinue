<?php
namespace app\jiandan\Controller;
use think\Controller;
use DOMDocument;
use DOMXPath;
/**
 * Created by PhpStorm.
 * User: itapp
 * Date: 2018/8/13
 * Time: 17:27
 */
class Jiandan extends Controller
{

    public function getMeiziList(){
        $pageIndex=$this->request->post('pageIndex');
        $data=array();
        $header[] = "X-Client-ID:7e43c50781295f355";
        $header[] = "X-Access-Token:4dc049e83308fe6c66ee08a1833577f90298bcec3dca66cc1d20";
        $tagetUrl='http://jandan.net/ooxx/page-'.$pageIndex.'#comments';
        $html=doCurlPostRequest($tagetUrl,$data,$header);

        //创建一个DomDocument对象，用于处理一个HTML
        $dom = new DOMDocument();
        //从一个字符串加载HTML
        @$dom->loadHTML($html);
        //使该HTML规范化
        $dom->normalize();
        //用DOMXpath加载DOM，用于查询
        $xpath = new DOMXPath($dom);
        //根據規則抓取妹子圖的數據
        $hrefs = $xpath->evaluate("//*[@id=\"comments\"]/ol/li/div/div/div[2]/p");

        for ($i = 0; $i < $hrefs->length; $i++) {

            $href = $hrefs->item($i);
            $linktext = $href->nodeValue;
            $url= base64_decode($linktext);
            $data= $this->splitData($data,$url);

        }

        if($data!=null){
            return jsonString(array_utf8_encode($data));
        }else{
            return jsonString(null,"请求失败",0);
        }
    }

    public function splitData($data,$str){
        if(strpos($str,'.gif')){
            $content=explode('.gif',$str);
            array_push($data,  str_replace('//','https://',$content[0].'.gif'));
            $this->splitData($data,base64_decode($content[1]));
        }else if(strpos($str,'.jpg')){
            $content=explode('.jpg',$str);
            array_push($data, str_replace('//','https://',$content[0].'.jpg'));
            $this->splitData($data,base64_decode($content[1]));
        }else{
            array_push($data, str_replace('//','https://',$str));
        }
        return $data;
    }


}