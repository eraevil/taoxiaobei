<?php

namespace app\wx\controller;

use think\Controller;
//use app\common\model\User as UserModel;
use think\Request;

class Common extends Controller
{
    public $user=array();
    public $param=array();

    public function _initialize()
    {
        $this->param=input('');
        session('thr_session','1f6e91343d7d51911670646b24c49256');
//        $this->checkStatus(); // 用户信息完整度核验
//        $this->checkLogin();//校验登录
    }

    public function checkStatus(){
        $user = db('user')->where(['thr_session' => session('thr_session')])->find();
        session('school_id',$user['school_id']);
        if($user['fill'] == 0){
            ajaxmsg("用户信息未完善！",403);
        }
    }


    /**
     * 校验登录
     * @return bool|void
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function checkLogin()
    {
        $request = Request::instance();
        $controller= $request->controller();  //获取控制器
        $action= $request->action();  //获取控制器
        $url=$controller.'/'.$action;
        $controllerarr=array();//免登陆
        if(in_array($url,$controllerarr)){
            return true;
        }
        $token =Request::instance()->header('token');
        if(!$token){
            return ajaxmsg('你的登录过期,请重新登录!', 10001);
        }
        $user=UserModel::where(['token'=>$token])->find();
        if(!$user){
            return ajaxmsg('你的登录过期,请重新登录!', 10001);
        }
//        if($user['state'] ==3){
//            return ajaxmsg('此帐号已被注销', 10002);
//        }
//        if($user['affect_time']!=0 && $user['affect_time']<time()){
//            return ajaxmsg('此帐号生效时间还未到', 10002);
//        }
//        if($user['effective_time']!=0 && $user['effective_time']>time()){
//            return ajaxmsg('此帐号有效期已过', 10002);
//        }
        $this->user=$user;
        return true;
    }

}
