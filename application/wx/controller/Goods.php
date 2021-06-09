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
     * @Creater: Lisheng
     *
     * @Author: Lisheng
     *
     * @Return: goods 返回给前端的商品信息
     */
    public function getGoodsList()
    {

        if(request()->isPost()) {
            $param = input('post.');
            $page = $param['page'];
            $page_size = $param['page_size'];

            $where = [];
            if(isset($param['category_id'])){
                $where['category_id'] = $param['category_id'];
            }

            // 列表查询
            $goods = db('goods')->alias('a')
                ->join('user b','a.user_id = b.user_id')
                ->where(['goods_status' => 1])
                ->where($where)
                ->field('a.user_id,goods_id,goods_title as title,price,img as image,nick_name as userName,user_headimg as userImage')
                ->order('a.add_time desc')
                //->paginate($page_size, false, ['query' => request()->param()]);
                ->page($page,$page_size)
                ->select();


            //推荐
            if (isset($param['thr_session'])) {
                $A_Value = 0.9; // 用户相似度阈值

                $thr_session = $param['thr_session'];
                $user = db('user')->where(['thr_session' => $thr_session])->field('user_id')->find();
                $user_id = $user['user_id'];  // 用户id

                $score_user_num = db('score')->group('user_id')->count(); // 参与评分总人数
                $score_num = db('score')->where(['user_id' => $user_id])->count(); // 用户参与评分数量
                $score_matrix = db('score')->field('user_id,goods_id,score')->select(); // 评分矩阵
                $user_list = db('score')->group('user_id')->field('user_id')->select(); // 参与评分的用户列表
                $similarity_list = []; // 相似用户列表

                if($score_user_num >= 2 && $score_num > 100) {
                    foreach ($user_list as $ul) {
                        if ($ul['user_id'] != $user_id) { // 计算与非己用户的相似度
                            $similarity_index = 0;
                        }
                    }
                }
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
            $param = input('post.');
            $page = $param['page'];
            $page_size = $param['page_size'];
            $search = $param['key'];

            $where['goods_title|goods_intro|key_words'] = array('like', '%' . $search . '%');
            $info = db('goods')->alias('a')
                ->join('user b','a.user_id = b.user_id')
                ->where($where)
                ->where(['goods_status' => 1])
                ->field('a.user_id,goods_id,goods_title as title,price,img as image,nick_name as userName,user_headimg as userImage')
                ->order('a.add_time desc')
                ->page($page,$page_size)
                ->select();
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
            $param = input('post.');
            $goods_id = input('goods_id');
            $thrsession = $param['thr_session'];
            $user = db('user')->where(['thr_session' => $thrsession])->field('user_id')->find();  //用户信息

            $info = db('goods')->alias('a')
                ->join('user b','a.user_id = b.user_id')
                ->where(['goods_id' => $goods_id])
                ->field('a.user_id,goods_id,goods_title as title,a.price,img as image,nick_name as userName,user_headimg as userImage,goods_intro,a.add_time')
                ->find();
            $info['add_time'] = date('Y-m-d H:i:s',$info['add_time']);

            if($user['user_id'] == $info['user_id'])$info['display'] = false;
            else $info['display'] = true;

            $score = db('score')->where(['user_id' => $user['user_id'], 'goods_id' => $goods_id])->find();
            if($score){
                $info['score'] = (int)$score['score'] - 1;
            }


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

    // 获取商品分类信息
    public function getCategoryInfo(){
        if (request() -> isGet()){
            $info = db('category_01')
                ->field('id,title')
                ->select();
            if ($info){
                ajaxmsg("成功查询到分类信息",200,$info);
            }else{
                ajaxmsg("没有查询到分类信息",202);
            }
        }
    }


    // 发布新商品
    public function release(){
        if (request() -> isPost()) {
            $param = input('post.');
            $thrsession = $param['session'];

            // 卖家id
            $user = db('user')->where(['thr_session' => $thrsession])->field('user_id,school_id')->find();
            unset($param['session']);
            $param['user_id'] = $user['user_id'];
            $param['school_id'] = $user['school_id'];

            // 审核状态
            $param['goods_status'] = 0;

            $param['add_time'] = time();
            $param['goods_num'] = 'SP' . date('YmdHis', $param['add_time']) . rand(1000, 9999);

            $info = db('goods')->insert($param);

            if ($info) {
                ajaxmsg("成功发布商品", 200);
            } else {
                ajaxmsg("发布商品失败", 202);
            }
        }
    }

    // 创建订单
    public function createOrder(){
        if (request() -> isPost()) {
            $param = input('post.');
            $thrsession = $param['thr_session'];

//          var_dump($param);
            $time = time();
            $user = db('user')->where(['thr_session' => $thrsession])->field('user_id')->find();
            unset($param['thr_session']);

            $data = $param;
            $data['user_id'] = $user['user_id'];
            $data['goods_id'] = $param['goods_id'];
            $data['trade_num'] = "DD" . date('YmdHis',$time) . rand(1000,9999);
            $data['cal_money'] = (float)$data['trade_money'] * 0.99;
            $data['trade_status'] = 0;
            $data['add_time'] = $time;

            $goods_info = db('goods')->where(['goods_id' => $data['goods_id']])->field('goods_status')->find();

            if($goods_info['goods_status'] == 1){
                $createOK = db('trade')->insert($data);
                // 修改商品状态为已卖出
                $update_goods_status = db('goods')->where(['goods_id' => $data['goods_id']])->update(['goods_status' => 2]);
            }else{
                ajaxmsg("商品状态有误", 500);
            }

            $trade_info = db('trade')->where(['goods_id' => $data['goods_id']])->field('id')->find();

            if($createOK && isset($trade_info['id'])){
                ajaxmsg("成功创建订单", 200,$trade_info['id']);
            }
        }
    }

    // 支付
    public function pay(){
        if (request() -> isPost()) {
            $param['post'] = input('post.');
        }
        if (request() -> isGet()) {
            $param['get'] = input('get.');
        }

        var_dump($param);

        if($param){
            ajaxmsg("成功支付商品", 200);
        }

    }

    // 校验密码，完成支付
    public function checkPassword(){
        if (request() -> isPost()) {
            $param = input('post.');
            $pass = $param['password'];
            $user_info = db('user')->where(['thr_session' => $param['thr_session']])->field('user_id,user_password,money')->find();
            $user_pass = $user_info['user_password'];
            $time = time();

            $is_correct = false;

            // 密码校验逻辑
            if($user_info){
                if($pass == '111111' && ($user_pass == '' || $user_pass == '96e79218965eb72c92a549dd5a330112'))
                    $is_correct = true;
                else if(md5($pass) == $user_pass)
                    $is_correct = true;
                else
                    $is_correct = false;

                // 密码正确
                if($is_correct){
                    $trade_info = db('trade')->where(['id' => $param['trade_id']])->field('trade_money')->find();
                    $money = (float)$user_info['money']; // 用户余额
                    $trade_money = (float)$trade_info['trade_money'];// 应付金额

                    if($money >= $trade_money){
                        // 余额充足
                        $money -= $trade_money; // 支付
                        db('user')->where(['user_id' => $user_info['user_id']])->update(['money' => $money]);
                        db('trade')->where(['id' => $param['trade_id']])->update(['trade_status' => 1, 'pay_time' => $time]); // 修改订单状态为已支付
                        ajaxmsg("购买成功",200);
                    }else{
                        ajaxmsg("余额不足",500);
                    }
                }else{
                    ajaxmsg("密码错误",500);
                }
            }
            else{
                ajaxmsg("无用户信息",500);
            }
        }
    }


    // 按商品状态获取列表
    public function getGoodsListByStatus(){
        if(request()->isPost()) {
            $param = input('post.');
            $whereOr = [];
            $status = $param['status'];

            $user = db('user')->where(['thr_session' => $param['thr_session']])->field('user_id')->find();
            $id = $user['user_id'];

            if($param['status'] == 0){
                $whereOr = 'goods_status=4 AND user_id='.$id;
            }

            // 列表查询
            $goods = db('goods')
                ->where(['user_id' => $id])
                ->where(['goods_status' => $status])
                ->whereOr($whereOr)
                ->field('user_id,goods_id,goods_title,price,img,goods_status,add_time')
                ->order('add_time desc')
                ->select();

            foreach($goods as $key => $good) {
                $goods[$key]['add_time'] = date('Y-m-d H:i:s',$good['add_time']);
            }

            if($goods){
                ajaxmsg("成功获取商品信息",200,$goods);
            }else{
                ajaxmsg("获取商品信息失败",500);
            }
        }


    }

    // 评分
    public function score(){
        if(request()->isPost()) {
            $param = input('post.');
            $thrsession = $param['thr_session'];
            $goods_id = $param['goods_id'];
            $param['add_time'] = time(); // 评分时间

            $user = db('user')->where(['thr_session' => $thrsession])->field('user_id')->find();
            unset($param['thr_session']);
            $param['user_id'] = $user['user_id'];

            if($user){
                $score = db('score')->where(['user_id' => $user['user_id'],'goods_id' => $goods_id])->find();
                if($score){
                    $result = db('score')->where(['user_id' => $user['user_id'],'goods_id' => $goods_id])->update($param);
                }else{
                    $result = db('score')->insert($param);
                }
                if($result){
                    ajaxmsg('感谢评分',200);
                }else{
                    ajaxmsg('评分失败',500);
                }
            }else{
                ajaxmsg('无权限操作',500);
            }
        }
    }


}