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

        $goods_list = db('goods')->alias('a')
            ->join('user b','a.user_id = b.user_id')
            ->join('category_01 c','a.category_id = c.id')
            ->field('a.goods_id,goods_title,goods_num,nick_name,c.title,price,a.add_time,goods_status')
            ->order('add_time desc')
            ->paginate(50);

        return view('',['goods' => $goods,'admin' => $admin,'user' => $user, 'goods_list' => $goods_list]);
    }

}
