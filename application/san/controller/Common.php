<?php
namespace app\san\controller;

use think\Controller;

class Common extends Controller
{
    public function _initialize()
    {
        if (!session('id') || !session('name')) {
            $this->redirect('Login/index');
        }
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
