<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/11/17
 * Time: 15:20
 */
namespace app\wx\controller;
use think\Controller;

class Personal extends Controller
{
    /**
     * @Purpose:
     * 个人板块首页头像显示
     * @Method Name: Null
     *
     * @Param: user_id          用户id
     * @Creater: PaoPao
     *
     * @Author: PaoPao
     *
     * @Return: $info    返回头像，昵称
     */
    public function index()
    {
        if (request() -> isPost()){
            $user_id = input('user_id');
            $info = db('user')
                ->where(['user_id' => $user_id])
                ->field('user_headimg')
                ->find();
            if($info)
                ajaxmsg("返回查询信息",200,$info);
            else
                ajaxmsg("没有查询到信息",202);
        }
    }

    /**
     * @Purpose:
     * 查看我的交易
     * @Method Name: Null
     *
     * @Param: user_id          用户id
     * @Param: goods_status     商品状态
     * @Param: trade_status     订单状态
     * @Param: judge            查看作为卖家的消息还是作为买家的消息(1：卖家 2：买家)
     * @Creater: PaoPao
     *
     * @Author:
     *
     * @Return: statusCode 200
     */

    public function myTransaction()
    {
        //判断judge值进行对应的操作
        //通过user_id查询到所属school_id,再通过两个id及judge值,XXX_status值进行查询
        $date = input('post.');
        //judge == 1
        $info = db('goods')->select();
        //judge == 2
        $info = db('trade')->select();
        if($info)
            ajaxmsg("返回查询信息",200);
        else
            ajaxmsg("查询信息失败",500);
    }

}