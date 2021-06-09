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
    // 获取个人主页信息
    public function getUserInfo(){
        if(request()->isPost()){
            $param = input('post.');

            $thr_session = $param['thr_session'];

            $info = db('user')->alias('a')
                ->join('school b', 'a.school_id = b.school_id')
                ->where(['thr_session' => $thr_session])
                ->field('user_id,nick_name,user_headimg,school_name,money,user_num')
                ->find();
            $release = db('goods')->where(['user_id' => $info['user_id']])->count();
            $saled = db('goods')->where(['user_id' => $info['user_id']])->where(['goods_status' => [['eq',2],['eq',3],['eq',4]]])->count();
            $info['release'] = $release;
            $info['saled'] = $saled;

            if($info){
                ajaxmsg('成功',200,$info);
            }else{
                ajaxmsg('失败',500);
            }
        }
    }

    // 获得充值记录
    public function getRechargeInfo(){
        if(request()->isPost()){
            $param = input('post.');

            $user = db('user')->where(['thr_session' => $param['thr_session']])->field('user_id')->find();
            $id = $user['user_id'];

            $info = db('recharge')->where(['user_id' => $id])->field('add_time,re_money,admin_name')->order('add_time desc')->select();
            foreach($info as $key => $inf) {
                $info[$key]['add_time'] = date('Y-m-d H:i:s',$inf['add_time']);
                $info[$key]['re_money'] = sprintf("%.2f",$inf['re_money']);
            }


            if($info){
                ajaxmsg('成功',200,$info);
            }else{
                ajaxmsg('失败',500);
            }
        }
    }


    // 获取收货信息
    public function getConsigneeInfo(){
        if (request() -> isPost()) {
            $param = input('post.');
            $thr_session = $param['thr_session'];

            $info = db('user')->where(['thr_session' => $param['thr_session']])
                ->field('consignee_name,consignee_phone,consignee_address,consignee_remark,consignee_status')
                ->find();

            if($info){
                ajaxmsg("成功",200,$info);
            }
            else{
                ajaxmsg("失败",500);
            }
        }
    }

    // 更新收货信息
    public function updateConsigneeInfo(){
        if (request() -> isPost()) {
            $param = input('post.');
            $thr_session = $param['thr_session'];

            $info = db('user')->where(['thr_session' => $param['thr_session']])->update($param);
            if($info){
                ajaxmsg("成功",200);
            }
            else{
                ajaxmsg("无更改",500);
            }
        }
    }
}