<?php
namespace app\san\controller;
use think\Db;

class Setting extends Common
{
    //网站设置
    public function website()
    {
        $res = db('webset')->where('id',1)->find();
        if($res == Null)
        {
            if(request()->isPost())
            {
                $data = input('post.');
                $res = db('webset')->insert($data);
                if($res)
                {
                    //user_log('更新','更新'.'网站设置');
                    ajaxmsg("更新成功",10000);
                }
                else
                {
                    ajaxmsg("未做任何修改",10002);
                }
            }
        }
        if(request()->isPost())
        {
            $data = input('post.');
            $res = db('webset')->where('id',$data['id'])->update($data);
            if($res)
            {
                //user_log('更新','更新'.'网站设置');
                ajaxmsg("更新成功",10000);
            }
            else
            {
                ajaxmsg("未做任何修改",10002);
            }
        }
        return view('',['res'=>$res]);
    }

    // 数据备份 页面加载
    public function backup()
    {
        $dir = ROOT_PATH.'/public/static/sql/';
        //SQL文件数组
        $list = [];
        if (is_dir($dir)) {
            $fileList = scandir($dir);
            foreach ($fileList as $key => $val) {
                if ($val == '.' || $val == '..') {
                    continue;
                }
                //文件名称
                $list[$key]['name'] = $val;
                //时间
                $list[$key]['time'] = date('Y-m-d H:i:s', filemtime($dir . '/' . $val));
                //大小
                $filesize = filesize($dir . '/' . $val);
                if ($filesize > 0 && $filesize <= 1024) {
                    $filesize = 1;
                } else {
                    $filesize = ceil($filesize / 1024);
                }
                $list[$key]['size'] = $filesize . 'KB';
            }
            foreach ($list as $key=>$value){
                $time[$key] = $value['time'];
            }
        }
        $list = isset($list)?$list:array();

        return view('',['res'=>$list,'order'=>1]);
    }

    //数据库备份
    public function backups()
    {
        //1.获取数据库信息
        $info = Db::getConfig();
        $dbname = $info['database'];

        //2.获取数据库所有表
        $tables = Db::query("show tables");

        //3、组装头部信息
        header("Content-type:text/html;charset=utf-8");
        $path = ROOT_PATH.'/public/static/sql/';
        $database = $dbname;   //获取当前数据库
        $info  = "-- ----------------------------\r\n";
        $info .= "-- 日期：".date("Y-m-d H:i:s",time())."\r\n";
        $info .= "-- MySQL - 5.5.52-MariaDB : Database - ".$database."\r\n";
        $info .= "-- ----------------------------\r\n\r\n";
        $info .= "SET NAMES utf8;\r\nSET FOREIGN_KEY_CHECKS = 0;\r\n\r\n";

        //4、检查目录是否存在
        if (is_dir($path)) {
            if (is_writable($path)) {
            } else {
                echo '目录不可写'; exit();
            }
        } else {
            mkdir($path,0777,true);
        }

        //5、保存的文件名称
        $url = date('Ymd'). '_taoxiaobei' .'.sql';
        $file_name = $path.$url;
        file_put_contents($file_name, $info, FILE_APPEND);
        //6、循环表，写入数据
        foreach ($tables as $k => $v) {
            $val = $v["Tables_in_$database"];
            $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$val' AND TABLE_SCHEMA='$dbname'";
            $res = Db::query($sql);
            $max_num = Db::table("$val")->order('id desc')->value('id');
            //查询表结构
            $info_table = "-- ----------------------------\r\n";
            $info_table .= "-- Table structure for `$val`\r\n";
            $info_table .= "-- ----------------------------\r\n\r\n";
            $info_table .= "DROP TABLE IF EXISTS `$val`;\r\n";
            if (count($res) < 1) {
                continue;
            }
            $info_table .= "CREATE TABLE `$val` (\n\r\t";
            foreach ($res as $kk => $vv) {
                $info_table .= " `".$vv['COLUMN_NAME']."` ";
                $info_table .= $vv['COLUMN_TYPE'];
                //是否允许空值
                if ($vv['IS_NULLABLE'] == 'NO') {
                    $info_table .= " NOT NULL ";
                }
                //判断主键
                if ($vv['EXTRA']) {
                    $info_table .= " AUTO_INCREMENT ";
                    $key = $vv['COLUMN_NAME'];
                }
                //编码
                if ($vv['CHARACTER_SET_NAME']) {
                    $info_table .= " CHARACTER SET ".$vv['CHARACTER_SET_NAME'];
                }
                //字符集
                if ($vv['COLLATION_NAME']) {
                    $info_table .= " COLLATE ".$vv['COLLATION_NAME'];
                }
                //默认数值
                if ($vv['COLUMN_DEFAULT']) {
                    $info_table .= " DEFAULT ".$vv['COLUMN_DEFAULT'];
                }
                //注释
                if ($vv['COLUMN_COMMENT']) {
                    $info_table .= " COMMENT '".$vv['COLUMN_COMMENT']."',\n\r\t";
                }
            }
            $info_table .= " PRIMARY KEY (`$key`) USING BTREE";
            $info_table .= "\n\r) ENGINE = MyISAM AUTO_INCREMENT $max_num CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;\r\n\r\n";

            //查询表数据
            $info_table .= "-- ----------------------------\r\n";
            $info_table .= "-- Data for the table `$val`\r\n";
            $info_table .= "-- ----------------------------\r\n\r\n";
            file_put_contents($file_name,$info_table,FILE_APPEND);
            $sql_data = "select * from $val";
            $data = Db::query($sql_data);
            $count= count($data);
            if ($count < 1) {
                continue;
            }
            foreach ($data as $key => $value) {
                $sqlStr = "INSERT INTO `$val` VALUES (";
                foreach($value as $v_d){
                    $v_d = str_replace("'","\'",$v_d);
                    $sqlStr .= "'".$v_d."', ";
                }
                //需要特别注意对数据的单引号进行转义处理
                //去掉最后一个逗号和空格
                $sqlStr = substr($sqlStr,0,strlen($sqlStr)-2);
                $sqlStr .= ");\r\n";
                file_put_contents($file_name,$sqlStr,FILE_APPEND);
            }
            $info = "\r\n";
            file_put_contents($file_name,$info,FILE_APPEND);
        }
        ajaxmsg('数据备份成功',10000);
    }

}