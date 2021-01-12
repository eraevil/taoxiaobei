<?php
namespace app\san\controller;

use think\Controller;
use think\Loader;
use think\Request;

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
            'ip' => $_SERVER['REMOTE_ADDR'],
            'operate' => $content,
            'time' => time(),
        );
        db('system_log')->insert($msg);
    }


    // 初始化测试数据-用户数据
    public function init_user_list()
    {
        //限制大小
        ini_set('memory_limit','1024M');
        //引入类文件
        Loader::import('PHPExcel.Classes.PHPExcel');
        //实例化上传类
        $excelobj = new \PHPExcel();
        //接收文件
        $file = $_FILES['excel'];
        //获取后缀名并改为小写
        $extension = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
        //判断Excel版本
        if ($extension=='xlsx'){
            $readobj = \PHPExcel_IOFactory::createReader('excel2007');
        }else{
            $readobj = \PHPExcel_IOFactory::createReader('excel5');
        }
        //读取数据并处理
        $res = $readobj->load($file['tmp_name']);
        $arr = $res->getSheet(0)->toArray();

        //执行添加
        for ($i = 0; $i < 60 ; $i++){
            $data['user_num'] = 'TXB' . rand(1999, 2022) . '0' . rand(0, 9) . rand(10, 30) . time();
            $data['open_id'] = 'oi2ha5IVK77lzWkmKafKBWrtHUdg';
            $data['session_key'] = 'czED6OIxZKoYbVaMRQrSWQ==';
            $data['thr_session'] = '1f6e91343d7d51911670646b24c49256';
            $data['nick_name'] = $arr[$i][0];
            $data['user_sex'] = rand(1, 2);
            $data['user_headimg'] = $arr[$i][1];
            $data['add_time'] = time();
            $data['trade_status'] = 1;
            $data['school_id'] = 1;
            $data['user_intro'] = "每个时代都有每个时代的精神。我曾经讲过，实现中国梦必须走中国道路、弘扬中国精神、凝聚中国力量。核心价值观是一个民族赖以维系的精神纽带，是一个国家共同的思想道德基础。精神特质、精神脉络。";

            $info = db('user')
                ->insert($data);
        }

        return 1;
    }


    // 初始化测试数据-商品数据
    public function init_goods_list()
    {
        //限制大小
        ini_set('memory_limit','1024M');
        //引入类文件
        Loader::import('PHPExcel.Classes.PHPExcel');
        //实例化上传类
        $excelobj = new \PHPExcel();
        //接收文件
        $file = $_FILES['excel'];
        //获取后缀名并改为小写
        $extension = strtolower(pathinfo($file['name'],PATHINFO_EXTENSION));
        //判断Excel版本
        if ($extension=='xlsx'){
            $readobj = \PHPExcel_IOFactory::createReader('excel2007');
        }else{
            $readobj = \PHPExcel_IOFactory::createReader('excel5');
        }
        //读取数据并处理
        $res = $readobj->load($file['tmp_name']);
        $arr = $res->getSheet(0)->toArray();

        //执行添加
        for ($i = 5; $i < 8991 ; $i++){
            $data['goods_title'] = $arr[$i][0];
            $data['goods_num'] = 'TXB' . rand(1999, 2022) . '0' . rand(0, 9) . rand(10, 30) . rand(1000,9999);
            $data['user_id'] = rand(10,67);
            $data['category_id'] = $arr[$i][3];
            $data['price'] = $arr[$i][2];
            $data['new_old_index'] = 0.75;
            $data['school_id'] = 1;
            $data['img'] = $arr[$i][1];
            $data['add_time'] = time();
            $data['goods_status'] = 1;

            $info = db('goods')
                ->insert($data);
        }

        return 1;
    }



}
