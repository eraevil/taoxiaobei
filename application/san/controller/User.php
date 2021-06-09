<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2020/8/17
 * Time: 16:36
 */
namespace app\san\controller;

use think\Session;

class User extends Common
{
    public function index()
    {
        $user = db('user')
            ->alias('a')
            ->join('school b','a.school_id = b.school_id')
            ->order('add_time desc')
            ->field('user_id,user_num,nick_name,user_sex,school_name,user_phone,trade_status,add_time')
            ->paginate(50);

        return view('',['user' => $user]);
    }

    public function details()
    {
        $id = input('id');

        $user_info = db('user')
            ->alias('a')
            ->join('school b','a.school_id = b.school_id')
            ->where('user_id','=',$id)
            ->field('user_num,nick_name,user_sex,user_birth,user_phone,school_name,trade_status,add_time,user_headimg,user_intro')
            ->find();

        $goods_info = db('goods')
            ->alias('a')
            ->join('category_01 b','a.category_id = b.id')
            ->where('user_id','=',$id)
            ->field('goods_id,goods_num,goods_title,b.title,price,goods_status')
            ->paginate(50);

        $goods_num = $goods_info->count();


        return view('user/details',['user_info' => $user_info, 'goods_info' => $goods_info, 'goods_num' => $goods_num]);
    }


}