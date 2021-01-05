<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2020/8/17
 * Time: 16:36
 */
namespace app\san\controller;

use think\Session;

class Admin extends Common
{
    public function index()
    {
        $admin = db('admin')
            ->alias('a')
            ->join('school b','a.manage_school = b.school_id')
            ->where('status','between','1,2')
            ->field('id,name,phone,sex,school_name,status,login_time,login_ip')
            ->order('id')
            ->paginate(15);

        $op = session('id');

        return view('',['admin' => $admin,'op' => $op]);
    }

    // 管理员详情
    public function details()
    {
        $id = input('id');

        $info_status = 'no'; // 不可写状态
        if($id == session('id') && session('id') != 1){
            // 非超管自己
            $info_status = 'is_self'; // 设为可写标志
        }
        if($id == session('id') && session('id') == 1){
            // 是超管自己
            $info_status = 'is_super_self'; // 设为可写标志
        }
        if($id != session('id') && session('id') == 1){
            // 是超管非自己
            $info_status = 'is_super'; // 设为可写标志
        }

        // 获得学校列表
        $school_list = db('school')
            ->where('school_id','neq',0)
            ->field('school_id, school_name')
            ->order('school_id')
            ->select();


        // 管理员信息
        $info = db('admin')
            ->alias('a')
            ->join('school b','a.manage_school = b.school_id')
            ->where(['id' => $id])
            ->field('id,name,phone,sex,manage_school,school_name,status')
            ->find();

        $log = db('system_log')->where(['admin_id' => $id])->order('time desc')->paginate(15);

        return view('Admin/details',['info' => $info, 'log' => $log, 'info_status' => $info_status, 'school_list' => $school_list]);
    }

    // 保存信息
    public function save(){
        $data = input();

        $id = $data['id'];
        unset($data['id']);
        $info = db('admin')->where(['id' => $id])->find();

        if(isset($data['password'])){
            $data['password'] = MD5($data['password']);
        }

        $is_set = db('admin')
            ->where(['name' => $data['name']])
            ->where('id','neq',$id)
            ->find();
        if($is_set){
            ajaxmsg('已存在该管理员',10002);
        }

        $result = db('admin')
            ->where(['id' => $id])
            ->update($data);

        if($result){
            $this->admin_log("操作了【" . $info['id']. ' | '. $info['name'] . "】管理员");
            ajaxmsg('保存成功',10000);
        }else{
            ajaxmsg('未作修改',10002);
        }
    }

    // 新增管理员
    public function add(){

//        ajaxmsg('',10000);
        $op = session('id');
        $info = db('school')
            ->where('school_id','neq',0)
            ->field('school_id, school_name')
            ->order('school_id')->select();
        $n = db('school')->where('school_id','neq',0)->count();

        for($i = 0; $i < $n; $i++){
            $admin = db('admin')
                ->where(['manage_school' => $info[$i]['school_id']])
                ->where(['status' => 1])
                ->field('name')
                ->select();
            $info[$i]['admin'] = $admin;
        }

//        dump($info);die;

        $data = input();
        if($data != null){
            $is_set = db('admin')
                ->where(['name' => $data['name']])
                ->find();
            if($is_set){
                ajaxmsg('已存在该用户',10002);
            }
            $data['password'] = MD5($data['password']);
            $data['status'] = 1;
            $data['add_time'] = time();
            $data['type'] = 2;


            $result = db('admin')->insert($data);

            if($result){
                $this->admin_log('新增管理员：' .$data['name']);
                ajaxmsg('新增成功',10000);
            }else{
                ajaxmsg('新增失败',10002);
            }
        }

        return view('Admin/add',['op' => $op, 'info' => $info]);
    }

    public function delete(){

        if(session('id') != 1){
            ajaxmsg('非超管不允许删除管理员',10002);
        }
        $id = input('id');

        $info = db('admin')->where(['id' => $id])->find();

        $result = db('admin')
            ->where(['id' => $id])
            ->update(['status' => 3]);

        if($result){
            $this->admin_log('删除管理员：【'.$info['id'].' | '.$info['name'].'】');
            ajaxmsg('删除成功',10000);
        }else{
            ajaxmsg('删除失败',10002);
        }
    }
}