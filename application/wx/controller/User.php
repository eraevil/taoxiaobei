<?php

namespace app\wx\controller;

use app\common\model\User as UserModel;
use think\cache\driver\Redis;
use think\Controller;

class User extends Controller
{
    public function index(){
        ajaxmsg('test 成功');
    }

    /**
     * @Purpose:
     * 完善用户信息
     * @Method Name: Null
     *
     * @Param: nick_name 用户昵称
     * @Param: user_phone 联系电话
     * @Param: user_intro 个人简介
     *
     * @Creater: Lisheng
     *
     * @Author: Lisheng
     *
     * @Return: 成功200 失败500
     */
    public function fillUserInfo()
    {
        if (request()->isPost()) {
            $data = input('post.');
            if ($data) {
                $params['nick_name'] = $data['nick_name'];
                $params['user_phone'] = $data['user_phone'];
                $params['user_intro'] = $data['user_intro'];
                $params['fill'] = 1;

                $isexist = db('user')->where(['nick_name' => $params['nick_name'], 'thr_session' => ['neq', session('thr_session')]])->find();
                if ($isexist) {
                    ajaxmsg('该用户名已存在', 403);
                }

                $update = db('user')->where(['thr_session' => session('thr_session')])->update($params);

                if ($update) {
                    ajaxmsg('完成用户信息维护', 200);
                } else {
                    ajaxmsg('已经接受请求，但未处理完成', 202);
                }

            }
        }
    }
}