<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2021/5/11
 * Time: 9:41
 */


namespace app\wx\controller;
use think\Controller;

class Trade extends Common
{
    // 按订单状态获取列表
    public function getTradeListByStatus(){
        if(request()->isPost()) {
            $param = input('post.');
            $whereOr = [];
            $status = $param['status'];

            $user = db('user')->where(['thr_session' => $param['thr_session']])->field('user_id')->find();
            $id = $user['user_id'];

            if($param['status'] == 0){
                $whereOr = 'trade_status=3 AND a.user_id='.$id;
            }

            $trades = db('trade')->alias('a')
                ->join('goods b','a.goods_id = b.goods_id')
                ->where(['a.user_id' => $id])
                ->where(['trade_status' => $status])
                ->whereOr($whereOr)
                ->field('a.id,b.goods_title,b.price,b.img,trade_status,a.add_time')
                ->order('a.add_time desc')
                ->select();

            foreach($trades as $key => $trade) {
                $trades[$key]['add_time'] = date('Y-m-d',$trade['add_time']);
            }

            if($trades){
                ajaxmsg("成功获取商品信息",200,$trades);
            }else{
                ajaxmsg("获取商品信息失败",500);
            }
        }


    }

    // 获取订单详情
    public function getTradeDetail(){
        if(request()->isPost()) {
            $param = input('post.');

            $trade = db('trade')->alias('a')
                ->join('goods b','a.goods_id = b.goods_id')
                ->join('user c','b.user_id = c.user_id')
                ->where(['a.id' => $param['trade_id']])
                ->field('a.cal_money,a.goods_id,a.user_id,a.id,a.trade_num,b.goods_title,b.goods_num,c.nick_name,b.price,a.trade_status,a.add_time as trade_time, a.pay_time,a.finish_time,a.consignee_name,a.consignee_phone,a.consignee_address,a.consignee_remark,b.add_time as goods_time')
                ->find();
            $buyer = db('user')->where(['user_id' => $trade['user_id']])->find();
            $trade['buyer'] = $buyer['nick_name'];
            $trade['trade_time'] = date('Y-m-d H:i:s',$trade['trade_time']);
            $trade['goods_time'] = date('Y-m-d H:i:s',$trade['goods_time']);
            $trade['pay_time'] = date('Y-m-d H:i:s',$trade['pay_time']);
            $trade['finish_time'] = date('Y-m-d H:i:s',$trade['finish_time']);

            if(true){
                ajaxmsg("成功获取订单信息",200,$trade);
            }else{
                ajaxmsg("获取订单信息失败",500);
            }


        }
    }

    // 取消订单
    public function cancelTrade(){
        if(request()->isPost()) {
            $param = input('post.');
            $thrsession = $param['thr_session'];
            $trade_id = $param['trade_id'];

            $user = db('user')->where(['thr_session' => $thrsession])->field('user_id')->find();
            $trade = db('trade')->where(['id' => $trade_id])->find();

            if($trade['user_id'] == $user['user_id']){
                $result = db('goods')->where(['goods_id' => $trade['goods_id']])->update(['goods_status' => 1]);
                if($result){
                    $cancel = db('trade')->where(['id' => $trade_id])->update(['trade_status' => 3]);
                }

                if(isset($cancel)){
                    ajaxmsg("成功取消",200);
                }else{
                    ajaxmsg("取消失败",500);
                }
            }else{
                ajaxmsg("无操作权限",500);
            }
        }
    }

    // 收货
    public function finishTrade(){
        if(request()->isPost()) {
            $param = input('post.');
            $thrsession = $param['thr_session'];
            $trade_id = $param['trade_id'];
            $time = time(); // 收货时间

            $user = db('user')->where(['thr_session' => $thrsession])->field('user_id')->find();
            $trade = db('trade')->where(['id' => $trade_id])->find();
            $saler = db('trade')->alias('a')
                ->join('goods b','a.goods_id = b.goods_id')
                ->join('user c','b.user_id = c.user_id')
                ->where(['a.id' => $trade_id])
                ->field('c.user_id,c.money,a.id as trade_id,a.goods_id')
                ->find();
            $money = (float)$saler['money']; // 卖家余额
            $goods_fund = (float)$trade['cal_money']; // 商品结算金额
            $money += $goods_fund;

            if($trade['user_id'] == $user['user_id']){
                if($trade['trade_status'] == 1){
//                    ajaxmsg("卖家",200,$saler['goods_id']);

                    // 下架商品
                    $result3 = db('goods')->where(['goods_id' => $saler['goods_id']])->update(['goods_status' => 3]);
                    // 关闭交易
                    $result1 = db('trade')->where(['id' => $trade_id])->update(['trade_status' => 2,'finish_time' => $time]);
                    // 资金结算
                    $result2 = db('user')->where(['user_id' => $saler['user_id']])->update(['money' => $money]);


                    if($result1 && $result2 && $result3){
                        ajaxmsg("成功收货",200);
                    }else{
                        ajaxmsg("收货失败",500);
                    }
                }else{
                    ajaxmsg("未支付订单",500);
                }

            }else{
                ajaxmsg("无操作权限",500);
            }


        }
    }
}