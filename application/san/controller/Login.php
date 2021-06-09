<?php
/**
 * author    : KedSan
 * createTime: 2018/9/25 22:19
 */

namespace app\san\controller;

use think\Controller;

class Login extends Controller
{
    public function index()
    {


        if (request()->isPost()) {
            $data = input('post.');
            //验证码判断
            if (!captcha_check($data['verifycode'])) {
                ajaxmsg("验证码错误", 10002);
            };

            $admin = db('admin')->where('name', $data['name'])->find();
            if ($admin) {
                if ($admin['status'] == 1) {
                    if ($admin['password'] == MD5($data['password'])) {

                        session('id', $admin['id']);
                        session('name', $admin['name']);

                        $ip = $_SERVER["REMOTE_ADDR"]; // ip
                        db('admin')->where('id',$admin['id'])->update(['login_ip'=>$ip,'login_time'=>time()]);
                        $this->admin_log( '登录系统');
                        ajaxmsg("登录成功", 10000);

                    } else {
                        ajaxmsg("用户名或密码错误", 10002);
                    }
                } else {
                    ajaxmsg("已禁用，请联系神秘人员lisheng_rc@163.com", 10002);
                }
            } else {
                ajaxmsg("查无此人", 10002);
            }
        }
        return view('login/index');
    }
    //退出登录
    public function logout()
    {
        $this->admin_log( '退出登录');
        session("username", null);
        session('id', null);
        ajaxmsg("退出成功", 10000);
    }

    /**
     * 日志操作记录
     * @param $content //操作信息
     */
    public function admin_log($content)
    {
        $msg = array(
            'admin_id' => session('id'),
            'ip'   => $_SERVER['REMOTE_ADDR'],
            'operate' => $content,
            'time' => time(),
        );
        db('system_log')->insert($msg);
    }

}