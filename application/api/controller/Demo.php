<?php

namespace app\api\controller;

use think\cache\driver\Redis;
//邮件
use phpmailer\phpmailer;

class Demo extends Common
{
    public $param = array();

    /************加密解密2019/10/15*************/
    //加密
    public function encryption()
    {
        if (request()->isPost()) {
//            $data = array(
//                array(
//                    'uid' => 194732,
//                    'business_id' => 1,
//                    'order_number' => 'D201910111232123',
//                    'price' => 2000,
//                    'pay_time' => '2019-10-12'
//                ),
//                array(
//                    'uid' => 264232,
//                    'business_id' => 2,
//                    'order_number' => 'D2019101112123412',
//                    'price' => 3000,
//                    'pay_time' => '2019-10-12'
//                )
//
//            );
            $data = input('post.');
            // print_r($data);die;
            //加密所传递过来的数据
            $encrypt = encrypte(json_encode($data, true));
            //判断是否存在token
            $token = !empty($data['token']) ? $data['token'] : '';
            //MD5加密所传递过来的数据和token
            $sign = md5(md5($encrypt) . $token);
            //数组组装
            $arr = array(
                'security' => [
                    'sign' => $sign,
                    'token' => $token,
                ],
                'param' => $encrypt,
            );
            if (!empty($arr)) {
                ajaxmsg('数据返回成功', 10000, $arr);
            } else {
                ajaxmsg('数据返回成功', 10000, []);
            }
        }
    }

    /**
     * 检查签名
     * @return bool
     */
    private function checkSign()
    {
        $security = json_decode(input('security'), true);
        $token = $security['token'] ? $security['token'] : '';
        $sign = $security['sign'] ? $security['sign'] : '';
        $param = input('param');
        $sign_encrypt = md5(md5($param) . $token);
        if ($sign != $sign_encrypt) {
            return false;
        }
        $this->param = json_decode(decrypte($param), true);
        return true;
    }

    /**
     * 测试接口 解密数据
     */
    public function decrypt()
    {
        if (!$this->checkSign()) {//检查签名
            ajaxmsg('操作失败', 10002);
        } else {
            ajaxmsg('返回数据成功', 10000, $this->param);
        }
    }


    /************发送极光推送2019/10/15*************/
    public function send_jpush()
    {
        customer_send('千千你是猪？', '', 1, 1);
    }


    /************发送短信2019/10/15*************/
    public function send_sms()
    {
        if (request()->isPost()) {
            $mobile = trim(input('request.mobile'));
            if (strlen($mobile) < 11) {
                ajaxmsg('请输入11位手机号!', 10002);
            }
            //生成4位验证码
            $code = mt_rand(1000, 9999);
            $result = sendSms(['mobile' => $mobile, 'SignName' => '摩摩', 'TemplateCode' => 'SMS_171930022', 'code' => $code]);
            //   print_r($result);die;
            if (!$result) {
                ajaxmsg('发送失败!', 10002);
            } else {
                //储存验证码
                $mobile = [
                    'mobile' => $mobile,
                    'code' => $code,
                    'ctime' => time(),
                ];
                $redis = new Redis();
                $redis->set('mobile', $mobile);
                ajaxmsg('发送成功', 10000);
            }
        }
    }

    //验证
    //验证码验证(新用户)
    public function new_get_code()
    {
        if (request()->isPost()){
            $code = trim(input('request.code'));
            $mobile = trim(input('request.mobile'));
            $redis = new Redis();
            $infomsg = $redis->get('mobile');
           // print_r($data);die;
            if ($infomsg['code'] != $code) {
                ajaxmsg('验证码错误!', 10002);
            } elseif ($infomsg['mobile'] != $mobile) {
                ajaxmsg('手机号不一致!', 10002);
            } else {
                ajaxmsg('注册成功', 10000);
            }
        }
    }

    /************base64上传到本地2019/10/15*************/
    public function base64()
    {
        $a = upload_img('data:image/jpeg;base64,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');
        print_r($a);
        die;
    }

    /************微信支付订单(JSAPI)2019/10/15*************/
    public function Pay()
    {
        if (request()->isPost()) {
            $order_number = input('order_number');
            //  print_r($order_number);die;
            $total = input('total');
            $payObj = new \wx\Pay();
            $authInfo = session('openid');
            if (!empty($authInfo)) {
                $payInfo = array(
                    'orderSn' => $order_number,
                    'body' => '锁具订单',
                    'fee' => $total,
                    'openId' => $authInfo,
                    'notify_url' => 'http://ydd.siantest.com/wxuser/Pay/notifys' //回调网站
                );
                $payRes = $payObj->toPay($payInfo);
                //  print_r($payInfo);die;
                if ($payRes) {
                    ajaxmsg('请求成功', 10000, $payRes);
                } else {
                    ajaxmsg('接口异常', 10002);
                }
            }
        }
    }

    /************微信支付回调处理(JSAPI)2019/10/15*************/
    public function notifys()
    {
        $xmlData = file_get_contents('php://input');
        libxml_disable_entity_loader(true);
        $data = json_decode(json_encode(simplexml_load_string($xmlData, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        // print_r($data);die;
        if (($data['return_code'] == 'SUCCESS') && ($data['result_code'] == 'SUCCESS')) {
            //逻辑处理
            db('order')->where('order_number', $data['out_trade_no'])
                ->update(['order_status' => 2, 'pay_order_number' => $data['transaction_id'], 'pay_time' => time(), 'pay_way' => 1]);
            $str = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
            echo $str;
        } else {
            $str = '<xml><return_code><![CDATA[FAIL]]></return_code><return_msg><![CDATA[签名失败]]></return_msg></xml>';
            echo $str;
        }
    }

    /************微信支付宝支付（APP）2019/10/15*************/
    public function wx_app()
    {
        if (request()->isPost()) {
            //查询订单信息
//            $order_info = db('order')
//                ->where(['order_id' => input('order_id')])
//                ->field('order_id,member_id,order_number,pay_money,coupon_money')
//                ->find();
            //获取支付方式
            $pay_type = input('pay_type'); //支付方式1微信2支付宝3余额
            //获取支付金额
            $order_info['needpay_money'] = 0.01;
            //订单号
            $order_info['order_number'] = 'MM201908145554';
            //判断支付方式
            switch ($pay_type) {
                case '1';//如果支付方式为支付宝支付
                    //实例化alipay类
                    $ali = new Alipay();
                    //异步回调地址
                    $url = 'http://momo24h.com/customer/Wxpay/alipay_notify';
                    $array = $ali->alipay('客户端APP支付宝支付', '客户端APP支付宝支付', $order_info['order_number'], $order_info['needpay_money'], $url);
                    if ($array) {
                        ajaxmsg('返回成功', 10000, $array);
                    } else {
                        echo json_encode(array('status' => 0, 'msg' => '对不起请检查相关参数!@'));
                    }
                    break;
                case '2';  //如果支付方式为微信支付

                    $wx = new Wxpay();//实例化微信支付控制器

                    $body = '摩摩网络';//支付说明

                    $total_fee = 0.01 * 100;//支付金额(乘以100) $order_info['needpay_money'] * 100

                    $notify_url = 'http://momo24h.com/customer/Wxpay/notify';//回调地址

                    $order = $wx->getPrePayOrder($body, $order_info['order_number'], $total_fee, $notify_url);//调用微信支付的方法

                    if ($order['result_code'] == "SUCCESS") {
                        if ($order['prepay_id']) {//判断返回参数中是否有prepay_id
                            $order1 = $wx->getOrder($order['prepay_id']);//执行二次签名返回参数
                            ajaxmsg('返回成功', 10000, $order1);
                        } else {
                            ajaxmsg('返回失败', 10002, $order['err_code_des']);
                        }
                    } else {
                        ajaxmsg('返回失败', 10002, $order['err_code_des']);
                    }

                    break;
                case '3'; //如果支付方式为余额支付

                    break;
            }
        }
    }

    /************发送邮件2019/10/15*************/
    public function send_email()
    {
        $toemail = '2281801008@qq.com';//收件人的邮箱
        $mail = new PHPMailer();
        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = "smtp.163.com";// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = "18584423196@163.com";// 发送方的163邮箱用户名
        $mail->Password = "NX1999828";// 客户端授权密码
        $mail->SMTPSecure = "ssl";// 使用ssl协议方式
        $mail->Port = 994;// 163邮箱的ssl协议方式端口号是465/994
        $mail->setFrom("18584423196@163.com", "nixin");// 设置发件人信息
        $mail->addAddress($toemail, '收件');// 设置收件人信息
        $mail->addReplyTo("18584423196@163.com", "回复");// 设置回复人信息
        //$mail->addCC("xxx@163.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
        //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)
        $mail->addAttachment("robots.txt");// 添加附件
        $mail->Subject = "邮件发送";// 邮件标题
        $mail->Body = "邮件内容";// 邮件正文
        //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用

        if (!$mail->send()) {// 发送邮件
            echo "邮件发送失败<br><br>";
            echo "邮件错误: " . $mail->ErrorInfo;// 输出错误信息
        } else {
            echo '发送成功';
        }
    }
}
