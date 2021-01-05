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
/**
 * @param string $msg
 * @param int $status
 * @param string $data
 * @param string $url
 * @param string $errcode
 */
use JPush\Client;
function ajaxmsg($msg = "", $status = 1, $data = '', $url = '', $errcode = '')
{
    header('Content-type: application/json; charset=utf-8');
    $json['msg'] = $msg;
    $json['code'] = $status;
    $json['data'] = $data;
    $json['url'] = $url;
    if ($errcode) {
        $json['errcode'] = $errcode;
    }
    echo json_encode($json, true);
    exit;
}

///**
// * 日志操作记录
// * @param $content //操作信息
// */
//function admin_log($content)
//{
//    $msg = array(
//        'admin_id' => session('id'),
//        'ip'   => $_SERVER['REMOTE_ADDR'],
//        'operate' => $content,
//        'time' => time(),
//    );
//    db('system_log')->insert($msg);
//}

/**
 * 实例化阿里云oos
 * @return \OSS\OssClient
 * @throws \OSS\Core\OssException
 */
function new_oss()
{
    vendor('aliyuncs.autoload');
    $config = config('aliyun_oss');
    $oss = new \OSS\OssClient($config['KeyId'], $config['KeySecret'], $config['Endpoint']);
    return $oss;
}

/**
 * 上传文件到oss并删除本地文件
 * @param $path
 * @return string
 * @throws \OSS\Core\OssException
 */
function oss_upload($path)
{
    // 获取bucket名称
    $config = config('aliyun_oss');
    // 先统一去除左侧的.或者/ 再添加./
    $oss_path = ltrim($path, './');
    $pa = './' . $oss_path;
    // 实例化oss类
    $oss = new_oss();
    // 上传到oss
    $oss->uploadFile($config['Bucket'], $oss_path, $pa);
    // 如需上传到oss后 自动删除本地的文件 则删除下面的注释
    unlink($oss_path);
    return get_url($path);
}

/**
 * 获取完整网络连接
 * @param  string $path 文件路径
 * @return string       http连接
 */
function get_url($path)
{
    // 如果是空；返回空
    if (empty($path)) {
        return '';
    }
    // 如果已经有http直接返回
    if (strpos($path, 'http://') !== false) {
        return $path;
    }
    $path = ltrim($path, '.');
    return 'jiaqicdn.xyan.cn' . $path;   //这需要换成oss的外网地址
}

//base64 解码(上传到本地)
function base64($img)
{
    $up_dir = ROOT_PATH . 'public' . DS . '/images/base64/';
    $base64_img = trim($img);
    //print_r($base64_img);die;
    preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result);
    $type = $result[2];//ext 后缀名字
    if (in_array($type, array('pjpeg', 'jpeg', 'jpg', 'gif', 'bmp', 'png'))) {
        $new_file = $up_dir . time() . rand(1, 999999) . '.' . $type;
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_img)))) {
            $img_path = str_replace('../../..', '', $new_file);
            $img_pat =  config("url.url") .'/images/base64/' . substr($img_path, strrpos($img_path, '/'));
            $arr_url = $img_pat;
        } else {
            return 400;
        }
    } else {
        //文件类型错误
        return 440;
    }
    return $arr_url;
}

/**
 * 上传图片
 * 裁剪 base64
 * @param $Catalog
 * @return \think\response\Json
 * @throws \OSS\Core\OssException
 */
function upload_img($file)
{
    $up_dir = ROOT_PATH . 'public' . DS . '/images/';
    $base64_img = trim($file);
    //print_r($base64_img);die;
    preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result);
    $type = $result[2];//ext 后缀名字
    if (in_array($type, array('pjpeg', 'jpeg', 'jpg', 'gif', 'bmp', 'png'))) {
        $new_file = $up_dir . time() . rand(1, 999999) . '.' . $type;
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_img)))) {
            $img_path = str_replace('../../..', '', $new_file);
            $img_pat = '/images/' . substr($img_path, strrpos($img_path, '/'));
            $arr_url = $img_pat;
        } else {
            return 400;
        }
    } else {
        //文件类型错误
        return 440;
    }
    if ($arr_url) {
        $ret['data']["src"] = 'http://' . oss_upload($arr_url);
        return $ret['data']["src"];
    }
}

/**
 * @param array $data
 * @return bool
 * 阿里大于短信接口调用
 */
function sendSms($mobile,$templateCode,$data = [])
{
    $params = array();
    // *** 需用户填写部分 ***
    // fixme 必填：是否启用https
    $security = false;
    // fixme 必填: 请参阅 https://ak-console.aliyun.com/ 取得您的AK信息
    $accessKeyId = "LTAIjiqzxHrYh56Z";
    $accessKeySecret = "3RFukmxBzktQ462rCvsL7RTsPH8GiF";
    // fixme 必填: 短信接收号码
    $params["PhoneNumbers"] = $mobile;
    // fixme 必填: 短信签名，应严格按"签名名称"填写，请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/sign
    $params["SignName"] = '摩摩';
    // fixme 必填: 短信模板Code，应严格按"模板CODE"填写, 请参考: https://dysms.console.aliyun.com/dysms.htm#/develop/template
    $params["TemplateCode"] = $templateCode;
    // fixme 可选: 设置模板参数, 假如模板中存在变量需要替换则为必填项
    $params['TemplateParam']=json_encode($data, JSON_UNESCAPED_UNICODE);
    // fixme 可选: 设置发送短信流水号
    $params['OutId'] = "12345";
    // fixme 可选: 上行短信扩展码, 扩展码字段控制在7位或以下，无特殊需求用户请忽略此字段
    $params['SmsUpExtendCode'] = "1234567";
    // 初始化SignatureHelper实例用于设置参数，签名以及发送请求
    $content = req(
        $accessKeyId,
        $accessKeySecret,
        "dysmsapi.aliyuncs.com",
        array_merge($params, array(
            "RegionId" => "cn-hangzhou",
            "Action" => "SendSms",
            "Version" => "2017-05-25",
        )),
        $security
    );
    if($content->Code=='OK'){
        return true;
    }
    return false;
}

function req($accessKeyId, $accessKeySecret, $domain, $params, $security = false, $method = 'POST')
{
    $apiParams = array_merge(array(
        "SignatureMethod" => "HMAC-SHA1",
        "SignatureNonce" => uniqid(mt_rand(0, 0xffff), true),
        "SignatureVersion" => "1.0",
        "AccessKeyId" => $accessKeyId,
        "Timestamp" => gmdate("Y-m-d\TH:i:s\Z"),
        "Format" => "JSON",
    ), $params);
    ksort($apiParams);

    $sortedQueryStringTmp = "";
    foreach ($apiParams as $key => $value) {
        $sortedQueryStringTmp .= "&" . encode($key) . "=" . encode($value);
    }
    $stringToSign = "${method}&%2F&" . encode(substr($sortedQueryStringTmp, 1));

    $sign = base64_encode(hash_hmac("sha1", $stringToSign, $accessKeySecret . "&", true));

    $signature = encode($sign);

    $url = ($security ? 'https' : 'http') . "://{$domain}/";

    try {
        $content = fetchContent($url, $method, "Signature={$signature}{$sortedQueryStringTmp}");
        return json_decode($content);
    } catch (\Exception $e) {
        return false;
    }
}

function encode($str)
{
    $res = urlencode($str);
    $res = preg_replace("/\+/", "%20", $res);
    $res = preg_replace("/\*/", "%2A", $res);
    $res = preg_replace("/%7E/", "~", $res);
    return $res;
}

function fetchContent($url, $method, $body)
{
    $ch = curl_init();
    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    } else {
        $url .= '?' . $body;
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "x-sdk-client" => "php/2.0.0"
    ));
    if (substr($url, 0, 5) == 'https') {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }
    $rtn = curl_exec($ch);

    if ($rtn === false) {
        // 大多由设置等原因引起，一般无法保障后续逻辑正常执行，
        // 所以这里触发的是E_USER_ERROR，会终止脚本执行，无法被try...catch捕获，需要用户排查环境、网络等故障
        trigger_error("[CURL_" . curl_errno($ch) . "]: " . curl_error($ch), E_USER_ERROR);
    }
    curl_close($ch);
    return $rtn;
}


/**
 * 时间处理
 * @param $time
 * @return false|string
 */
function tranTime($time, $type = 0)
{
//    print_r($time);die;
    $htime = date('H:i', $time);
    $dtime = date('Y-m-d', time());
    $t = date('Y-m-d', $time);
    $te = time() - $time;
    if ($te < 60 * 1) {
        $str = '刚刚';
    } elseif ($te < 60 * 60) {
        $min = floor($te / 60);
        $str = $min . '分钟前';
    } elseif (strtotime($dtime) == strtotime($t)) {
        $str = '今天 ' . $htime;
    } elseif ($te < 60 * 60 * 24 * 2) {
        $d = strtotime($dtime) - strtotime($t);
        $day = $d / (60 * 60 * 24);
        if ($day == 1) {
            $str = '昨天 ' . $htime;
        } else {
            $str = '前天 ' . $htime;
        }
    } else {
        if ($type != 0) {
            $str = date('m-d H:i', $time);
        } else {
            $str = date('Y-m-d', $time);
        }
    }
    return $str;
}

/**
 * 发送HTTP请求方法
 * @param  string $url    请求URL
 * @param  array  $params 请求参数
 * @param  string $method 请求方法GET/POST
 * @return array  $data   响应数据
 */
function httpCurl($url, $params, $method = 'POST', $header = array(), $multi = false){
    date_default_timezone_set('PRC');
    $opts = array(
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_HTTPHEADER     => $header,
        CURLOPT_COOKIESESSION  => true,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_COOKIE         =>session_name().'='.session_id(),
    );
    /* 根据请求类型设置特定参数 */
    switch(strtoupper($method)){
        case 'GET':
            // $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
            // 链接后拼接参数  &  非？
            $opts[CURLOPT_URL] = $url . '?' . http_build_query($params);
            break;
        case 'POST':
            //判断是否传输文件
            $params = $multi ? $params : http_build_query($params);
            $opts[CURLOPT_URL] = $url;
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $params;
            break;
        default:
            throw new Exception('不支持的请求方式！');
    }
    /* 初始化并执行curl请求 */
    $ch = curl_init();
    curl_setopt_array($ch, $opts);
    $data  = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);
    if($error) throw new Exception('请求发生错误：' . $error);
    return  $data;
}
/**
 * 微信信息解密
 * @param  string  $appid  小程序id
 * @param  string  $sessionKey 小程序密钥
 * @param  string  $encryptedData 在小程序中获取的encryptedData
 * @param  string  $iv 在小程序中获取的iv
 * @return array 解密后的数组
 */
function decryptData( $appid , $sessionKey, $encryptedData, $iv ){
    $OK = 0;
    $IllegalAesKey = -41001;
    $IllegalIv = -41002;
    $IllegalBuffer = -41003;
    $DecodeBase64Error = -41004;

    if (strlen($sessionKey) != 24) {
        return $IllegalAesKey;
    }
    $aesKey=base64_decode($sessionKey);

    if (strlen($iv) != 24) {
        return $IllegalIv;
    }
    $aesIV=base64_decode($iv);

    $aesCipher=base64_decode($encryptedData);

    $result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
    $dataObj=json_decode( $result );
    if( $dataObj  == NULL )
    {
        return $IllegalBuffer;
    }
    if( $dataObj->watermark->appid != $appid )
    {
        return $DecodeBase64Error;
    }
    $data = json_decode($result,true);

    return $data;
}
/**
 * 请求过程中因为编码原因+号变成了空格
 * 需要用下面的方法转换回来
 */
function define_str_replace($data)
{
    return str_replace(' ','+',$data);
}
