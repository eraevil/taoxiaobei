<?php
namespace app\san\validate;

use think\Validate;

class Content extends Validate
{
    protected $rule =   [
//        'column_id'   => 'require',
        'content_title'     => 'require',
    ];

    protected $message  =   [
//        'column_id.require'  => '栏目不能为空',
        'content_title.require'     => '内容标题不能为空',
    ];
}