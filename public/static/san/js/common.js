

/*
* 删除和恢复操作
* 需要传入的值:
* obj:点击的按钮
* cont:提示的内容
* title:提示的标题
* url:ajax地址
* */
function delRec(obj,cont,title,url) {
    $(obj).click(function(e) {
        e.stopPropagation();//阻止冒泡
        var id = $(this).attr('data-id')
        layer.confirm(cont, {
            btn: ['确定', '取消'], //按钮
            title: title //按钮
        }, function(index) {
            $.ajax({
                url: url,
                data: {
                    id: id
                },
                dataType: "json",
                method: "POST",
                success: function(res) {
                    if(res.code==10000){
                        layer.msg(res.msg);
                        setTimeout(function() {
                            window.location.reload()
                        }, 1500)
                    }else{
                        layer.msg(res.msg);
                    }
                }
            })
        });
    });
}