<?php
namespace app\san\controller;
use think\Controller;
use think\Session;

class Recharge extends Common
{
    // 充值页面
    public function index(){


        return view('',['user' => null]);
    }


    public function getUserCharge(){
        if(request()->isPost()){
            $param = input('post.');

            $keyword = $param['keyword'];

            $info = db('user')
                ->where(['user_num' => $keyword])
                ->whereOr(['nick_name' => $keyword])
                ->field('user_id,user_num,nick_name,money')
                ->select();

//            dump($info);die;

            return view('recharge/index',['user' => $info]);
        }
    }

    public function recharge(){
        if(request()->isPost()){
            $param = input('post.');

            if($param['money'] == '')return view('recharge/index',['user' => null]);

            // 查询余额
            $info = db('user')
                ->where(['user_id' => $param['id']])
                ->field('money')
                ->find();

            // 充值金额
            $re_money = (float)$param['money'];
            $money = (float)$info['money'] + $re_money;

            // 充值记录
            $data['re_money'] = $re_money;
            $data['user_id'] = $param['id'];
            $data['add_time'] = time();
            $data['admin_name'] = Session('name');

            $do_recharge = db('user')->where(['user_id' => $param['id']])->update(['money' => $money]); // 更新用户余额

            if($do_recharge){
                $insert = db('recharge')->insert($data); // 写入充值记录表
            }

            if($insert){
                $info = db('user')
                    ->where(['user_id' => $param['id']])
                    ->field('user_id,user_num,nick_name,money')
                    ->select();

                $this->admin_log("为用户 " . $info[0]['nick_name'] . " 充值余额 " . $re_money . " 元"); // 管理员操作记录
                return view('recharge/index',['user' => $info]);
            }

            return view('recharge/index',['user' => null]);
        }
    }
}