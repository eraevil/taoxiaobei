<?php
/**
 * Created by PhpStorm.
 * User: nx
 * Date: 2019/7/11
 * Time: 22:42
 */
namespace app\api\controller;
use think\Controller;
use think\Request;
// 制定允许其他域名访问
header("Access-Control-Allow-Origin:*");
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with, content-type');
class Common extends Controller
{
    protected function _initialize()
    {
        session('openid','oI4sz5uP-GdO6C1Zw_5aOsCVRtns');
    }
}