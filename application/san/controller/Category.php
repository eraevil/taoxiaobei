<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2020/5/25
 * Time: 21:04
 */

namespace app\san\controller;


class Category extends Common
{
    function index(){

        $category01 = db('category_01')->field('id,title,icon,desc')->select();
        return view('',['category01' => $category01]);
    }

    function edit_one(){


        if(input('id') != null){
            $id = input('id');
            $info = db('category_01')->where('id','=',$id)->find();
            return view('category/edit',['info' => $info]);
        }else{

            echo '222';die;
            $data = input();
            $isset = db('category_01')->where('id','=',$data['id'])->update($data);

            var_dump($isset);die;

//            if($isset){
//                return view('',[])
//            }else{
//                $this->error('失败');
//            }
        }

    }
}