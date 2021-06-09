<?php

namespace app\wx\controller;
use think\Controller;

class Index extends Common
{
    public function test(){

        if(request()->isPost()) {
            $param = input('post.');

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

                foreach ($user_list as $ul){
                    if($ul['user_id'] != $user_id){ // 计算与非己用户的相似度
                        $similarity_index = 0;
                    }
                }
            }

        }
    }
}