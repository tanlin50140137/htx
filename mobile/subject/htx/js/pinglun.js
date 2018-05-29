// JavaScript Document




//回复框
$(document).ready(function(e) {
    $(".hfu-but").click(function(){
        var vl=$(this).html();
        if(vl=="回复"){
            $(this).html("取消");
            $(this).css('color','#ff9900');
            $(this).parent().parent().children("div[class=reply-box]").css('display','block')
            
        }else if(vl=="取消"){
            $(this).html("回复");
            $(this).css('color','#333');
            $(this).parent().parent().children("div[class=reply-box]").css('display','none')
        }            
    });
});
