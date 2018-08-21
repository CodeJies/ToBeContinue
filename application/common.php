<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/*
 * $data 返回的数据 $msg=携带的信息 $code=请求编号  200表示成功 0表示失败
 */
function jsonString($data, $msg='请求成功', $code='200')
{
    $result = ['result' => $data, 'msg' => $msg, 'code' => $code];
//$result='result='.$data.',msg='.$msg.',code='.$code;
    return json($result);
}


function doCurlGetRequest($url, $data = [], $header = [], $timeout = 5){
    if($url == "" || $timeout <= 0){
        return false;
    }
    $url = $url.'?'.http_build_query($data);
    $curl = curl_init((string)$url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_TIMEOUT, (int)$timeout);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //添加自定义的http header
    return curl_exec($curl);
}

function doCurlPostRequest($url, $data = [], $header = [], $timeout = 5){
    if($url == '' || $timeout <=0){
        return false;
    }
    $curl = curl_init((string)$url);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_POST,true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($curl, CURLOPT_TIMEOUT,(int)$timeout);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header); //添加自定义的http header
    return curl_exec($curl);
}

function array_utf8_encode($dat)
{
    if (is_string($dat))
        return utf8_encode($dat);
    if (!is_array($dat))
        return $dat;
    $ret = array();
    foreach ($dat as $i => $d)
        $ret[$i] = array_utf8_encode($d);
    return $ret;
}

//获取Xpath对象
function get_x_path($url)
{
    $data = array();
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

/**
 * 删除空格
 * @param $str
 * @return mixed
 */
function trim_all($str)
{
    $seachArr = array(" ", "　", "\t", "\n", "\r");
    $replaceArr = array("", "", "", "", "");
    return str_replace($seachArr, $replaceArr, $str);
}