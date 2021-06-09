<?php
namespace app\san\controller;

class Index extends Common
{
    public function index()
    {
        //商品、管理员数量
        $goods = db('goods')->count();
        $admin = db('admin')->count();
        $user = db('user')->count();

        $user_list = db('user')
            ->alias('a')
            ->join('school b','a.school_id = b.school_id')
            ->order('add_time desc')
            ->field('user_id,user_num,nick_name,user_sex,school_name,user_phone,trade_status,add_time')
            ->paginate(50);

        return view('',['goods' => $goods,'admin' => $admin,'user' => $user, 'user_list' => $user_list]);
    }

}
