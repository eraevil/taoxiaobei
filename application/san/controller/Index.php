<?php
namespace app\san\controller;

class Index extends Common
{
    public function index()
    {
        //研究院动态
        $category1 = db('category_01')->count();
//        //研究员风采
//        $news2 = db('content')->where(['is_del' => 1,'column_id' => 2])->count();
//        //精选资讯
//        $handpick  = db('content')->where(['is_del' => 1,'column_id' => 3])->count();
//
//        //最新文章
//        $newest_content = db('content')->where('is_del',1)->order('ctime desc')->limit(8)->select();;
//        $hot_content    = db('content')->where('is_del',1)->order('reading_number desc')->limit(8)->select();;
//        return view('',['news1'=>$news1,'news2'=>$news2,'handpick'=>$handpick,'newest_content'=>$newest_content,'hot_content'=>$hot_content]);
        return view('',['category1' => $category1]);
    }
}
