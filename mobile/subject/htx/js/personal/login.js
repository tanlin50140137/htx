var loadings ='',intval='',i=60;
var relphone =/^0?(13|14|15|17|18)[0-9]{9}$/;
var relemail =/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/;
/**
 * 统一登录
 * */
function showccc()
{
	var html  = '<div class="login-reg-box">';
		html += '<div class="login_bg login_a">';
		html += '<div class="login-tit">';
		html += '<div class="login-titA login-titB" onclick="showLogin();">登录</div>';
		html += '<div class="login-titA regA" onclick="showReset();">注册</div>';
		html += '<div class="login-titC" onclick="hide();">x</div>';
		html += '</div>';
		html += '<form id="frm_name1" class="frm_name">';
		html += '<div class="userName">';
		html += '<input type="text" class="username1" name="username" placeholder="请输入手机号/邮箱"/>';
		html += '</div>';
		html += '<div class="tishi" style="font-size:0.6rem;line-height:25px;margin-top:5px;color:#f33b29;"></div>';
		html += '<div class="passWord">';
		html += '<input type="password" class="password1" name="password" placeholder="请输入密码"/>';
		html += '</div>';
		html += '<div class="tishi2" style="font-size:0.6rem;line-height:25px;margin-top:5px;color:#f33b29;"></div>';
		html += '<div class="choose_box">';
		html += '<div>';
		html += '<input type="checkbox" checked="checked" name="checkbox"/>';
		html += '<lable>七天内自动登录</lable>';
		html += '</div>';
		html += '<a href="http://'+window.location.host+'/mobile/?act=password_call">忘记密码</a>';
		html += '</div>';
		html += '<a href="javascript:void(0);" class="login_btn" onclick="SendLogins();">登录</a>';
		html += '<div class="other_login">';
		html += '<span>QQ号注册的老用户，<a href="http://'+window.location.host+'/mobile/?act=QQLogin_htx&call=http://mf.htxgcw.com/index.php?act=QReturnData_url&flag=1">点此登录</a></span>';
		html += '</div>';		
		html += '</form>';
		html += '<form id="frm_name2" class="frm_name" style="display:none">';
		html += '<div class="userName">';
		html += '<input type="text" class="username" name="username" placeholder="请输入手机号/邮箱"/>';
		html += '</div>';
		html += '<div class="tishi3" style="font-size:0.6rem;line-height:25px;margin-top:5px;color:#f33b29;"></div>';
		html += '<div class="passWord">';
		html += '<input type="password" class="password" name="password" placeholder="6-16位密码，字母区分大小写，不能空格"/>';
		html += '</div>';
		html += '<div class="tishi4" style="font-size:0.6rem;line-height:25px;margin-top:5px;color:#f33b29;"></div>';
		html += '<div class="passWord passWord-a">';
		html += '<input type="text" class="virify" name="virify" placeholder="输入验证码"/>';
		html += '<p class="GetVirify" onclick="GetVirify();">获取验证码</p>';
		html += '<p class="GetVirify" style="display:none;">获取验证码</p>';
		html += '</div>';
		html += '<div class="tishi5" style="font-size:0.6rem;line-height:25px;margin-top:5px;color:#f33b29;"></div>';
		html += '<div class="choose_box">';
		html += '<div>';
		html += '<input type="checkbox" checked="checked" name="checkbox" onclick="checkbox_select(this);"/>';
		html += '<lable>同意 <a href="http://'+window.location.host+'/mobile/?act=RelevantAgreement">《火天信资料库平台服务相关协议》</a></lable>';
		html += '</div>';
		html += '</div>';
		html += '<a href="javascript:void(0);" class="login_btn reset_btn" onclick="SendResets();">注册</a>';
		html += '<a href="javascript:void(0);" class="login_btn reset_btn" style="display:none;background:#989090;">注册</a>';
		html += '</form>';
		html += '</div>';
		html += '</div>';
		loadings = layer.open({
			 className:'popuo-login1'
			,content: html
		});
}
//登录
function showLogin(){
	$(".frm_name").hide();
	$(".frm_name:eq(0)").show();
	$(".login-titA").removeClass('login-titB');
	$(".login-titA:eq(0)").addClass('login-titB');
}
//注册
function showReset(){
	$(".frm_name").hide();
	$(".frm_name:eq(1)").show();
	$(".login-titA").removeClass('login-titB');
	$(".login-titA:eq(1)").addClass('login-titB');
}
//关闭
function hide(){
	layer.close(loadings);
}
//点击登录
function SendLogins(){
	var PhoneAndMail = $(".username1").val();
	if( $(".username1").val() == '' ){
		$(".tishi").html('请输入手机号或邮箱');
		return false;
	}
	$(".tishi").html('');
	if(PhoneAndMail.lastIndexOf('@') == -1){//手机
		if( !isNaN( PhoneAndMail ) ){
			if(!relphone.test(PhoneAndMail)){
				$(".tishi").html('手机号不正确');
				return false;
			}			
		}else{
			$(".tishi").html('请输入手机号或邮箱');
			return false;
		}	
	}else{//邮箱
		if(!relemail.test(PhoneAndMail)){
			$(".tishi").html('邮箱格式不正确');
			return false;
		}
	}
	$(".tishi").html('');
	if( $(".password1").val() == '' ){
		$(".tishi2").html('请输入密码');
		return false;
	}else{
		$(".tishi2").html('');
	}
	var ds = $("#frm_name1").serialize();
	$.ajax({
		url:'http://'+window.location.host+'/mobile/index.php',
		type:'post',
		data:'act=GetLogin&'+ds,
		beforeSend:function(){
			loadings = layer.open({type: 2});
		},
		complete:function(){
			layer.close(loadings);
		},    
		success:function(msg){
			var obj = eval("("+msg+")");
			if( obj.error == 0 ){
				layer.open({
				    content: '登录成功'
				    ,skin: 'msg'
				    ,time: 1 //2秒后自动关闭
				    ,end: function(elem){
				    	location.reload();
				    } 
				});
			}else{
				if( obj.error == 1 ){
					$(".tishi").html(obj.txt);
				}else if( obj.error == 2 ){
					$(".tishi2").html(obj.txt);
				}
			}
		}
	});
}
//点击注册
function SendResets(){
	var PhoneAndMail = $(".username").val();		
	if( $(".username").val() == '' ){
		$(".tishi3").html('请输入手机号或邮箱');
		return false;
	}
	$(".tishi3").html('');
	if(PhoneAndMail.lastIndexOf('@') == -1){//手机
		if( !isNaN( PhoneAndMail ) ){
			if(!relphone.test(PhoneAndMail)){
				$(".tishi3").html('手机号不正确');
				return false;
			}
		}else{
			$(".tishi3").html('请输入手机号或邮箱');
			return false;
		}
	}else{//邮箱
		if(!relemail.test(PhoneAndMail)){
			$(".tishi3").html('邮箱格式不正确');
			return false;
		}
	}
	$(".tishi3").html('');
	if( $(".password").val() == '' ){
		$(".tishi4").html('请输入密码');
		return false;
	}
	$(".tishi4").html('');
	if( $(".virify").val() == '' ){
		$(".tishi5").html('请输入验证码');
		return false;
	}
	$(".tishi5").html('');
	var ds = $("#frm_name2").serialize();
	$.ajax({
		url:'http://'+window.location.host+'/mobile/index.php',
		type:'post',
		data:'act=GetReset&'+ds,
		success:function(msg){
			var obj = eval("("+msg+")");
			if( obj.error == 1 ){//错误
				layer.open({
				     content: obj.txt
				    ,skin: 'msg'
				    ,time: 1
				});
			}else{
				layer.open({
				    content: '注册成功，自动登录，请稍等...'
				    ,skin: 'msg'
				    ,time: 1 //2秒后自动关闭
				    ,end: function(elem){
				    	location.reload();
				    } 
				});
			}
		}
	});
}
//获取验证码
function GetVirify(){
	var PhoneAndMail = $(".username").val();		
	if( $(".username").val() == '' ){
		$(".tishi3").html('请输入手机号或邮箱');
		return false;
	}
	$(".tishi3").html('');
	if(PhoneAndMail.lastIndexOf('@') == -1){//手机
		if( !isNaN( PhoneAndMail ) ){
			if(!relphone.test(PhoneAndMail)){
				$(".tishi3").html('手机号不正确');
				return false;
			}
		}else{
			$(".tishi3").html('请输入手机号或邮箱');
			return false;
		}
		$(".tishi3").html('');		
		//发关短信...
		$.ajax({
			url:'http://'+window.location.host+'/mobile/index.php',
			type:'post',
			data:'act=SendSMS&datas='+PhoneAndMail,
			beforeSend:function(){
				intval = setTimeout(Countdown,1000);
			},
			complete:function(){
				//layer.close(loadings);
			},    
			success:function(msg){
				var obj = eval("("+msg+")");
				if( obj.error == 1 ){
					clearInterval(intval);
					layer.open({
					    content: obj.txt
					    ,skin: 'msg'
					    ,time: 2
					    ,end:function(){					    	
					    	$(".GetVirify").hide(),$(".GetVirify:eq(0)").show();
					    	i = 60;//重置
					    }
					});
				}
			}
		});
	}else{//邮箱
		if(!relemail.test(PhoneAndMail)){
			$(".tishi3").html('邮箱格式不正确');
			return false;
		}
		$(".tishi3").html('');
		//发送邮件...
		$.ajax({
			url:'http://'+window.location.host+'/mobile/index.php',
			type:'post',
			data:'act=SendEmail&datas='+PhoneAndMail,
			beforeSend:function(){
				intval = setTimeout(Countdown,1000);
			},   
			success:function(msg){
				var obj = eval("("+msg+")");
				if( obj.error == 1 ){
					clearInterval(intval);
					layer.open({
					    content: obj.txt
					    ,skin: 'msg'
					    ,time: 2
					    ,end:function(){					    	
					    	$(".GetVirify").hide(),$(".GetVirify:eq(0)").show();
					    	i = 60;//重置
					    }
					});						
				}
				
			}
		});
	}
}
//定时器
function Countdown()
{	
	$(".GetVirify").hide(),$(".GetVirify:eq(1)").show();
	$(".GetVirify:eq(1)").text('已发送（'+i+'）');
	i--;
	if(i<=1){		
		clearInterval(intval);
		
		//调回
		$(".GetVirify").hide(),$(".GetVirify:eq(0)").show();
		i = 60;//重置
		return false;
	}
	intval = setTimeout(Countdown,1000);
}
//选择
function checkbox_select(t){
	if( $(t).is(":checked") == true ){
		$(".reset_btn").hide(),$(".reset_btn:eq(0)").show();
	}else{
		$(".reset_btn").hide(),$(".reset_btn:eq(1)").show();
	}
}