<?php
namespace app\index\controller;

use think\Controller;
use DOMDocument;
use DOMXPath;

class Index extends Controller
{
    public function index( )
    {

        $target_url = "http://jandan.net/ooxx/page-47#comments";
        $header[] = "X-Client-ID:7e43c50781295f355";
        $header[] = "X-Access-Token:4dc049e83308fe6c66ee08a1833577f90298bcec3dca66cc1d20";
        $curl = curl_init((string)$target_url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_TIMEOUT, (int)20);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //添加自定义的http header
        $html = curl_exec($curl);

        if (!$html) {
            echo "<br />cURL error number:" .curl_errno($curl);
            echo "<br />cURL error:" . curl_error($curl);
            exit;
        }
//创建一个DomDocument对象，用于处理一个HTML
        $dom = new DOMDocument();
//从一个字符串加载HTML
        @$dom->loadHTML($html);
//使该HTML规范化
        $dom->normalize();

//用DOMXpath加载DOM，用于查询
        $xpath = new DOMXPath($dom);
//*[@id="comment-3927606"]/div/div/div[2]/p/img //*[@id="comments"]/ol
        $hrefs = $xpath->evaluate("//*[@id=\"comments\"]/ol/li/div/div/div[2]/p");

        for ($i = 0; $i < $hrefs->length; $i++) {
            $href = $hrefs->item($i);
            $linktext = $href->nodeValue;
//            $linkUrl = $href->getAttribute("href");
//            return $linktext;
           $url= base64_decode($linktext);
//            echo '<div><img src='.$url.'/> </div>';
            echo '<br/>';
        }
//        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }

    public function hello( )
    {
        $name= request()->get("name");
        if(!is_null($name)){
            $this->assign("name",$name);
        }else{
            $this->assign("name",'CodeJies');
        }
        return $this->fetch();
//        return '<style type="text/css">*{ padding: 0; margin: 0; } .think_default_text{ padding: 4px 48px;} a{color:#2E5CD5;cursor: pointer;text-decoration: none} a:hover{text-decoration:underline; } body{ background: #fff; font-family: "Century Gothic","Microsoft yahei"; color: #333;font-size:18px} h1{ font-size: 100px; font-weight: normal; margin-bottom: 12px; } p{ line-height: 1.6em; font-size: 42px }</style><div style="padding: 24px 48px;"> <h1>:)</h1><p> ThinkPHP V5<br/><span style="font-size:30px">十年磨一剑 - 为API开发设计的高性能框架</span></p><span style="font-size:22px;">[ V5.0 版本由 <a href="http://www.qiniu.com" target="qiniu">七牛云</a> 独家赞助发布 ]</span></div><script type="text/javascript" src="http://tajs.qq.com/stats?sId=9347272" charset="UTF-8"></script><script type="text/javascript" src="http://ad.topthink.com/Public/static/client.js"></script><thinkad id="ad_bd568ce7058a1091"></thinkad>';
    }
}
