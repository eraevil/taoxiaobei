<?php

namespace app\wx\controller;
use think\Controller;

class Release extends Controller
{
    /**
     * @Purpose:
     * 上架物品
     * @Method Name: Null
     *
     * @Param: user_id          用户id
     * @Param: category_id      商品类型
     * @Param: goods_title      商品标题
     * @Param: price            商品价格
     * @Param: goods_intro      商品描述
     * @Param: key_words        关键词(多个关键词以'|'分隔)
     * @Param: new_old_index    商品新旧程度
     * @Param: img              商品图片
     * @Creater: PaoPao
     *
     * @Author: PaoPao
     *
     * @Return: statusCode 201
     */

    public function onShelves()
    {
        if(request() -> isPost()){
            $date = input('post.');
            $date['add_time'] = time();
//            $date['school_id'] = session('school_id');
            $date['school_id'] = 111;
            $date['goods_num'] = "SP" . date('Ymd',$date['add_time']) . rand(1000,9999);
            $info = db('goods')
                ->insert($date);
            if($info)
                ajaxmsg("成功添加商品信息",201);
            else
                ajaxmsg("添加商品信息失败",500);
        }
    }

    /**
     * @Purpose:
     * 发布帖子
     * @Method Name: Null
     *
     * @Param: user_id          用户id
     * @Param: post_category    帖子类型
     * @Param: posts_title      帖子主题
     * @Param: posts_text       帖子正文
     * @Param: post_category    贴子类型 0 闲聊  1 求助  2 咨询
     *
     * @Creater: PaoPao
     *
     * @Author: PaoPao
     *
     * @Return: statusCode 201
     */
    public function releaseNews(){
        if(request() -> isPost()){
            $date = input('post.');
            $date['add_time'] = time();
//            $date['school_id'] = session('school_id');
            $date['school_id'] = 111;
            $info = db('posts')
                ->insert($date);
            if($info)
                ajaxmsg("成功添加帖子信息",201);
            else
                ajaxmsg("添加帖子信息失败",500);
        }
    }
}