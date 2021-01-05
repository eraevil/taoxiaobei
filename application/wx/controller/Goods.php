<?php

namespace app\wx\controller;
use think\Controller;

class Goods extends Common
{
    /**
     * @Purpose:
     * 首页商品信息的展示
     * @Method Name: Null
     *
     * @Param: category_id 商品的类型
     *
     * @Creater: Lisheng
     *
     * @Author:
     *
     * @Return: goods 返回给前端的商品信息
     */
    public function getGoodsList()
    {
        //查询 当前用户所在的市场 状态正常 的商品
        //category_id若未传，查询推荐商品
        $data['category_id'] = '';
        if(request()->isPost()) {
            $data = input('post.');
            if($data){
                $category_id = $data['category_id'];
                $goods = db('goods')
                    ->where(['school_id' => session('school_id'),
                        'goods_status' => 1,
                        'category_id' => $category_id])
                    ->paginate(20, false, ['query' => request()->param()]);
            }else{
                $goods = db('goods')
                    ->where(['school_id' => session('school_id'),
                        'goods_status' => 1])
                    ->paginate(20, false, ['query' => request()->param()]);
            }
        }

        if($goods){
            ajaxmsg("成功获取商品信息",200,$goods);
        }else{
            ajaxmsg("获取商品信息失败",500);
        }
    }

    /**
     * @Purpose:
     * 搜索商品
     * @Method Name: Null
     *
     * @Param: key 搜索键入关键字
     *
     * @Creater: Lisheng
     *
     * @Author: PaoPao
     *
     * @Return: goods 返回给前端的商品信息
     */
    public function searchGoodsList(){
        // 根据参数key检索数据库中的goods表
        // 将名字、关键字、描述中存在该内容的商品信息返回给前端
        // 后面根据情况会改一下返回的值（返回所有信息不安全）
        if (request() -> isPost()){
            $search = input('key');
            $where['goods_title|goods_intro|key_words'] = array('like', '%' . $search . '%');
            $info = db('goods')
                ->where($where)
                ->paginate(20, false, ['query' => request()->param()]);
            if ($info){
                ajaxmsg("成功查询到商品信息",200,$info);
            }else{
                ajaxmsg("没有查询到商品信息",202);
            }
        }
    }

    /**
     * @Purpose:
     * 查看商品详细信息
     * @Method Name: Null
     *
     * @Param: goods_id 商品id
     *
     * @Creater: Lisheng
     *
     * @Author: PaoPao
     *
     * @Return: good_info 返回给前端的商品信息
     */
    public function getGoodsInfo(){
        // 根据参数goods_id检索数据库中的goods表
        // 将字段匹配的商品信息返回给前端
        if (request() -> isPost()){
            $goods_id = input('goods_id');
            $info = db('goods')
                ->where(['goods_id' => $goods_id])
                ->find();
            if ($info){
                ajaxmsg("成功查询到商品信息",200,$info);
            }else{
                ajaxmsg("没有查询到商品信息",202);
            }
        }
    }

    /**
     * @Purpose:
     * 立即购买，创建一笔订单
     * @Method Name: Null
     *
     * @Param: goods_id 商品id
     * @Param: user_id 买家id
     * @Param: take_goods_name 收货人姓名
     * @Param: trade_time 交易时间
     * @Param: trade_phone 联系电话
     * @Param: trade_place 交易地址
     * @Param: trade_mark 备注
     *
     * @Creater: Lisheng
     *
     * @Author: PaoPao
     *
     * @Return: code 订单创建成功返回200
     */
    public function createTrade(){
        // 当用户点击立即购买时创建一笔订单
        // 创建成功后，前端调用确认支付进行支付
        // 对于交易金额需要进行修改（调整平台抽成比例，目前暂定死为5%）
        if (request() -> isPost()){
            $date = input('post.');
            $price = db('goods')
                ->where(['goods_id' => $date['goods_id']])
                ->field('price')
                ->find();
            $date['trade_money'] = $price['price'] * 1.05;   //交易金额(需要修改)
            $date['add_time'] = time();
            $date['trade_num'] = "DD" . date('Ymd',$date['add_time']) . rand(1000,9999);

            $info = db('trade')
                ->insert($date);
            if($info)
                ajaxmsg("成功创建订单",201);
            else
                ajaxmsg("创建订单失败",500);
        }
    }





}