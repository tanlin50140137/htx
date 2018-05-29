/**
 * 流式
 */
var pageTotal=eval($("#pageTotal").val());

$(function(){
	
	var mydiv = $("#dl1")[0];
	
	$(window).scroll(function(){
		var scrollTop = $(this).scrollTop();
		var scrollHeight = $("html,body").height();
		var windowHeight = $(this).height();
		var extss = window.navigator.userAgent;//获取浏览器内核
		var brarray = ['MQQBrowser','UCBrowser'];//手机浏览器	
			
		//菜单栏浮动
		//var upH = $(".headdiv").height()+$(".banner").height()+$(".menudiv").height()+$(".ul-project").height()+65;
		var upH = $(".content-width:eq(1)")[0].offsetTop;
		if (scrollTop >= upH) {
             mydiv.style.position = "fixed";
             mydiv.style.top = "0px";
        }
        else 
        {
             mydiv.style.position = "static";
        }
		
		//$(".ul-learn").append(scrollTop);return;
		
		//判断浏览器
		if(extss.indexOf(brarray[0])>-1)
		{//QQ手机浏览器
			pd_infoapth(scrollTop+windowHeight,scrollHeight-56);
		}
		else if(extss.indexOf(brarray[1])>-1)
		{//UC手机浏览器
			pd_infoapth(scrollTop+windowHeight,scrollHeight-200);
		}
		else
		{//其他浏览器
			pd_infoapth(scrollTop+windowHeight,scrollHeight);
		}									
	});
	//共用体
	function pd_infoapth(sw,sh){
		if(sw >= sh){
			 var i = $("#flag_dt").val();
			 if(page <= pageTotal[i])
			 { 
			　　　$.ajax({
					url:'http://'+window.location.host+'/mobile/index.php',
					type:'POST',
					data:'act=GetRecommended&flag='+i+'&page='+page,
					beforeSend:function(){
						$("#pri_text").show();
					},
					complete:function(){
						$("#pri_text").hide();
					},
					success:function(result){						
						var obj = eval("("+result+")");
						$(".ul-learn").append(obj.rows);
			    	}
				 });
			}
			else
			{
				$("#pri_text").show();
				$("#pri_text").html('<font style="font-size:12px;color:#DDDDDD;">加载完毕</font>'); 
			}	  
			page++;
		}
	}
});