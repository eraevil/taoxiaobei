<?php

namespace app\wx\controller;

use app\common\model\User as UserModel;
use think\cache\driver\Redis;
use think\Controller;

class Login extends Controller
{
    public function getSession(){
        if (request()->isPost()) {
            $param = input('post.');
            $code = $param['code'];
            $encrypteData = $param['encrypteData'];
            $iv = $param['iv'];
            $params['appid'] = 'wx272b6c1cd89810d1';
            $params['secret'] = '5caf46ad2a1f717f5ec5a9c9da738086';
            $params['grant_type'] = 'authorization_code';
            $params['js_code'] = define_str_replace($code);
            $res = httpCurl('https://api.weixin.qq.com/sns/jscode2session', $params, 'GET');

            $res = json_decode($res, true);

            $ret = $this->decryUser($iv,$res['session_key'],$encrypteData,$params['appid']);
            $ret = json_decode($ret, true);
            
            $thr_session = md5($res['openid'].$res['session_key']);
            // user open_id session_key nick_name avatar_url thr_session

            $isset = db('user')->where(['open_id' => $res['openid']])->find();
            if($isset){
                ajaxmsg('成功',10000,$isset['thr_session']);
            }else{
                $data = [
                    'nick_name' => $ret['nickName'],
                    'user_headimg' => $ret['avatarUrl'],
                    'open_id' => $res['openid'],
                    'session_key' => $res['session_key'],
                    'thr_session' => $thr_session,
                    'add_time' => time(),
                    'user_sex' => $ret['gender']
                ];

                $data['user_num'] = "TXB" . date('YmdHis',$data['add_time']) . rand(1000,9999);

                $insert = db('user')->insert($data);

                if($insert){
                    ajaxmsg('成功',10000,$thr_session);
                }else{
                    ajaxmsg('失敗',10002);
                }
            }

        }
    }

    // 解密
    private function decryUser($iv,$sessionKey,$encrypteData,$appid){
        import('wxBizDataCrypt',EXTEND_PATH);

        $pc = new \WXBizDataCrypt($appid, $sessionKey);
        $errCode = $pc->decryptData($encrypteData, $iv, $data);

        return $data;
    }
	
	
	/**
     * @Purpose:
     * 更新学校信息
     * @Method Name: Null
     *
     * @Param: school_id 学校id
     * @Param: thr_session 用户三方凭证
     *
     * @Creater: Lisheng
     *
     * @Author: Lisheng
     *
     * @Return: school_info 合法学校信息
     */
    public function chooseSchool(){
        if (request()->isPost()) {
            $data = input('post.');
            if($data){
                $school_id = $data['school_id'];
                $update = db('user')->where(['thr_session' => $data['thr_session']])
                    ->update(['school_id' => $school_id]);
                if($update){
                    ajaxmsg('绑定学校信息成功',201);
                }else{
                    ajaxmsg('绑定学校信息失败',202);
                }
            }
        }
        $school_info = db('school')->field('school_id,school_name')->where(['goods_status' => 1])->paginate(20, false, ['query' => request()->param()]);
        if($school_info){
            ajaxmsg("成功获取学校信息",200,$school_info);
        }else{
            ajaxmsg("获取学校信息失败",202);
        }
    }

}