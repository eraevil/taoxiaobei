<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2021/4/5
 * Time: 12:42
 */
namespace app\san\controller;
use think\Controller;

class Goods extends Common
{
    public function index(){

        $type = input('type');

        $info = db('goods')->alias('a')
            ->join('user b','a.user_id = b.user_id')
            ->join('category_01 c','a.category_id = c.id')
            ->where(['goods_status' => $type])
            ->field('a.goods_id,goods_title,goods_num,nick_name,c.title,price,a.add_time,goods_status')
            ->order('add_time desc')
            ->paginate(50);

        return view('',['goods' => $info]);
    }

    // 商品详情
    public function details(){

        $id = input('id');

        $info = db('goods')->alias('a')
            ->join('category_01 b','a.category_id = b.id')
            ->join('user c','a.user_id = c.user_id')
            ->where(['goods_id' => $id])
            ->field('a.goods_id,goods_title,goods_num,nick_name,title,price,new_old_index,img,goods_intro,key_words,a.add_time,remark,goods_status,check_time')
            ->find();

        return view('',['info' => $info]);
    }

    // 商品审核
    public function check(){
        if (request()->isPost()) {
            $param = input('post.');

            $param['check_time'] = time();

            $info = db('goods')->where(['goods_id' => $param['goods_id']])->update($param);

            if($info){
                ajaxmsg('ok',200,$param);
            }else{
                ajaxmsg('failed', 500, $param);
            }
        }
    }
}