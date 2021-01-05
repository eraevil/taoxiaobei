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
            ->order('user_id')
            ->field('user_id,user_num,nick_name,user_sex,school_name,user_phone,trade_status,add_time')
            ->paginate(15);

        return view('',['user' => $user]);
    }

    public function details()
    {
        return view('User/details',[]);
    }


}