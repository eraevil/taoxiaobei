var _path=window.location.pathname;


//var _href=$('.J_menuItem').attr(href);
$('.J_menuItem').each(function(){
    if(_path==$(this).attr('href')){
        $(this).css({'color':'#fff'});
        $(this).parent().parent().addClass('in');
        $(this).parent().parent().parent().addClass('active').siblings().removeClass('active');
    }else{
        _path=_path.replace('/counselor/','');
        _path = _path.split('/')[0];
        var Select=$(this).attr('href');
        Select=Select.replace('/counselor/','');
        Select = Select.split('/')[0];
        if(_path==Select){
            $(this).css({'color':'#fff'});
            $(this).parent().parent().addClass('in');
            $(this).parent().parent().parent().addClass('active');
        }
    }
})
