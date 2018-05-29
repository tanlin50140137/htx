<?php

/**
 * TanLin Mobile架构
 * */
################################################页面##############################################################
/**
 * 统一登录
 * */
function IndexUnifiedLogin()
{
	session_start();
	if( $_GET['user'] != null )
	{
		//获取远程信息
		$post = 'act=GetUserInfo_alls&users='.$_GET['user'];
		$json = vcurl(EXTLINK,$post);
		$Datainfo = ParsingJson($json);
		
		$_SESSION['log_on_user'] = $Datainfo;
	}
	else
	{
		$_SESSION['log_on_user']=null;
		unset($_SESSION['log_on_user']);
	}
	
	$callb_url = $_GET['callb_url']==null?apth_url():urldecode($_GET['callb_url']);
	header('location:'.$callb_url);
}
/**
 * 首页
 * */
function index()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	#获取我的专题
	$json = vcurl(EXTLINK,'act=GetMyZT');
	$myzt = ParsingJson($json);
	
	#获取推荐
	$json = vcurl(EXTLINK,'act=GetRecommend');
	$tuijian = ParsingJson($json);	
	#获取资料下载-页数
	$json = vcurl(EXTLINK,'act=MobileDownload&getpage=page');
	$pageDownload = ParsingJson($json);
	#获取视频培训-页数
	$json = vcurl(EXTLINK,'act=MobileVideo&getpage=page');
	$pageVideo = ParsingJson($json);
	#获取热门话题-页数
	$json = vcurl(EXTLINK,'act=GetHotTopics&getpage=page');
	$pageHot = ParsingJson($json);
	
	require dir_url('subject/htx/template/').__FUNCTION__.'.html';
}
/**
 * 视频
 * */
function video()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	$id = htmlspecialchars($_GET['id'],ENT_QUOTES,'UTF-8',false);
	
	$pid = $_GET['pid']==''?'':$_GET['pid'];
	$area = $_GET['area']==''?'':$_GET['area'];
	
	if($id!='')
	{
		#获取指定分类名
		$json = vcurl(EXTLINK,'act=GetSpecifiedColumn_video&id='.$id);
		$ColumnFL = ParsingJson($json);
		$flName = $ColumnFL['rows'][0];
	}
		
	#获取区域
	$json = vcurl(EXTLINK,'act=GetQuyu');
	$quyu = ParsingJson($json);
	$eara = $quyu['rows'];
	array_shift($eara);
		
	#获取子分类
	$json = vcurl(EXTLINK,'act=GetDataFL_video&pid='.$id);
	$DataFL = ParsingJson($json);
	$fl = $DataFL['rows'];
	
	//获取资料列表
	$json = vcurl(EXTLINK,'act=GetDataList_video&flag=3&pid='.$pid.'&area='.$area);
	$DataList = ParsingJson($json);
	
	require dir_url('subject/htx/template/').__FUNCTION__.'.html';
}
function videoinfo()
{#视频详情页面
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	$id = htmlspecialchars($_GET['contid'],ENT_QUOTES,'UTF-8',false);
	
	#获取本系列信息
	$json = vcurl(EXTLINK,'act=GetVideoInfo&consid='.$id);
	$DataList = ParsingJson($json);
	$imgapth = ParsingJson($DataList['row']['coverapth']);
	switch($DataList['error']){
		case '1':
			echo "<script>alert('".$DataList['txt']."');location.href='".apth_url()."';</script>";exit;
		break;
	}
	
	require dir_url('subject/htx/template/').'video-details1.html';
}
function videolook()
{#视频观看页面
	//公共文件
	include dir_url('subject/htx/').'common.php';
		
	$id = htmlspecialchars($_GET['contid'],ENT_QUOTES,'UTF-8',false);
	
	//请先登录
	if( $boolstr == false )
	{
		echo "<script>alert('请先登录');location.href='".apth_url('?act=videoinfo&contid='.$id)."';</script>";exit;
	}
	
	$numbers = htmlspecialchars($_GET['numbers'],ENT_QUOTES,'UTF-8',false);
	$getnums = $numbers==''?1:$numbers;
	
	#获取视频系列信息
	$json = vcurl(EXTLINK,'act=GetLookVideoInfo&consid='.$id.'&boolstr='.$boolstr['username'].'&numbers='.$numbers.'&tel='.$member_all['tel'].'&email='.$member_all['email'].'&qq='.$member_all['qq']);
	$DataList = ParsingJson($json);

	$flag_pay = $DataList['RowsAll'][0]['numbers_pay'];
	
	//获取本节信息
	$json = vcurl(EXTLINK,'act=GetVideoAddess_data&d='.$id.'&numbers='.$getnums.'&t='.$boolstr['username']);
	$DataList2 = ParsingJson($json);
	
	//点击量
	click_nmu($id,$getnums);
	
	switch($DataList['error']){
		case '1':
			echo "<script>alert('".$DataList['txt']."');location.href='".apth_url()."';</script>";exit;
		break;
	}
	
	require dir_url('subject/htx/template/').'video-details2.html';
}
/**
 * 资料 
 * */
function data()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	#获取资料分类
	$json = vcurl(EXTLINK,'act=GetDataFL');
	$DataFL = ParsingJson($json);
	$fl = $DataFL['rows'];
	array_pop($fl);

	require dir_url('subject/htx/template/').__FUNCTION__.'.html';
}
/**
 * 资料 -列表页
 * */
function data_list()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	$id = htmlspecialchars($_GET['id'],ENT_QUOTES,'UTF-8',false);
	
	$pid = $_GET['pid']==''?'':$_GET['pid'];
	$area = $_GET['area']==''?'':$_GET['area'];
	
	if($id!='')
	{
		#获取指定分类名
		$json = vcurl(EXTLINK,'act=GetSpecifiedColumn&id='.$id);
		$ColumnFL = ParsingJson($json);
		$flName = $ColumnFL['rows'][0];
	}
	
	#获取区域
	$json = vcurl(EXTLINK,'act=GetQuyu');
	$quyu = ParsingJson($json);
	$eara = $quyu['rows'];
	array_shift($eara);
	
	#获取子分类
	$json = vcurl(EXTLINK,'act=GetDataFL&pid='.$id);
	$DataFL = ParsingJson($json);
	$fl = $DataFL['rows'];
	
	//获取资料列表
	$json = vcurl(EXTLINK,'act=GetDataList&flag=1&pid='.$pid.'&area='.$area);
	$DataList = ParsingJson($json);
	
	require dir_url('subject/htx/template/').'data-1.html';
}
/**
 * 资料 -内容页
 * */
function data_content()
{
	session_start();
	//公共文件
	include dir_url('subject/htx/').'common.php';
		
	$consid = htmlspecialchars($_GET['consid'],ENT_QUOTES,'UTF-8',false);
	#简介内容
	if( $consid !='' )
	{
		#记录动态
		//Listen_memdy($boolstr,$consid,1);
		
		#内容
		$json = vcurl(EXTLINK,'act=GetDataContent&consid='.$consid);		
		$rowd = ParsingJson($json);
		$row = $rowd['rows'];

		/*支付信息*/
		$data['username'] = GetUserNames($member_all);//用户名
		$data['integral'] = $row['integral'];//价格
		$data['id'] = $consid;//当前资料ID
		$_SESSION['wap_payment_info'] = $data;//sesson传参
				
		#检测是否支付
		$pay = '';
		if( $data['username'] != null )
		{
			$json = vcurl(EXTLINK,'act=checked_pay&username='.$data['username'].'&consid='.$consid);		
			$pay = ParsingJson($json);
		}
		
		#评星算法 
		vcurl(EXTLINK,'act=StarAlgorithmId_ph&consid='.$consid);

		//require dir_url('subject/htx/template/').__FUNCTION__.'.html';//第一版本
		require dir_url('subject/htx/template/').'data-details-2.html';
	}
	else 
	{
		echo '<center>非法传输，服务器拒绝访问!</center>';
	}	
}
/**
 * 资料 下载-微信支付
 * */
function weixin_ment()
{
	session_start();
	$info = $_SESSION['wap_payment_info'];
	
	$json = vcurl(EXTLINK,'act=payment_path&username='.$info['username'].'&userid='.$info['id']);
	$row = ParsingJson($json);
	if( $row['error'] == 1 )
	{#第一次提交
		$data['username'] = $info['username'];//支付者
		$data['consid'] = $info['id'];//关联资料或视频PID
		$data['infoid'] = $info['id'];//关联资料或视频ID
		$data['objtype'] = 1;//关联信息类型
		$data['tardename'] = '火天信资料下载';//商品名称
		$data['ordersn'] = 'HTX'.date('YmdHis').mt_rand(10000, 99999).'w';//订单号
		$data['price'] = $info['integral'];//累计金额
		$data['ordertime'] = time();//下单时间
		$data['numbers'] = '';
		$data['numbers_no'] = '';
		$data['frees'] = $info['integral'];//本次金额
		$data['paytype'] = '微信支付';
		$data['state'] = 1;
		//记录支付信息
		vcurl(EXTLINK,'act=SetPayMentInfo&data='.json_encode($data));
				
	}
	else 
	{
		if( $row['txt']['state'] != 2 )
		{#未支付
			$data['objtype'] = 1;//关联信息类型
			$data['ordersn'] = 'HTX'.date('YmdHis').mt_rand(10000, 99999).'w';//订单号
			$data['price'] = $info['integral'];//本次金额
			$data['frees'] = $info['integral'];
			$data['paytype'] = '微信支付';

			//修改支付信息
			vcurl(EXTLINK,'act=UpdatePayMentInfo&username='.$info['username'].'&userid='.$info['id'].'&data='.json_encode($data));
		}
		else 
		{#已经支付过
			
		}
	}

	//记录监听
	vcurl(EXTLINK,'act=WeChatRecord&data='.json_encode(array('ordersn'=>$data['ordersn'],'objtype'=>1,'state'=>1)));
	
	#POST参数
	$post_url = 'http://newjob.htxgcw.com/wapweixin/pay.php?pay=wapweixin';//提交地址
	$subject = '火天信资料下载';//商品名称
	$out_trade_no = $data['ordersn'];
	$total_fee = $data['frees']*100;
	
	echo '<form action="'.$post_url.'" method="post" id="frm">
			<input type="hidden" name="subject" value="'.$subject.'"/>
			<input type="hidden" name="ordersn" value="'.$out_trade_no.'"/>
			<input type="hidden" name="amount" value="'.$total_fee.'"/>
		 </form><script>document.getElementById("frm").submit();</script>';
	
}
/**
 * 资料 下载-银联支付
 * */
function unionpay()
{
	session_start();
	$info = $_SESSION['wap_payment_info'];
		
	$json = vcurl(EXTLINK,'act=payment_path&username='.$info['username'].'&userid='.$info['id']);
	$row = ParsingJson($json);
	if( $row['error'] == 1 )
	{#第一次提交
		$data['username'] = $info['username'];//支付者
		$data['consid'] = $info['id'];//关联资料或视频PID
		$data['infoid'] = $info['id'];//关联资料或视频ID
		$data['objtype'] = 1;//关联信息类型
		$data['tardename'] = '火天信资料下载';//商品名称
		$data['ordersn'] = 'HTX'.date('YmdHis').mt_rand(10000, 99999).'w';//订单号
		$data['price'] = $info['integral'];//累计金额
		$data['ordertime'] = time();//下单时间
		$data['numbers'] = '';
		$data['numbers_no'] = '';
		$data['frees'] = $info['integral'];//本次金额
		$data['paytype'] = '银联支付';
		$data['state'] = 1;
		//记录支付信息
		vcurl(EXTLINK,'act=SetPayMentInfo&data='.json_encode($data));
				
	}
	else 
	{
		if( $row['txt']['state'] != 2 )
		{#未支付
			$data['objtype'] = 1;//关联信息类型
			$data['ordersn'] = 'HTX'.date('YmdHis').mt_rand(10000, 99999).'w';//订单号
			$data['price'] = $info['integral'];//本次金额
			$data['frees'] = $info['integral'];
			$data['paytype'] = '银联支付';

			//修改支付信息
			vcurl(EXTLINK,'act=UpdatePayMentInfo&username='.$info['username'].'&userid='.$info['id'].'&data='.json_encode($data));
		}
		else 
		{#已经支付过
			
		}
	}

	#POST参数
	$post_url = 'http://newjob.htxgcw.com/unionpay/pay.php?pay=unionpay';//提交地址
	$channelType = '08';//手机端
	$out_trade_no = $data['ordersn'];
	$total_fee = $data['frees'];
	
	echo '<form action="'.$post_url.'" method="post" id="frm">
			<input type="hidden" name="channelType" value="'.$channelType.'"/>
			<input type="hidden" name="out_trade_no" value="'.$out_trade_no.'"/>
			<input type="hidden" name="total_fee" value="'.$total_fee.'"/>
		 </form><script>document.getElementById("frm").submit();</script>';
	
}
/**
 * 资料 下载-支付成功页面
 * */
function pay_end()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	$ordersn = $_REQUEST['ordersn'];
	//GetJieGuo
	$json = vcurl(EXTLINK,'act=GetJieGuo&ordersn='.$ordersn);
	$rows = ParsingJson($json);	
	$row = $rows['row'];
	$link =  EXTURL_DATA.'/executable_file.php?act=Remotedownload&id='.$row['id'];//下载链接
	$pay = $rows['payinfo'];
	$apth = ParsingJson($row['coverapth']);
	
	if(empty($pay))
	{
		header('location:'.apth_url());exit;
	}
	else 
	{
		if( $pay['state'] != 2 )
		{#未支付
			header('location:'.apth_url());exit;
		}
	}
	
	require dir_url('subject/htx/template/').'pay-center-2.html';
}
/**
 * 资料 下载-支付宝支付
 * */
function alipay_ment()
{
	session_start();
	$info = $_SESSION['wap_payment_info'];
	
	$json = vcurl(EXTLINK,'act=payment_path&username='.$info['username'].'&userid='.$info['id']);
	$row = ParsingJson($json);
	if( $row['error'] == 1 )
	{#第一次提交
		$data['username'] = $info['username'];//支付者
		$data['consid'] = $info['id'];//关联资料或视频PID
		$data['infoid'] = $info['id'];//关联资料或视频ID
		$data['objtype'] = 1;//关联信息类型
		$data['tardename'] = '火天信资料下载';//商品名称
		$data['ordersn'] = 'HTX'.date('YmdHis').mt_rand(10000, 99999).'w';//订单号
		$data['price'] = $info['integral'];//累计金额
		$data['ordertime'] = time();//下单时间
		$data['numbers'] = '';
		$data['numbers_no'] = '';
		$data['frees'] = $info['integral'];//本次金额
		$data['paytype'] = '支付宝';
		$data['state'] = 1;
		//记录支付信息
		vcurl(EXTLINK,'act=SetPayMentInfo&data='.json_encode($data));
	}
	else 
	{
		if( $row['txt']['state'] != 2 )
		{#未支付
			$data['objtype'] = 1;//关联信息类型
			$data['ordersn'] = 'HTX'.date('YmdHis').mt_rand(10000, 99999).'w';//订单号
			$data['price'] = $info['integral'];//本次金额
			$data['frees'] = $info['integral'];
			$data['paytype'] = '支付宝';

			//修改支付信息
			vcurl(EXTLINK,'act=UpdatePayMentInfo&username='.$info['username'].'&userid='.$info['id'].'&data='.json_encode($data));
		}
		else 
		{#已经支付过
			
		}
	}

	#POST参数
	$post_url = 'http://newjob.htxgcw.com/wapalipay/pay.php?pay=wapalipay';//提交地址
	$callback_url = '';
	$notify_url = '';
	$subject = '火天信资料下载';//商品名称
	$out_trade_no = $data['ordersn'];
	$total_fee = $data['frees'];
	
	echo '<form action="'.$post_url.'" method="post" id="frm">
			<input type="hidden" name="callback_url" value="'.$callback_url.'"/>
			<input type="hidden" name="notify_url" value="'.$notify_url.'"/>
			<input type="hidden" name="subject" value="'.$subject.'"/>
			<input type="hidden" name="out_trade_no" value="'.$out_trade_no.'"/>
			<input type="hidden" name="total_fee" value="'.$total_fee.'"/>
		 </form><script>document.getElementById("frm").submit();</script>';	 
}
/**
 * 支付宝成功返回数据-同步返回
 * */
function pay_success()
{
	//ordersn
	$json = vcurl(EXTLINK,'act=checked_order&ordersn='.$_GET['out_trade_no']);
	$row = ParsingJson($json);
	if( $row['objtype'] == 1 )
	{#资料
		$data['serial'] = $_GET['trade_no'];//流水号
		$data['paytime'] = time();//支付时间
		$data['status'] = $_GET['result'];//支付宝返回状态
		$data['state'] = 2;//待支付状态
		
		//UpdatePayMentInfo_order
		$int = vcurl(EXTLINK,'act=UpdatePayMentInfo_order&ordersn='.$_GET['out_trade_no'].'&data='.json_encode($data));
		if($int)
		{#支付成功
			header('location:'.apth_url('?act=pay_end&ordersn='.$_GET['out_trade_no']));
		}
		else
		{#支付失败
			header('location:'.apth_url());
		}
	}
	else 
	{#视频
		header('location:'.apth_url());
	}
}
/**
 * 支付宝成功返回数据-异步返回
 * */
function pay_alinotify()
{
	//ordersn
	$json = vcurl(EXTLINK,'act=checked_order&ordersn='.$_POST['out_trade_no']);
	$row = ParsingJson($json);
	if( $row['objtype'] == 1 )
	{#资料
		$data['serial'] = $_POST['trade_no'];//流水号
		$data['paytime'] = time();//支付时间
		$data['status'] = $_POST['trade_status'];//支付宝返回状态
		$data['state'] = 2;//待支付状态
		
		//UpdatePayMentInfo_order
		vcurl(EXTLINK,'act=UpdatePayMentInfo_order&ordersn='.$_POST['out_trade_no'].'&data='.json_encode($data));		
	}
	else 
	{#视频
		
	}
}
/**
 * 微信异步回返
 * */
function weixin_notify()
{
	$json = vcurl(EXTLINK,'act=checked_order&ordersn='.$_REQUEST['out_trade_no']);
	$row = ParsingJson($json);
	if( $row['objtype'] == 1 )
	{#资料
		$data['serial'] = $_REQUEST['transaction_id'];//流水号
		$data['paytime'] = time();//支付时间
		$data['status'] = $_REQUEST['bank_type'];
		$data['state'] = 2;//待支付状态
		
		//UpdatePayMentInfo_order记录数据
		vcurl(EXTLINK,'act=UpdatePayMentInfo_order&ordersn='.$_REQUEST['out_trade_no'].'&data='.json_encode($data));
		
		#SetWeiXinNotify		
		vcurl(EXTLINK,'act=SetWeiXinNotify&ordersn='.$_REQUEST['out_trade_no'].'&paytime='.$data['paytime'].'&objtype='.$row['objtype'].'&username='.$row['username']);		
	}
	else 
	{#视频
		
	}
}
/**
 * 手记内容页题
 * */
function ContentsNotes()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	#用户ID
	$user = htmlspecialchars($_GET['user'],ENT_QUOTES,'UTF-8',false);
		
	if( $user == '')
	{#获取用户信息
		$user = $member_all['id'];
		$userinfo = $member_all;
	}
	else 
	{#获取用户信息 
		$json = vcurl(EXTLINK,'act=GetExtInfos&user='.$user);//外部访问到您的信息
		$userinfo = ParsingJson($json);
	}
		
	#验证如果为空
	if( empty($userinfo) ){exit;}
		
	#获取ID
	$userid = htmlspecialchars($_GET['contid'],ENT_QUOTES,'UTF-8',false);
	
	#本条信息是否存在 
	$flag = vcurl(EXTLINK,'act=CheckedInfo&userid='.$userid.'&user='.$user);//外部访问到您的信息
	if( $flag == 0 ){exit;}				
	if( $userid == '' ){exit;}
	
	#记录手记浏览量
	vcurl(EXTLINK,'act=MRecordNotesViews&userid='.$userid.'&user='.$user);
	#获取编辑信息 
	$json = vcurl(EXTLINK,'act=MGetThematicRow&userid='.$userid);
	$row = ParsingJson($json);
	#最新手记 
	$json = vcurl(EXTLINK,'act=MGetHotNotes&user='.$user);
	$HotRow = ParsingJson($json);
	
	require dir_url('subject/htx/template/').'data-details-1.html';
}
/**
 * 手记内容页题-提问
 * */
function AskContQues()
{
	//公共文件 
	include dir_url('subject/htx/').'common.php';
	
	$user = htmlspecialchars($_GET['user'],ENT_QUOTES,'UTF-8',false);
	#获取用户信息
	$json = vcurl(EXTLINK,'act=mGetAboutQuestion&user='.$user);
	$data = ParsingJson($json);
	
	require dir_url('subject/htx/template/').'ask-questions-1.html';
}
/**
 * 登录页面
 * */
function login_reset()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	require dir_url('subject/htx/template/').'login.html';
}
function login()
{#登录
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	require dir_url('subject/htx/template/').'login-1.html';
}
function resets()
{#注册
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	require dir_url('subject/htx/template/').'login-2.html';
}
function password_call()
{#找回密码
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	require dir_url('subject/htx/template/').__FUNCTION__.'.html';
}
function password_upd()
{#修改密码
	session_start();
	
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	if(isset($_SESSION['update_passwords']) || !empty($_SESSION['update_passwords']))
	{		
		$post = 'act=user_existence_yes&datas='.$_SESSION['update_passwords'];
		$int = vcurl(EXTLINK,$post);
		if( $int == 0 )
		{
			header('location:'.apth_url('password_call'));exit;
		}		
	}
	
	require dir_url('subject/htx/template/').'password-upd.html';
}
/**
 * 专题－页面
 * */
function zt_project()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	require dir_url('subject/htx/template/').'project.html';
}
/**
 * 专题－开通个人
 * */
function to_information()
{#开通专题
	//公共文件
	include dir_url('subject/htx/').'common.php';
		
	//如果已经提交－跳转到审核页面	
	$post = 'act=mGetUserinfo&tel='.$member_all['tel'];
	$json = vcurl(EXTLINK,$post);
	$authentication = ParsingJson($json);
	$auth = $authentication['authentication']==null?0:$authentication['authentication'];
	switch ( $auth )
	{
		case 1:	
			if( $authentication['subordinate'] == 1 )
			{
				header('location:'.apth_url('?act=to_information_1&contid='.$member_all['tel']));//开通专题栏目	- 个人
			}
			else 
			{
				header('location:'.apth_url('?act=to_information_ent&contid='.$member_all['tel']));//开通专题栏目 - 企业
			}									
		break;
		case 2:
			if( $authentication['subordinate'] == 1 )
			{
				header('location:'.apth_url('?act=to_information_2&contid='.$member_all['tel']));//审核通过
			}
			else 
			{	
				header('location:'.apth_url('?act=to_information_ent2&contid='.$member_all['tel']));//审核通过
			}			
		break;	
	}
	
	require dir_url('subject/htx/template/').'to-information.html';
}
/**
 * 专题－开通企业
 * */
function to_enterprise()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	//如果已经提交－跳转到审核页面	
	$post = 'act=mGetUserinfo&tel='.$member_all['tel'];
	$json = vcurl(EXTLINK,$post);
	$authentication = ParsingJson($json);
	$auth = $authentication['authentication']==null?0:$authentication['authentication'];
	switch ( $auth )
	{
		case 1:
			if( $authentication['subordinate'] == 1 )
			{
				header('location:'.apth_url('?act=to_information_1&contid='.$member_all['tel']));//开通专题栏目	- 个人
			}
			else 
			{
				header('location:'.apth_url('?act=to_information_ent&contid='.$member_all['tel']));//开通专题栏目 - 企业
			}
		break;
		case 2:
			if( $authentication['subordinate'] == 1 )
			{
				header('location:'.apth_url('?act=to_information_2&contid='.$member_all['tel']));//审核通过
			}
			else 
			{	
				header('location:'.apth_url('?act=to_information_ent2&contid='.$member_all['tel']));//审核通过
			}
		break;	
	}
	require dir_url('subject/htx/template/').'to-enterprise.html';
}
/**
 * 专题－审核中-个人
 * */
function to_information_1()
{#开通专题－审核
	//公共文件
	include dir_url('subject/htx/').'common.php';
	//如果已经提交－跳转到审核页面
	$post = 'act=mGetUserinfo&tel='.$member_all['tel'].'&subordinate=1';
	$json = vcurl(EXTLINK,$post);
	$authentication = ParsingJson($json);
	$auth = $authentication['authentication']==null?0:$authentication['authentication'];
	switch ( $auth )
	{
		case 2:
				header('location:'.apth_url('?act=to_information_2&contid='.$member_all['tel']));//审核通过
		break;	
	}
	$tel = $_GET['contid'];	
	//获取用户信息 
	$post = 'act=mGetUserinfo&tel='.$tel;
	$hRows = vcurl(EXTLINK,$post);
	$row = json_decode($hRows,true);
	$img_j = json_decode($row['identity'],true);
	
	require dir_url('subject/htx/template/').'to-information-1.html';
}
/**
 * 专题－审核中-企业
 * */
function to_information_ent()
{#开通专题－审核
	//公共文件
	include dir_url('subject/htx/').'common.php';
	//如果已经提交－跳转到审核页面
	$post = 'act=mGetUserinfo&tel='.$member_all['tel'].'&subordinate=2';
	$json = vcurl(EXTLINK,$post);
	$authentication = ParsingJson($json);
	$auth = $authentication['authentication']==null?0:$authentication['authentication'];
	switch ( $auth )
	{
		case 2:
				header('location:'.apth_url('?act=to_information_ent2&contid='.$member_all['tel']));//审核通过
		break;	
	}
	$tel = $_GET['contid'];	
	//获取用户信息 
	$post = 'act=mGetUserinfo&tel='.$tel;
	$hRows = vcurl(EXTLINK,$post);
	$row = json_decode($hRows,true);
	$img_j = json_decode($row['identity'],true);
	
	require dir_url('subject/htx/template/').'to-information-ent.html';
}
/**
 * 专题－审核通过-个人
 * */
function to_information_2()
{#开通专题－审核
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	$tel = $_GET['contid'];	
	//获取用户信息 
	$post = 'act=mGetUserinfo&tel='.$tel;
	$hRows = vcurl(EXTLINK,$post);
	$row = json_decode($hRows,true);
	$img_j = json_decode($row['identity'],true);
	
	$authentication = ParsingJson($img_j);
	$auth = $authentication['authentication']==null?0:$authentication['authentication'];
	switch ( $auth )
	{
		case 0:
				header('location:'.apth_url('?act=zt_project'));
		break;	
	}
	
	require dir_url('subject/htx/template/').'to-information-2.html';
}
/**
 * 专题－审核通过-企业
 * */
function to_information_ent2()
{#开通专题－审核
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	$tel = $_GET['contid'];	
	//获取用户信息 
	$post = 'act=mGetUserinfo&tel='.$tel;
	$hRows = vcurl(EXTLINK,$post);
	$row = json_decode($hRows,true);
	$img_j = json_decode($row['identity'],true);
	
	require dir_url('subject/htx/template/').'to_information_ent2.html';
}
/**
 * 专题－主页
 * */
function ThemIndex()
{
	//公共文件 
	include dir_url('subject/htx/').'common.php';
	$id = $_GET['id'];
		
	$post = 'act=GetThemaInfo&user='.$id;
	$json = vcurl(EXTLINK,$post);
	$Datainfo = ParsingJson($json);
	$userinfo = $Datainfo['userinfo'];
	$rows = $Datainfo['rows']['rows'];
	
	if( $userinfo == '' )
	{
		header('location:'.apth_url('?act=zt_project'));exit;
	}
	else
	{
		if( $userinfo['power'] != 2 )
		{#不是超级管理员
			if( $userinfo['authentication'] == 0 )
			{
				header('location:'.apth_url('?act=zt_project'));exit;
			}
			elseif(  $userinfo['authentication'] == 1  )
			{
				if( $userinfo['subordinate'] == 1 )
				{
					header('location:'.apth_url('?act=to_information_1&contid='.$userinfo['tel']));exit;//开通专题栏目	- 个人
				}
				elseif( $userinfo['subordinate'] == 2 )
				{
					header('location:'.apth_url('?act=to_information_ent&contid='.$userinfo['tel']));exit;//开通专题栏目 - 企业
				}
				else 
				{
					header('location:'.apth_url('?act=zt_project'));exit;
				}
			}
		}
	}
	
	require dir_url('subject/htx/template/').'personal-5-1.html';
}
/**
 * 专题－发布文章
 * */
function ThemArticles()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	//获取分类 
	$post = 'act=GetDataFL';
	$json = vcurl(EXTLINK,$post);
	$fl = json_decode($json,true);
	array_pop($fl['rows']);
	
	if( $member_all['authentication'] == 0 )
	{
		header('location:'.apth_url('?act=zt_project'));exit;
	}
	elseif(  $member_all['authentication'] == 1  )
	{
		if( $member_all['subordinate'] == 1 )
		{
			header('location:'.apth_url('?act=to_information_1&contid='.$member_all['tel']));exit;//开通专题栏目	- 个人
		}
		elseif( $member_all['subordinate'] == 2 )
		{
			header('location:'.apth_url('?act=to_information_ent&contid='.$member_all['tel']));exit;//开通专题栏目 - 企业
		}
		else 
		{
			header('location:'.apth_url('?act=zt_project'));exit;
		}
	}
	
	require dir_url('subject/htx/template/').'personal-5-2.html';
}
/**
 * 外部上传
 * */
function up_project()
{#外部上传
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	#上传列表
	$page = $_GET['page'] == null?1:$_GET['page'];
	$post = 'act=GetHistoryData&username='.$boolstr['username'].'&page='.$page;
	$hRows = vcurl(EXTERNAL,$post);
	$rows = json_decode($hRows,true);
	
	require dir_url('subject/htx/template/').'project-1.html';
}
/**
 * 搜索－页面
 * */
function search()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	#获取资料分类
	$json = vcurl(EXTLINK,'act=GetDataFL');
	$DataFL = ParsingJson($json);
	$fl = $DataFL['rows'];
	array_pop($fl);
	
	
	#获取区域
	$json = vcurl(EXTLINK,'act=GetQuyu');
	$quyu = ParsingJson($json);
	$eara = $quyu['rows'];
	array_shift($eara);
	
	//获取资料列表
	$json = vcurl(EXTLINK,'act=GetDataList&flag=1&pid=&area=');
	$DataList = ParsingJson($json);
	
	require dir_url('subject/htx/template/').__FUNCTION__.'.html';
}
/**
 * 帮助－页面
 * */
function help()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	switch ($_GET['id'])
	{
		case '1':$a='help-1';break;
		case '2':$a='help-2';break;
		case '3':$a='help-3';break;
		case '4':$a='help-4';break;
		case '5':$a='help-5';break;
		case '6':$a='help-6';break;
		case '7':$a='help-7';break;
		default:$a = __FUNCTION__;
	}	
	require dir_url('subject/htx/template/').$a.'.html';
}
/**
 * 提问－页面
 * */
function questions()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	//获取分类
	$post = 'act=GetDataFL';
	$json = vcurl(EXTLINK,$post);
	$fl = json_decode($json,true);
	array_pop($fl['rows']);

	
	require dir_url('subject/htx/template/').__FUNCTION__.'.html';
}
/**
 * 问答－页面
 * */
function AndAnswer()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	//mRelatedQuestions
	$post = 'act=mRelatedQuestions';
	$json = vcurl(EXTLINK,$post);
	$rows = json_decode($json,true);

	require dir_url('subject/htx/template/').'answer1.html';
}
/**
 * 全员中心－页面主页
 * */
function mypersonal()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	$pic = $member_all['pic'];//头像
	$username = $member_all['username'];//用户名
	$integral = $member_all['integral'];//积分
	$follow = $member_all['follow'];//关注
	
	require dir_url('subject/htx/template/').__FUNCTION__.'.html';
}
/**
 * 全员中心－内页
 * */
function mypersonal_in()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	switch ($_GET['locat'])
	{
		case '1':
			#获取会员中心－我的动态
			$data = json_encode($member_all);
			$json = vcurl(EXTLINK,'act=mGetMyState&data='.$data);
			$info = ParsingJson($json);
			
			$uplist = $info['end'];
			$endlist = $info['up'];
			
			$a='personal-1';
		break;
		case '2':	
			#获取会员中心－我的动态
			$data = json_encode($member_all);
			$json = vcurl(EXTLINK,'act=mGetBuying&data='.$data);
			$info = ParsingJson($json);
			
			$a='personal-2';
		break;
		case '3':	
			#获取会员中心－我的动态
			$data = json_encode($member_all);
			$json = vcurl(EXTLINK,'act=mGetMques&data='.$data);
			$info = ParsingJson($json);
						
			#获取我要提问信息
			$rows4 = $info['s2'];		
			#获取私信
			$rows5 = $info['s1'];
			#我关注的问题
			$mycon1 = $info['s3'];
			#我的回答
			$myan = $info['s4'];
			
			$a='personal-3';
		break;
		case '4':	
			#获取会员中心－订单中心 
			$data = json_encode($member_all);
			$json = vcurl(EXTLINK,'act=mGetMyPayment&data='.$data.'&page='.$_GET['page']);
			$info = ParsingJson($json);
			
			$page = $info['page'];//页码
			$totalpage = $info['totalpage'];//总页数
			$totalrows = $info['totalrows'];//总条数
			$totalshow = $info['totalshow'];//显示数
			
			$a='personal-4';
		break;
		case '5':				
			$pic = $member_all['pic'];//头像
			$username = $member_all['username'];//用户名
			$integral = $member_all['integral'];//积分
			$follow = $member_all['follow'];//关注
			
			#获取会员中心－我的专题  
			$data = json_encode($member_all);
			$json = vcurl(EXTLINK,'act=mGetMyZhuanTi&data='.$data);
			$info = ParsingJson($json);

			$srTotal = $info['srTotal'];
			$scTotal = $info['scTotal'];
			$saTotal = $info['saTotal'];
			$llTotal = $info['llTotal'];
			$suTotal = $info['suTotal'];
			
			$a='personal-5';
		break;
		case '6':	
			#获取会员中心－个人设置
			$subordinate = $member_all['subordinate'];//状态,2=企业,1=个人'	
			$a='personal-6';
		break;
		case '8':	
			#获取会员中心－个人设置
			$subordinate = $member_all['subordinate'];//状态,2=企业,1=个人'
				
			$balance = $member_all['balance']==''?0:$member_all['balance']; //余额
			
			$a='personal-7';
		break;
	}	
	require dir_url('subject/htx/template/').$a.'.html';
}
/**
 * 视频支付中心－页面
 * */
function v_payment_center()
{
	session_start();
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	$consid = htmlspecialchars($_REQUEST['consid'],ENT_QUOTES,'UTF-8',false);//系列ID
	$numbers = htmlspecialchars($_REQUEST['numbers'],ENT_QUOTES,'UTF-8',false);//章节编号
	
	//GetVideoInfo_payAll
	$json = vcurl(EXTLINK,'act=GetVideoInfo_payAll&consid='.$consid.'&number='.$numbers.'&data='.json_encode($member_all));
	$info = ParsingJson($json);
	
	if($info['error'] != 0 )
	{#如果数据为空
		header('location:'.apth_url('?act=videoinfo&contid='.$consid));exit;
	}
	
	$row = $info['txt']['row'];#本章节内容	
	$coverapth_json = json_decode($row['coverapth'],true);//图片路径
	$coverapth = EXTURL_DATA.$coverapth_json[0];
	
	$RowsAll = $info['txt']['RowsAll'];#讲节排序
	$a = $info['txt']['a'];#免费的
	$b = $info['txt']['b'];#已经支付的
	$free = $info['txt']['free'];//获取价格
	$t = $info['txt']['t'];#获取未支付的总数
	$a_set = $info['txt']['a_set'];/*2套以上才打折*/	
	$discount = $info['txt']['discount'];/*打1~9折*/
	
	if( $t == 0 )
	{#没支付的章节
		header('location:'.apth_url('?act=videolook&contid='.$consid));
	}
	
	$_SESSION['mGetJinErAll'] = $_SESSION['mGetJinErAll_once'] = $free;//处始化金额
	$_SESSION['numbersList'] = array($numbers);//处始化编号
	
	#支付信息
	#商品名称
	$tradeName = '火天信培训视频';	
	#生成订单号
	$order = 'HTX'.date('YmdHis').mt_rand(10000, 99999).'w';		
	#下单时间
	$time = date('Y-m-d H:i');		
	#购买者
	$purchaser = GetUserNames($member_all);
	#支付信息
	$_SESSION['pay_infos'] = array('purchaser'=>$purchaser,'member'=>$member_all,'tradeName'=>$tradeName,'id'=>$consid,'style'=>3,'order'=>$order,'t'=>$time,'zhangshu'=>$t,'zhjRows'=>$RowsAll,'discount'=>$discount,'a_set'=>$a_set,'free'=>$free);
	
	//require dir_url('subject/htx/template/').'payment_center.html';
	require dir_url('subject/htx/template/').'payment_center_2.html';
}
/**
 * 视频支付中心－支付宝提交支付
 * */
function AlipayVideoPage()
{
	session_start();
	
	$free = $_SESSION['mGetJinErAll'];
	$pay_info = $_SESSION['pay_infos'];
	//$numbersList = $_SESSION['numbersList'];
	$numbersList = array();	
	if(!empty($pay_info['zhjRows']))
	{
		foreach($pay_info['zhjRows'] as $k=>$v)
		{
			$numbersList[] = $v['numbers'];
		}
	}

	#获取会员信息
	$member_all = $pay_info['member'];
	if( empty( $numbersList ) )
	{
		echo json_encode(array('error'=>1,'txt'=>'请选择需要购买的章节'));exit;
	}
		
	#本章节内容
	$json = vcurl(EXTLINK,'act=mform_info_video3&id='.$pay_info['id']);
	$info = ParsingJson($json);		
	$row = $info['txt'];	
	if( $info['error'] != 0 )
	{
		echo json_encode(array('error'=>1,'txt'=>'没有找到相关的内容相息，可能的因原是空内容或已经被删除导致！'));exit;
	}

	#请求参数
	$post_url = 'http://newjob.htxgcw.com/wapalipay/pay.php?pay=wapalipay';//提交地址-手机移动支付
	$callback_url = '';//同步返回-本服务器
	$notify_url = '';//异步返回-本服务器
	$subject = trim ( $pay_info['tradeName'] ); // 商品名称
	$out_trade_no = trim ( $pay_info['order'] ); // 订单号
	$total_fee = trim ( $free );//价格	
	$paytype = '支付宝';//支付类型
	$payflag = 1;//表示支付宝
		
	$pid = $row['pid'];
	if( $pid == 0 )
	{#如果PID等于0就使用ID，如果不等于0就使用PID，主要针对：同一课程视频
		$pid = $row['id'];
	}
	
	#记录支付路径-修改-> 1状态 = 待支付
	$json = vcurl(EXTLINK,'act=mform_info_video4&consid='.$pay_info['id'].'&data='.json_encode($member_all));
	$info = ParsingJson($json);		
	$int = $info['txt'];
	
	if( $info['error'] != 0 )
	{#添加记录-第一次支付		

		#此参数支付成功后才记录入库
		$_SESSION['pay_numbers'] = json_encode($numbersList);
		$_SESSION['pay_price'] = $payflag.'%'.$total_fee;//本次金额
		$_SESSION['pay_paytype'] = $paytype;//支付类型
		######################################################################################################################			
		$data['username'] = $pay_info['purchaser'];//支付者
		$data['consid'] = $pid;//关联资料或视频PID
		$data['infoid'] = $pay_info['id'];//关联资料或视频ID
		$data['objtype'] = $pay_info['style'];//关联信息类型
		$data['tardename'] = $pay_info['tradeName'];//商品名称
		$data['ordersn'] = $pay_info['order'];//订单号
		$data['ordertime'] = strtotime($pay_info['t']);//下单时间
		$data['numbers'] = '';
		$data['numbers_no'] = json_encode($numbersList);
		$data['frees'] = $total_fee;//本次金额
		$data['price'] = '0';//累计金额
		$data['paytype'] = $paytype;
		
		//SetPayMentInfo
		vcurl(EXTLINK,'act=SetPayMentInfo&data='.json_encode($data));
	}
	else 
	{	
		#以下参数支付成功后才记录入库
		$numbers_int = json_decode($int['numbers_no'],true);//上次记录支付过的数量,这一次加上一次的数量
		$numbers_merge1 = array_merge($numbers_int,$numbersList);//合并
		$numbers_merge2 = array_unique($numbers_merge1);//去除重复的编号
		sort($numbers_merge2);//排序号		
		$_SESSION['pay_numbers'] = json_encode($numbers_merge2);//上一次章节编号和这一次的合并
		$_SESSION['pay_price'] = $int['price'].'-'.$payflag.'%'.$total_fee;//上一次和本次金额累加
		$_SESSION['pay_paytype'] = $paytype;//支付类型
		######################################################################################################################	
		
		$data['ordersn'] = $out_trade_no;//订单号
		$data['frees'] = $total_fee;//本次金额
		$data['numbers_no'] = json_encode($numbersList);
		$data['paytype'] = $paytype;
		
		//UpdatePayMentInfo
		vcurl(EXTLINK,'act=update_video_info3&data='.json_encode($data).'&consid='.$pay_info['id'].'&username='.json_encode($member_all));
	}
	
	#POST参数
	echo '<form action="'.$post_url.'" method="post" id="frm">
			<input type="hidden" name="flag_url" value="1"/>	
			<input type="hidden" name="subject" value="'.$subject.'"/>
			<input type="hidden" name="out_trade_no" value="'.$out_trade_no.'"/>
			<input type="hidden" name="total_fee" value="'.$total_fee.'"/>			
		 </form><script>document.getElementById("frm").submit();</script>';	
}
#视频支付－同步返回
function pay_success_video()
{
	$out_trade_no = $_REQUEST['out_trade_no'];//订单号
    $trade_no = $_REQUEST['trade_no'];//流水号
    $trade_status = $_REQUEST['result'];//支付成功返回 TRADE_SUCCESS
    $notify_time = time();//时间 

    #记录支付路径-修改 -> 2状态 = 已支付 
	$json = vcurl(EXTLINK,'act=checked_order&ordersn='.$out_trade_no);
	$row = ParsingJson($json);
    if( !empty( $row ) )
    {
    	$numbers_no = json_decode($row['numbers_no'],true);
    	if( empty($row['numbers']) )
    	{
	    	$numbers_merge2 = $numbers_no;
    	}
    	else
    	{    		
    		$numbers = json_decode($row['numbers'],true);	
	    	$numbers_merge1 = array_merge($numbers,$numbers_no);//合并
			$numbers_merge2 = array_unique($numbers_merge1);//去除重复的编号		
			sort($numbers_merge2);//排序号
    	}
    }
    	$data['numbers'] = json_encode($numbers_merge2);
    	$data['serial'] = $trade_no;//流水号
		$data['paytime'] = $notify_time;//支付时间
		$data['status'] = $trade_status;//支付宝返回状态
		$data['state'] = 2;//待支付状态
		$data['price'] = $row['price']+$row['frees'];//累计金额
	
		#记录订单
		$int = vcurl(EXTLINK,'act=UpdatePayMentInfo_order&ordersn='.$out_trade_no.'&data='.json_encode($data));
		
		#邮箱或短信通知，跳转结果页面 index.php?act=PaySuccess				
		if( $int )
		{#支付成功
				
			#记录站内通知 
			vcurl(EXTLINK,'act=SetWeiXinNotify_all&ordersn='.$out_trade_no.'&paytime='.$notify_time.'&objtype='.$row['objtype'].'&username='.$row['username']);
				
			header('location:'.apth_url('index.php?act=mPaySuccess&trade_no='.$out_trade_no));
		}
		else 
		{#失败-进入收银台
			$j = ParsingJson($row['numbers_no']);			
			header('location:'.apth_url('?act=v_payment_center&consid='.$row['infoid'].'&numbers='.$j[0]));	
		}
}
#视频支付－异步返回
function pay_alinotify_video()
{
	$out_trade_no = $_REQUEST['out_trade_no'];//订单号
    $trade_no = $_REQUEST['trade_no'];//流水号
    $trade_status = $_REQUEST['result'];//支付成功返回 TRADE_SUCCESS
    $notify_time = time();//时间 

    #记录支付路径-修改 -> 2状态 = 已支付 
	$json = vcurl(EXTLINK,'act=checked_order&ordersn='.$out_trade_no);
	$row = ParsingJson($json);
    if( !empty( $row ) )
    {
    	$numbers_no = json_decode($row['numbers_no'],true);
    	if( empty($row['numbers']) )
    	{
	    	$numbers_merge2 = $numbers_no;
    	}
    	else
    	{    		
    		$numbers = json_decode($row['numbers'],true);	
	    	$numbers_merge1 = array_merge($numbers,$numbers_no);//合并
			$numbers_merge2 = array_unique($numbers_merge1);//去除重复的编号		
			sort($numbers_merge2);//排序号
    	}
    }
    	$data['numbers'] = json_encode($numbers_merge2);
    	$data['serial'] = $trade_no;//流水号
		$data['paytime'] = $notify_time;//支付时间
		$data['status'] = $trade_status;//支付宝返回状态
		$data['state'] = 2;//待支付状态
		$data['price'] = $row['price']+$row['frees'];//累计金额
	
		#记录订单
		$int = vcurl(EXTLINK,'act=UpdatePayMentInfo_order&ordersn='.$out_trade_no.'&data='.json_encode($data));
		
		#邮箱或短信通知，跳转结果页面 index.php?act=PaySuccess				
		if( $int )
		{#支付成功	
			#记录站内通知 
			vcurl(EXTLINK,'act=SetWeiXinNotify_all&ordersn='.$out_trade_no.'&paytime='.$notify_time.'&objtype='.$row['objtype'].'&username='.$row['username']);				
			echo 'success';
		}
		else 
		{#失败-进入收银台
			echo 'fail';
		}
}
//银联
function VideoUnionpay()
{
	session_start();
	
	$free = $_SESSION['mGetJinErAll'];
	$pay_info = $_SESSION['pay_infos'];
	//$numbersList = $_SESSION['numbersList'];
		
	$numbersList = array();	
	if(!empty($pay_info['zhjRows']))
	{
		foreach($pay_info['zhjRows'] as $k=>$v)
		{
			$numbersList[] = $v['numbers'];
		}
	}
	
	#获取会员信息
	$member_all = $pay_info['member'];
	if( empty( $numbersList ) )
	{
		echo json_encode(array('error'=>1,'txt'=>'请选择需要购买的章节'));exit;
	}
		
	#本章节内容
	$json = vcurl(EXTLINK,'act=mform_info_video3&id='.$pay_info['id']);
	$info = ParsingJson($json);		
	$row = $info['txt'];	
	if( $info['error'] != 0 )
	{
		echo json_encode(array('error'=>1,'txt'=>'没有找到相关的内容相息，可能的因原是空内容或已经被删除导致！'));exit;
	}

	#请求参数
	$post_url = 'http://newjob.htxgcw.com/unionpay/pay.php?pay=unionpay';//提交地址-手机移动支付
	$callback_url = '';//同步返回-本服务器
	$notify_url = '';//异步返回-本服务器
	$subject = trim ( $pay_info['tradeName'] ); // 商品名称
	$out_trade_no = trim ( $pay_info['order'] ); // 订单号
	$total_fee = trim ( $free );//价格	
	$paytype = '银联支付';//支付类型
	$payflag = 1;//表示支付宝
	$channelType = '08';//手机端
			
	$pid = $row['pid'];
	if( $pid == 0 )
	{#如果PID等于0就使用ID，如果不等于0就使用PID，主要针对：同一课程视频
		$pid = $row['id'];
	}
	
	#记录支付路径-修改-> 1状态 = 待支付
	$json = vcurl(EXTLINK,'act=mform_info_video4&consid='.$pay_info['id'].'&data='.json_encode($member_all));
	$info = ParsingJson($json);		
	$int = $info['txt'];
	
	if( $info['error'] != 0 )
	{#添加记录-第一次支付		

		#此参数支付成功后才记录入库
		$_SESSION['pay_numbers'] = json_encode($numbersList);
		$_SESSION['pay_price'] = $payflag.'%'.$total_fee;//本次金额
		$_SESSION['pay_paytype'] = $paytype;//支付类型
		######################################################################################################################			
		$data['username'] = $pay_info['purchaser'];//支付者
		$data['consid'] = $pid;//关联资料或视频PID
		$data['infoid'] = $pay_info['id'];//关联资料或视频ID
		$data['objtype'] = $pay_info['style'];//关联信息类型
		$data['tardename'] = $pay_info['tradeName'];//商品名称
		$data['ordersn'] = $pay_info['order'];//订单号
		$data['ordertime'] = strtotime($pay_info['t']);//下单时间
		$data['numbers'] = '';
		$data['numbers_no'] = json_encode($numbersList);
		$data['frees'] = $total_fee;//本次金额
		$data['price'] = '0';//累计金额
		$data['paytype'] = $paytype;
		
		//SetPayMentInfo
		vcurl(EXTLINK,'act=SetPayMentInfo&data='.json_encode($data));
	}
	else 
	{	
		#以下参数支付成功后才记录入库
		$numbers_int = json_decode($int['numbers_no'],true);//上次记录支付过的数量,这一次加上一次的数量
		$numbers_merge1 = array_merge($numbers_int,$numbersList);//合并
		$numbers_merge2 = array_unique($numbers_merge1);//去除重复的编号
		sort($numbers_merge2);//排序号		
		$_SESSION['pay_numbers'] = json_encode($numbers_merge2);//上一次章节编号和这一次的合并
		$_SESSION['pay_price'] = $int['price'].'-'.$payflag.'%'.$total_fee;//上一次和本次金额累加
		$_SESSION['pay_paytype'] = $paytype;//支付类型
		######################################################################################################################	
		
		$data['ordersn'] = $out_trade_no;//订单号
		$data['frees'] = $total_fee;//本次金额
		$data['numbers_no'] = json_encode($numbersList);
		$data['paytype'] = $paytype;
		
		//UpdatePayMentInfo
		vcurl(EXTLINK,'act=update_video_info3&data='.json_encode($data).'&consid='.$pay_info['id'].'&username='.json_encode($member_all));
	}
		
	echo '<form action="'.$post_url.'" method="post" id="frm">
			<input type="hidden" name="flag_url" value="1"/>
			<input type="hidden" name="channelType" value="'.$channelType.'"/>
			<input type="hidden" name="out_trade_no" value="'.$out_trade_no.'"/>
			<input type="hidden" name="total_fee" value="'.$total_fee.'"/>
		 </form><script>document.getElementById("frm").submit();</script>';
}
//微信支付－视频培训
function weixinVideoment()
{
	session_start();
	
	$free = $_SESSION['mGetJinErAll'];
	$pay_info = $_SESSION['pay_infos'];
	//$numbersList = $_SESSION['numbersList'];
		
	$numbersList = array();	
	if(!empty($pay_info['zhjRows']))
	{
		foreach($pay_info['zhjRows'] as $k=>$v)
		{
			$numbersList[] = $v['numbers'];
		}
	}
	
	#获取会员信息
	$member_all = $pay_info['member'];
	if( empty( $numbersList ) )
	{
		echo json_encode(array('error'=>1,'txt'=>'请选择需要购买的章节'));exit;
	}
		
	#本章节内容
	$json = vcurl(EXTLINK,'act=mform_info_video3&id='.$pay_info['id']);
	$info = ParsingJson($json);		
	$row = $info['txt'];	
	if( $info['error'] != 0 )
	{
		echo json_encode(array('error'=>1,'txt'=>'没有找到相关的内容相息，可能的因原是空内容或已经被删除导致！'));exit;
	}

	#请求参数
	$post_url = 'http://newjob.htxgcw.com/wapweixin/pay_1.php?pay=wapweixin';//提交地址-手机移动支付
	$callback_url = '';//同步返回-本服务器
	$notify_url = '';//异步返回-本服务器
	$subject = trim ( $pay_info['tradeName'] ); // 商品名称
	$out_trade_no = trim ( $pay_info['order'] ); // 订单号
	$total_fee = trim ( $free );//价格	
	$paytype = '微信支付';//支付类型
	$payflag = 1;//表示支付宝
	
	$pid = $row['pid'];
	if( $pid == 0 )
	{#如果PID等于0就使用ID，如果不等于0就使用PID，主要针对：同一课程视频
		$pid = $row['id'];
	}
	
	#记录支付路径-修改-> 1状态 = 待支付
	$json = vcurl(EXTLINK,'act=mform_info_video4&consid='.$pay_info['id'].'&data='.json_encode($member_all));
	$info = ParsingJson($json);		
	$int = $info['txt'];
		
	if( $info['error'] != 0 )
	{#添加记录-第一次支付		

		#此参数支付成功后才记录入库
		$_SESSION['pay_numbers'] = json_encode($numbersList);
		$_SESSION['pay_price'] = $payflag.'%'.$total_fee;//本次金额
		$_SESSION['pay_paytype'] = $paytype;//支付类型
		######################################################################################################################			
		$data['username'] = $pay_info['purchaser'];//支付者
		$data['consid'] = $pid;//关联资料或视频PID
		$data['infoid'] = $pay_info['id'];//关联资料或视频ID
		$data['objtype'] = $pay_info['style'];//关联信息类型
		$data['tardename'] = $pay_info['tradeName'];//商品名称
		$data['ordersn'] = $pay_info['order'];//订单号
		$data['ordertime'] = strtotime($pay_info['t']);//下单时间
		$data['numbers'] = '';
		$data['numbers_no'] = json_encode($numbersList);
		$data['frees'] = $total_fee;//本次金额
		$data['price'] = '0';//累计金额
		$data['paytype'] = $paytype;
		
		//SetPayMentInfo
		vcurl(EXTLINK,'act=SetPayMentInfo&data='.json_encode($data));		
	}
	else 
	{	
		#以下参数支付成功后才记录入库
		$numbers_int = json_decode($int['numbers_no'],true);//上次记录支付过的数量,这一次加上一次的数量
		$numbers_merge1 = array_merge($numbers_int,$numbersList);//合并
		$numbers_merge2 = array_unique($numbers_merge1);//去除重复的编号
		sort($numbers_merge2);//排序号		
		$_SESSION['pay_numbers'] = json_encode($numbers_merge2);//上一次章节编号和这一次的合并
		$_SESSION['pay_price'] = $int['price'].'-'.$payflag.'%'.$total_fee;//上一次和本次金额累加
		$_SESSION['pay_paytype'] = $paytype;//支付类型
		######################################################################################################################	
		
		$data['ordersn'] = $out_trade_no;//订单号
		$data['frees'] = $total_fee;//本次金额
		$data['numbers_no'] = json_encode($numbersList);
		$data['paytype'] = $paytype;
		
		//UpdatePayMentInfo
		vcurl(EXTLINK,'act=update_video_info3&data='.json_encode($data).'&consid='.$pay_info['id'].'&username='.json_encode($member_all));
		
	}

	//记录监听
	vcurl(EXTLINK,'act=WeChatRecord&data='.json_encode(array('ordersn'=>$data['ordersn'],'objtype'=>3,'state'=>1)));
	
	echo '<form action="'.$post_url.'" method="post" id="frm">
			<input type="hidden" name="subject" value="'.$subject.'"/>
			<input type="hidden" name="ordersn" value="'.$out_trade_no.'"/>
			<input type="hidden" name="amount" value="'.($total_fee*100).'"/>
		 </form><script>document.getElementById("frm").submit();</script>';
}
#微信异步返回
function WeixinVideoNotify()
{
	$out_trade_no = $_REQUEST['out_trade_no'];//订单号
    $trade_no = $_REQUEST['transaction_id'];//流水号
    $trade_status = $_REQUEST['bank_type'];//支付成功返回 TRADE_SUCCESS
    $notify_time = time();//时间 
     
    #记录支付路径-修改 -> 2状态 = 已支付 
	$json = vcurl(EXTLINK,'act=checked_order&ordersn='.$out_trade_no);
	$row = ParsingJson($json);
    if( !empty( $row ) )
    {
    	$numbers_no = json_decode($row['numbers_no'],true);
    	if( empty($row['numbers']) )
    	{
	    	$numbers_merge2 = $numbers_no;
    	}
    	else
    	{    		
    		$numbers = json_decode($row['numbers'],true);	
	    	$numbers_merge1 = array_merge($numbers,$numbers_no);//合并
			$numbers_merge2 = array_unique($numbers_merge1);//去除重复的编号		
			sort($numbers_merge2);//排序号
    	}
    }
    
    $data['numbers'] = json_encode($numbers_merge2);
    $data['serial'] = $trade_no;//流水号
	$data['paytime'] = $notify_time;//支付时间
	$data['status'] = $trade_status;//支付宝返回状态
	$data['state'] = 2;//待支付状态
	$data['price'] = $row['price']+$row['frees'];//累计金额
		
	if( $data['numbers'] != null && $data['price'] != null )
	{
		#记录订单
		$int = vcurl(EXTLINK,'act=UpdatePayMentInfo_order&ordersn='.$out_trade_no.'&data='.json_encode($data));
		
		#邮箱或短信通知，跳转结果页面 index.php?act=PaySuccess				
		if( $int )
		{#支付成功							
			#SetWeiXinNotify		
			vcurl(EXTLINK,'act=SetWeiXinNotify&ordersn='.$out_trade_no.'&paytime='.$notify_time.'&objtype='.$row['objtype'].'&username='.$row['username']);
			echo 'success';
		}
		else 
		{#失败-进入收银台
			echo 'fail';
		}
	}	
}
/**
 * 视频支付结果页面
 * */
function mPaySuccess()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	$ordersn = str_replace(array(' ','  ',"\n","\t"), array('','',"",""), urldecode(htmlspecialchars($_GET['trade_no'],ENT_QUOTES,'UTF-8',false)));
	#获取订单ID
	//$ordrow = db()->select('consid,infoid,state,objtype,numbers')->from(PRE.'payment')->where( array('ordersn'=>$ordersn) )->get()->array_row();
	$json = vcurl(EXTLINK,'act=checked_order&ordersn='.$ordersn);
	$ordrow = ParsingJson($json);
	
	if( $ordrow['state'] == 2 && $ordrow['numbers'] != '' )
	{#已经支付过		
		$json = vcurl(EXTLINK,'act=video_sucss_page&infoid='.$ordrow['infoid'].'&numbers='.$ordrow['numbers'].'&page='.$_GET['page']);
		$pageRows = ParsingJson($json);
		
		$totalRows = $pageRows['totalRows'];
	    $totalshow = $pageRows['totalshow'];
	    $totalpage = $pageRows['totalpage'];
	    $page = $pageRows['page'];
	    
		#支付结果页面	
		require dir_url('subject/htx/template/').'pay-center-1.html';
	}
	else 
	{#未支付过 HTX2018042409380471077w
				
	}
}
/**
 * 站内通知－消息
 * */
function message()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
		
	$json = vcurl(EXTLINK,'act=GetMassInfo&id='.$member_all['id']);
	$info = ParsingJson($json);
		
	require dir_url('subject/htx/template/').__FUNCTION__.'.html';
}
/**
 * 返回私信
 * */
function ReturnMessage()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	$username = str_replace(array(' ','  ',"\n","\t"), array('','',"",""), urldecode(htmlspecialchars($_GET['username'],ENT_QUOTES,'UTF-8',false)));
	$id = str_replace(array(' ','  ',"\n","\t"), array('','',"",""), urldecode(htmlspecialchars($_GET['id'],ENT_QUOTES,'UTF-8',false)));
	$userid = str_replace(array(' ','  ',"\n","\t"), array('','',"",""), urldecode(htmlspecialchars($_GET['userid'],ENT_QUOTES,'UTF-8',false)));
	$page = $_GET['page']==null?1:$_GET['page'];
	//获取回答者信息
	$json = vcurl(EXTLINK,'act=mGetOnceInfo&id='.$userid);
	$info = ParsingJson($json);
	//我的回答
	$json = vcurl(EXTLINK,'act=myQuestion&id='.$id.'&page='.$page);
	$rows = ParsingJson($json);

	require dir_url('subject/htx/template/').'personal-3-1.html';
}
/**
 * 展示问题－页面
 * */
function Answer()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	$userid = str_replace(array(' ','  ',"\n","\t"), array('','',"",""), urldecode(htmlspecialchars($_GET['userid'],ENT_QUOTES,'UTF-8',false)));
	$json = vcurl(EXTLINK,'act=mGetJueGuo&userid='.$userid.'&data='.json_encode($member_all));
	$info = ParsingJson($json);
	
	require dir_url('subject/htx/template/').'answer.html';
}
/**
 * 发布－页面
 * */
function found()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	//GetRecommend_fx
	$json = vcurl(EXTLINK,'act=GetRecommend_fx&page=1');
	$info = ParsingJson($json);
	$rows = $info['rows'];
	
	require dir_url('subject/htx/template/').'found.html';
}
/**
 * 会员中心关注与取消关注
 * */
function focus_on()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';

	$json = vcurl(EXTLINK,'act=GetFollwInfo&page='.($_GET['page']==''?1:$_GET['page']).'&userid='.$member_all['id']);
	$info = ParsingJson($json);
	
	$row = $info['row']['rows'];
	$counts = $info['row']['totalRows'];
	
	$row2 = $info['row2']['rows'];
	$counts2 = $info['row2']['totalRows'];
	
	require dir_url('subject/htx/template/').'focus-on.html';
}
/**
 * 火天信资料库平台服务相关协议
 * */
function RelevantAgreement()
{
	//公共文件
	include dir_url('subject/htx/').'common.php';
	
	require dir_url('subject/htx/template/').'relevantagreement.html';
}
###############################################################注册$.ajax###################################################################
function mAddAttention_moble()
{#关注
	$userid = $_POST['userid'];//用户ID
	$useras = $_POST['username'];//用户ID
	$myid = $_POST['myid'];//用户ID
	//mAbolishConcern
	$json = vcurl(EXTLINK,'act=mAddAttention&userid='.$userid.'&username='.$useras.'&myid='.$myid);
	echo $json;
}
function mAbolishConcern()
{#取消关注
	$userid = $_POST['id'];//用户ID
	$useras = $_POST['useras'];//用户ID
	$myid = $_POST['myid'];//用户ID
	//mAbolishConcern
	$json = vcurl(EXTLINK,'act=mAbolishConcern&id='.$userid.'&useras='.$useras.'&myid='.$myid);
	echo $json;
}
function SetUserInfo_all()
{#重置用户名
	session_start();

	//GetUserInfo_alls
	$json = vcurl(EXTLINK,'act=GetUserInfo_alls&users='.$_POST['users']);
	$info = ParsingJson($json);
		
	$_SESSION['log_on_user']=null;
	unset($_SESSION['log_on_user']);
	
	$_SESSION['log_on_user'] = $info;	
}
function GetOneChange()
{#获取随机头像
	$json = vcurl(EXTLINK,'act=mExchangePic');
	echo $json;
}
function mGetJinErAll_once()
{#获取单选金额
	session_start();

	$numbers = $_POST['numbers'];
	$flag = $_POST['flag'];
	$sum = $_POST['sum'];
	$mentni = $_POST['free'];

	$json = vcurl(EXTLINK,'act=GetVideoInfo_payAll&consid='.$_POST['consid'].'&data='.$_POST['data']);
	$info = ParsingJson($json);
		
	if($info['error'] != 0 )
	{#如果数据为空
		echo json_encode( array('error'=>'1','txt'=>'返回空数据了') );exit;
	}
		
	$row = $info['txt']['row'];#本章节内容	
	$coverapth_json = json_decode($row['coverapth'],true);//图片路径
	$coverapth = EXTURL_DATA.$coverapth_json[0];
		
	$RowsAll = $info['txt']['RowsAll'];#讲节排序
	$a = $info['txt']['a'];#免费的
	$b = $info['txt']['b'];#已经支付的
	$free = $info['txt']['free'];//获取价格
	$t = $info['txt']['t'];#获取未支付的总数
	$a_set = $info['txt']['a_set'];/*2套以上才打折*/	
	$discount = $info['txt']['discount'];/*打1~9折*/
		
	$hj = $_SESSION['mGetJinErAll_once'];
	$f_alls = 0;
	if(!empty($RowsAll))
	{
		foreach($RowsAll as $k=>$v)
		{
			if( $v['integral'] != 0 && $v['numbers'] == $numbers )
			{				
				$f_alls = $v['integral'];
				break;
			}
		}
				
	}
	if( $flag == 1 )
	{#原来基础上累加
		$_SESSION['mGetJinErAll_once'] = $hj+$f_alls;
		$_SESSION['numbersList'][] = $numbers;
	}
	else
	{#原来基础上累减	
		if( $sum > 0 )
		{		
			$_SESSION['mGetJinErAll_once'] = $hj-$f_alls;
			$array1 = $_SESSION['numbersList'];
			$array2 = array_flip($array1);
			unset($array2[$numbers]);
			$_SESSION['numbersList'] = array_flip($array2);
		}
		else
		{
			$_SESSION['mGetJinErAll_once'] = 0;
			$_SESSION['numbersList'] = array();
		}
	}
	#返回结果
	if( $sum == $t )
	{
		if( $t > $a_set )
		{#打折
			$_SESSION['mGetJinErAll'] = round($_SESSION['mGetJinErAll_once']*($discount/10),2);
		}
		else 
		{#不打折
			$_SESSION['mGetJinErAll'] = $_SESSION['mGetJinErAll_once'];
		}
	}
	else
	{
		$_SESSION['mGetJinErAll'] = $_SESSION['mGetJinErAll_once'];
	}
	echo $_SESSION['mGetJinErAll'];
}
function mGetJinErAll()
{#获取全选金额
	session_start();
	
	$member_all = $_SESSION['pay_infos']['member'];
	
	if( $_POST['consid'] != '' )
	{#全选
		//GetVideoInfo_payAll
		$json = vcurl(EXTLINK,'act=GetVideoInfo_payAll&consid='.$_POST['consid'].'&data='.json_encode($member_all));
		$info = ParsingJson($json);
		
		if($info['error'] != 0 )
		{#如果数据为空
			echo json_encode( array('error'=>'1','txt'=>'返回空数据了') );exit;
		}
		
		$row = $info['txt']['row'];#本章节内容	
		$coverapth_json = json_decode($row['coverapth'],true);//图片路径
		$coverapth = EXTURL_DATA.$coverapth_json[0];
		
		$RowsAll = $info['txt']['RowsAll'];#讲节排序
		$a = $info['txt']['a'];#免费的
		$b = $info['txt']['b'];#已经支付的
		$free = $info['txt']['free'];//获取价格
		$t = $info['txt']['t'];#获取未支付的总数
		$a_set = $info['txt']['a_set'];/*2套以上才打折*/	
		$discount = $info['txt']['discount'];/*打1~9折*/
				
		$hj = 0;
		$numberList = array();
		if(!empty($RowsAll))
		{
			foreach($RowsAll as $k=>$v)
			{
				if( $v['integral'] != 0 )
				{
					if( !in_array($v['numbers'], $v['numbers_pay']) )
					{
						$hj +=  $v['integral'];
						$numberList[] = $v['numbers'];
					}
				}
			}
			if($t>$a_set)
			{#打折
				$_SESSION['mGetJinErAll'] = round($hj*($discount/10),2);
				echo round($hj*($discount/10),2);			
			}
			else 
			{
				$_SESSION['mGetJinErAll'] = $hj;
				echo $hj;
			}		
		}
		$_SESSION['mGetJinErAll_once'] = $hj;
		$_SESSION['numbersList'] = $numberList;
	}
	else 
	{#取消全选
		$_SESSION['mGetJinErAll'] = 0;
		$_SESSION['mGetJinErAll_once'] = 0;
		$_SESSION['numbersList'] = array();
		echo '0';
	}
}
function mAnswerPoint()
{#问答点赞
	$myid = $_POST['myid'];
	$id = $_POST['id'];
	$json = vcurl(EXTLINK,'act=mAnswerPoint&id='.$id.'&myid='.$myid);
    
    echo $json;
}
function mPayAttentionProblem()
{#关注问题
	$myid = $_POST['myid'];
	$id = $_POST['id'];
	$json = vcurl(EXTLINK,'act=mPayAttentionProblem&id='.$id.'&myid='.$myid);
    
    echo $json;
}
function mReplyContent()
{#回复回答者内容
	$data['body'] = $_POST['bodys'];
    $data['userid'] = $_POST['userid'];
    $data['notesid'] = $_POST['notesid'];
    $data['myid'] = $_POST['myid'];
    $data['pid'] = $_POST['pid'];
    $data['cipid'] = $_POST['cipid'];
    $data['fhpid'] = $_POST['fhpid'];
    $data['flname'] = $_POST['flname'];
    
    $json = vcurl(EXTLINK,'act=mReplyContent&'.http_build_query($data));
    
    echo $json;
}
function mQuestionAnswering()
{#回答问题
	$data['body'] = $_POST['body'];
    $data['userid'] = $_POST['userid'];
    $data['notesid'] = $_POST['notesid'];
    $data['myid'] = $_POST['myid'];
    $data['pid'] = $_POST['pid'];
    $data['cipid'] = $_POST['cipid'];
    $data['fhpid'] = $_POST['fhpid'];
    $data['flname'] = $_POST['flname'];
    
    $json = vcurl(EXTLINK,'act=mQuestionAnswering&'.http_build_query($data));
    
    echo $json;
}
function SetUserInfo()
{#保存个人设置
	session_start();
	
	$_SESSION['log_on_user']=null;
	unset($_SESSION['log_on_user']);

	$data = ParsingJson($_POST['data']);	
	$_SESSION['log_on_user'] = $data['row'];
	if(!empty($data['row']))
	{
		echo 'OK';
	}
	else 
	{
		echo '0';
	}
}
function mSMS_Code()
{#获取手机验证码
	session_start();
	
	#生成验证码
	$code = mt_rand(10000, 99999);
	$_SESSION['m_virify'] = $code;
	
	$tel = $_POST['tel'];
	$flag = $_POST['flag'];
	
	$json = vcurl(EXTLINK,'act=mSMS_Code&tel='.$tel.'&flag='.$flag.'&code='.$code);
	echo $json;
}
function mMailVerification()
{#获取邮箱验证码
	session_start();
	
	#生成验证码
	$code = mt_rand(10000, 99999);
	$_SESSION['m_virify'] = $code;
	
	$email = $_POST['email'];
	$flag = $_POST['flag'];
	
	$json = vcurl(EXTLINK,'act=mMailVerification&email='.$email.'&flag='.$flag.'&code='.$code);
	//$getvry = ParsingJson($json);
	echo $json;
}
function mADDAboutQuestion()
{#手记提问
	session_start();
	$virify1 = strtolower($_SESSION['virify']);
	$virify2 = strtolower($_POST['virify']);
	if( $virify1 == $virify2 )
	{
		#发送数据
		$post = 'act=mADDAboutQuestion&notesid='.$_POST['notesid'].'&title='.$_POST['title'].'&body='.$_POST['body'].'&fhpid='.$_POST['fhpid'].'&cipid='.$_POST['cipid'].'&myid='.$_POST['myid'].'&userid='.$_POST['userid'].'&flname='.$_POST['flname'];
		$json = vcurl(EXTLINK,$post);
		echo $json;
	}
	else 
	{
		echo json_encode( array('error'=>'1','txt'=>'验证码不正确') );exit;
	}
}
function mGetThemaInfo_page()
{#专题栏目－加载更多
	$id = $_POST['id'];
	$page = $_POST['page'];	
	$post = 'act=GetThemaInfo_page&user='.$id.'&page='.$page;
	$json = vcurl(EXTLINK,$post);
	$rows = ParsingJson($json);	
	$html = '';
	if(!empty($rows['rows']))
	{
		foreach($rows['rows'] as $ks=>$vs)
		{
			$html .= '<li>';
			$html .= '<div class="process-li-fl note-li-fl">';
			$html .= '<span>'.$ks.'</span>';
			$html .= '</div>';
		
			$html .= '<div class="process-li-fr note-div-1">';
			foreach($vs as $k=>$v)
			{
				$j=ParsingJson($v['cover']);
				$html .= '<div class="note-a">';
				$html .= '<div class="note-right">';
				$html .= '<strong>';
				if( $v['cover'] != '' )
				{
					$html .= '<a href="'.apth_url('?act=ContentsNotes&contid='.$v['id'].'&user='.$v['userid']).'"><img src="'.EXTURL.$j[0].'" alt="'.$v['title'].'"></a>';
				}
				$html .= '</strong><a href="'.apth_url('?act=ContentsNotes&contid='.$v['id'].'&user='.$v['userid']).'" class="note-a-div">';
				$html .= '<div class="note1-1">'.$v['title'].'</div>';
				$html .= '<div class="note1-2">'.$v['flname'].'</div>';
				$html .= '<div class="note1-3">'.$v['ymd'].'</div>';
				$html .= '</a>';
				$html .= '</div>';
				$html .= '</div>';
			}
			$html .= '</div>';
			$html .= '</li>';
		}
		echo json_encode(array("error"=>"0","txt"=>$html,'page'=>$rows['totalpage']));
	}
	else
	{
		echo json_encode(array("error"=>"1","txt"=>'加载完毕'));
	}
}
function mAllReadDelete()
{#删除消息
	$id = $_POST['sign'];
	$json = vcurl(EXTLINK,'act=mAllReadDelete&sign='.$id);
	$info = ParsingJson($json);
	echo $info;
}
function mAllRead()
{#获取全部标记为已读
	$id = $_POST['id'];
	$json = vcurl(EXTLINK,'act=mAllRead&id='.$id);
	$info = ParsingJson($json);
	echo $info;
}
function GetMass()
{#获取通知消息
	$id = $_POST['id'];
	$f = $_POST['f'];
	
	$json = vcurl(EXTLINK,'act=GetMassInfo&id='.$id);
	$info = ParsingJson($json);
	$html = '';
	if( $f == 1 )
	{
		if($info['error'] == '0')
		{
			foreach($info['txt']['wd'] as $k=>$v)
			{
				$html .= '<li>';
	        	$html .= '<div class="message-a">';
	        	$html .= '<h3>系统通知</h3>';
	        	$html .= '<p>'.$v['publitime'].'</p>';
	        	$html .= '</div>';
	        	$html .= '<a href="javascript:void(0);" class="message-text">';
	        	$html .= $v['body'];
	        	$html .= '</a>';
	        	$html .= '<div class="del-btn" onclick="Delete(this,'.$v['id'].');"></div>';
	        	$html .= '</li>';
			}
			echo json_encode(array("error"=>"0","txt"=>$html));
		}
		else
		{
			echo json_encode(array("error"=>"1","txt"=>'<li style="height:200px;line-height:200px;text-align:center;color:#bba9a9;">暂无任何通知</li>'));
		}
	}
	elseif( $f == 2 || $f == 3 )
	{		
		if($info['error'] == '0')
		{
			foreach($info['txt']['yd'] as $k=>$v)
			{
				$html .= '<li>';
	        	$html .= '<div class="message-a">';
	        	$html .= '<h3>系统通知</h3>';
	        	$html .= '<p>'.$v['publitime'].'</p>';
	        	$html .= '</div>';
	        	$html .= '<a href="javascript:void(0);" class="message-text">';
	        	$html .= $v['body'];
	        	$html .= '</a>';
	        	$html .= '<div class="del-btn" onclick="Delete(this,'.$v['id'].');"></div>';
	        	$html .= '</li>';
			}
			echo json_encode(array("error"=>"0","txt"=>$html));
		}
		else
		{
			echo json_encode(array("error"=>"1","txt"=>'<li style="height:200px;line-height:200px;text-align:center;color:#bba9a9;">暂无任何通知</li>'));
		}
	}
		
}
function mGetUserinfo()
{#获取用户信息
	$tel = $_POST['tel'];
	$post = 'act=mGetUserinfo&tel='.$tel;
	$json = vcurl(EXTLINK,$post);
	echo $json;
}
function MAddAttention()
{#用户关注
	$userid = $_POST['userid'];
	$myid = $_POST['myid'];
	$post = 'api=AddAttention&userid='.$userid.'&myid='.$myid;
	$json = vcurl(EXTLINK_IN,$post);
	echo $json;
}
function update_pwd()
{#修改密码
	$username = $_POST['username'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];
	
	if($password1=='')
	{
		echo json_encode(array('msg'=>'Error','txt'=>'请输入新密码'));exit;
	}
	if($password2=='')
	{
		echo json_encode(array('msg'=>'Error','txt'=>'请再次输入密码'));exit;
	}
	if($password1!=$password2)
	{
		echo json_encode(array('msg'=>'Error','txt'=>'两次密码不一致'));exit;
	}
	
	$post = 'act=update_wpd&username='.$username.'&password='.$password1;
	$int = vcurl(EXTLINK,$post);
	if($int)
	{
		session_start();
		$_SESSION['log_on_user']=null;
		unset($_SESSION['log_on_user']);
		
		echo json_encode(array('msg'=>'OK'));
	}
	else
	{
		echo json_encode(array('msg'=>'Error','txt'=>'修改失败'));
	}
}
#找回密码提交
function RetrievePassword()
{
	session_start();
	$email_virify = $_SESSION['email_virify'];
	$model_virify = $_SESSION['model_virify'];
	
	$username = str_replace(array(' ','  ',"\n","\t"), array('','',"",""), urldecode(htmlspecialchars($_POST['username'],ENT_QUOTES,'UTF-8',false)));
	if($username == '')
	{
		echo json_encode(array('msg'=>'Error','txt'=>'请输入帐号'));exit;
	}
	else 
	{
		if( strrpos($username, '@') == false )
		{#手机 
			if( !preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $username) )
			{
				echo json_encode(array('msg'=>'Error','txt'=>'手机号不正确'));exit;
			}
			$post = 'act=user_existence_yes&datas='.$username;
			$int = vcurl(EXTLINK,$post);
			if( $int == 0 )
			{
				echo json_encode(array('msg'=>'Error','txt'=>'该帐号不存在'));exit;
			}
		}
		else 
		{#邮箱
			if( !preg_match("/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/",$username) )
			{
				echo json_encode(array('msg'=>'Error','txt'=>'邮箱不正确'));exit;
			}
			$post = 'act=user_existence_yes&datas='.$username;
			$int = vcurl(EXTLINK,$post);
			if( $int == 0 )
			{
				echo json_encode(array('msg'=>'Error','txt'=>'该帐号不存在'));exit;
			}
		}
	}
	$virify = str_replace(array(' ','  ',"\n","\t"), array('','',"",""), urldecode(htmlspecialchars($_POST['virify'],ENT_QUOTES,'UTF-8',false)));
	
	if( $virify == '' )
	{	
		echo json_encode(array('msg'=>'Error','txt'=>'请输入验证码'));exit;
	}
	else 
	{
		if( strrpos($username, '@') == false )
		{
			if( $virify != $model_virify )
			{
				echo json_encode(array('msg'=>'Error','txt'=>'验证码不正确'));exit;
			}
		}
		else
		{	
			if( $virify != $email_virify )
			{
				echo json_encode(array('msg'=>'Error','txt'=>'验证码不正确'));exit;
			}
		}
	}
	
	//删除掉验证码
	$_SESSION['email_virify']=null;
	$_SESSION['model_virify']=null;
	unset($_SESSION['email_virify']);
	unset($_SESSION['model_virify']);
	
	//修改密码
	$_SESSION['update_passwords'] = $username;
	
	echo json_encode(array('msg'=>'OK'));exit;
}
#找回密码邮件
function mPasswordMailbox()
{
	session_start();
		
	$email = str_replace(array(' ','  ',"\n","\t"), array('','',"",""), urldecode(htmlspecialchars($_POST['email'],ENT_QUOTES,'UTF-8',false)));//接收人邮箱
	if( $email == '' )
	{
		echo json_encode(array('msg'=>'Error','txt'=>'请提供邮箱'));exit;
	}
	else 
	{
		if( !preg_match("/^\w[-\w.+]*@([A-Za-z0-9][-A-Za-z0-9]+\.)+[A-Za-z]{2,14}$/",$email) )
		{
			echo json_encode(array('msg'=>'Error','txt'=>'邮箱不正确'));exit;
		}
	}
		
	#生成验证码
	$code = mt_rand(10000, 99999);	
	$_SESSION['email_virify'] = $code;
	
	#发送短信
	$post = 'act=mPasswordMailbox&email='.$email.'&code='.$code;
	$hRows = vcurl(EXTLINK,$post);	
	echo $hRows;	
}
#找回密码短信
function mPasswordSMS()
{
	error_reporting(0);
	
	session_start();
	
	$tel = str_replace(array(' ','  ',"\n","\t"), array('','',"",""), urldecode(htmlspecialchars($_POST['tel'],ENT_QUOTES,'UTF-8',false)));
	if( $tel == '' )
	{
		echo json_encode(array('msg'=>'Error','txt'=>'请提供手机号'));exit;
	}
	else 
	{
		if( !preg_match("/^0?(13|14|15|17|18)[0-9]{9}$/", $tel) )
		{
			echo json_encode(array('msg'=>'Error','txt'=>'手机号不正确'));exit;
		}
	}
	
	#生成验证码
	$code = mt_rand(10000, 99999);	
	$_SESSION['model_virify'] = $code;
	
	#发送短信
	$post = 'act=mPasswordSMS&tel='.$tel.'&code='.$code;
	$hRows = vcurl(EXTLINK,$post);	
	echo $hRows;	
}
function FileExtUPLoad()
{#文件外部上传搜索
	$page = str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['page'],ENT_QUOTES,'UTF-8',false)));//ID
	$search = str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['s'],ENT_QUOTES,'UTF-8',false)));//搜索内容
	$username = str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['username'],ENT_QUOTES,'UTF-8',false)));//用户名称
	$post = 'act=GetHistoryData&page='.$page.'&s='.$search.'&username='.$username;
	$hRows = vcurl(EXTERNAL,$post);		
	if( !empty( $hRows ) )
	{
		$jsons = json_decode( $hRows ,true);
		$hotRows = $jsons['rows'];
		$hotRows = $jsons['rows'];
	 	$rowTotal = $jsons['rowTotal'];
	 	$showTotal = $jsons['showTotal'];
	 	$pageTotal = $jsons['pageTotal'];
	 	$page = $jsons['page'];
	 	$html = '';
		if(!empty($hotRows)){
			foreach($hotRows as $k=>$v){
				$html .= '<li>';
				$html .= '<span>'.($k+1).'</span>';
				$html .= '<p class="p1">'.$v['filename'].'</p>';
				$html .= '<p>'.($v['filestate']==1?'资料':'视频').'</p>';
				$html .= '<p>'.$v['shortpublitime'].'</p>';
				$html .= '<a href="'.(EXTERNAL.'?act=Download&id='.$v['id']).'">下载</a>';
				$html .= '</li>';
			}
		}
		
		echo json_encode( array('error'=>'0','txt'=>$html,'rowTotal'=>$rowTotal,'showTotal'=>$showTotal,'pageTotal'=>$pageTotal,'page'=>$page) );
	}
	else 
	{
		echo json_encode( array('error'=>'1','txt'=>'Error id null to rows') );
	}
}
function get_loading_all()
{
	//参数
    $n = str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['n'],ENT_QUOTES,'UTF-8',false)));//章节编号
	$d = str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['d'],ENT_QUOTES,'UTF-8',false)));//系列ID
	$s = str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['s'],ENT_QUOTES,'UTF-8',false)));//排序
	$page = str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['page'],ENT_QUOTES,'UTF-8',false)));//页码
	$op = str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['op'],ENT_QUOTES,'UTF-8',false)));//评论者
	$flag = str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['flag'],ENT_QUOTES,'UTF-8',false)));//页码
	
	$post = '&n='.$n.'&d='.$d.'&s='.$s.'&page='.$page.'&op='.$op.'&flag='.$flag;
	$json = vcurl(EXTLINK,'act=loadding_click_data'.$post);
	
	echo $json;
}
function pl_dianzhan_up()
{
	//点击
	$d = str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['d'],ENT_QUOTES,'UTF-8',false)));
	$json = vcurl(EXTLINK_IN,'api=GiveVideo_up&d='.$d,'PHPSESSID='.$_COOKIE['PHPSESSID']);
	echo $json;
}
function pl_send_data()
{#发表评论
	$json = vcurl(EXTLINK,'act=Getpl_send_data&body='.$_POST['body'].'&numbers='.$_POST['numbers'].'&consid='.$_POST['consid'].'&username='.$_POST['username'].'&pid='.$_POST['pid'].'&maxs='.$_POST['maxs']);
	echo $json;
}
function SendSMS()
{#发关手机短信
	session_start();
	#生成验证码
	$uqid = mt_rand(100000, 999999);
	$_SESSION['tel_datas'] = $uqid;
	
	$tel = str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['datas'],ENT_QUOTES,'UTF-8',false)));
	
	//检测用户是否存在
	$json = vcurl(EXTLINK,'act=CheckingExists&username='.$tel);
	$DataList = ParsingJson($json);
	if( $DataList['error'] == 1 ){
		echo $json;exit;
	}
	
	//返回用户信息
	$json = vcurl(EXTLINK_IN,'api=ServicePlatform&code='.$uqid.'&name=火天信培训基地&tel='.$tel);
	echo $json;

}
function SendEmail()
{#发关邮件
	header("content-type:text/html;charset=utf-8");
	session_start();
	
	#生成验证码
	$uqid = mt_rand(100000, 999999);
	$_SESSION['email_datas'] = $uqid;
	
	$email = str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['datas'],ENT_QUOTES,'UTF-8',false)));
	
	//检测用户是否存在
	$json = vcurl(EXTLINK,'act=CheckingExists&username='.$email);
	$DataList = ParsingJson($json);
	if( $DataList['error'] == 1 ){
		echo $json;exit;
	}
	
	$html  = '<div style="border:1px solid #999999;margin:0 auto;width:50%;padding:0;">';
	$html .= '<p style="border-bottom:1px solid #999999;margin:0;height:50px;line-height:50px;text-align:center;font-size:16px;background:#e11515;color:#FFFFFF;">火天信建筑培训基地</p>';
	$html .= '<p style="height:50px;line-height:50px;margin:0;text-align:center;font-size:15px;">验证码：'.$uqid.'</p>';
	$html .= '</div>';
		
	//返回用户信息
	$json = vcurl(EXTLINK_IN,'api=EmailPlatform&cont='.$html.'&email='.$email.'&name=htx&subject='.urlencode('注册验证码'));
	echo $json;
	
}
function GetReset()
{#获取注册信息
	session_start();
	$tels = $_SESSION['tel_datas'];
	$emals = $_SESSION['email_datas'];
	
	$username = $_POST['username'];//用户名
	$password = $_POST['password'];//密码
	$checkbox = $_POST['checkbox'];//同意相关协议
	$virify = $_POST['virify'];//验证码
	
	if( $virify == '' )
	{#未输入
		echo json_encode(array('error'=>1,'txt'=>'请输入验证码'));exit;
	}
	else 
	{#已输入
		if( strrpos($username, '@') == false )
		{#手机
			if( $virify != $tels )
			{
				echo json_encode(array('error'=>1,'txt'=>'验证不正确'));exit;
			}
			unset($_SESSION['tel_datas']);
		}
		else 
		{#邮箱
			if( $virify != $emals )
			{
				echo json_encode(array('error'=>1,'txt'=>'验证不正确'));exit;
			}
			unset($_SESSION['email_datas']);
		}
	}
	
	if($checkbox!='on')
	{
		echo json_encode(array('error'=>1,'txt'=>'请同意火天信相关协议'));exit;
	}
	
	//返回用户信息
	$json = vcurl(EXTLINK,'act=GetReset&username='.$username.'&password='.$password);
	$DataList = ParsingJson($json);
	
	//判断错误号
	switch($DataList['error'])
	{
		case '0'://成功
			$name = $DataList['txt'];
			echo $json;
		break;
		case '1':
			echo $json;exit;
		break;
	}
	
	#自动登录
	$_SESSION['log_on_user'] = $name;
	setcookie('log_on_user',null,time()-1,'/');
}
function GetLogin()
{#获取登录结果
	session_start();
	
	$username = $_POST['username'];//用户名
	$password = $_POST['password'];//密码

	//返回用户信息
	$json = vcurl(EXTLINK,'act=GetLogin&username='.$username.'&password='.$password);
	$DataList = ParsingJson($json);
	
	//判断错误号
	switch($DataList['error'])
	{
		case '0'://成功
			$name = $DataList['txt'];
			echo $json;
		break;
		case '1'://请输入手机号或邮箱
			echo $json;exit;
		break;
		case '2'://密码不正确
			echo $json;exit;
		break;
		case '3'://登录失败
			echo $json;exit;
		break;
	}

	#七天自动登录
	$checked = strtolower(str_replace(array(' ','  ',"\n","\t","\s"), array('','',"","",""), urldecode(htmlspecialchars($_POST['checkbox'],ENT_QUOTES,'UTF-8',false))));
	if( $checked == 'on' )
	{
		#手机号或邮箱
		$_SESSION['log_on_user'] = $name;
		setcookie('log_on_user',$name,time()+(60*60*24*7),'/');
	}
	else
	{
		#手机号或邮箱
		$_SESSION['log_on_user'] = $name;
		setcookie('log_on_user',null,time()-1,'/');
	} 
		
}
function getsearch()
{#获取搜索结果	
	$style = htmlspecialchars($_POST['style'],ENT_QUOTES,'UTF-8',false);//1=资料；3=视频
	$title = htmlspecialchars($_POST['t'],ENT_QUOTES,'UTF-8',false);//标题
	$flagid = htmlspecialchars($_POST['flagid'],ENT_QUOTES,'UTF-8',false);//大类ID
	$pid = htmlspecialchars($_POST['pid'],ENT_QUOTES,'UTF-8',false);//小类ID
	$area = htmlspecialchars($_POST['area'],ENT_QUOTES,'UTF-8',false);//区域ID
	$page = htmlspecialchars($_POST['page'],ENT_QUOTES,'UTF-8',false);//页码
		
	$flag = htmlspecialchars($_POST['flag'],ENT_QUOTES,'UTF-8',false);//1=大类,2通用,3区域,4视频,5搜索框
	
	$json = vcurl(EXTLINK,'act=phseach&style='.$style.'&page='.$page.'&flag='.$flag.'&area='.$area.'&pid='.$pid.'&flagid='.$flagid.'&t='.$title);
	//echo $json;exit;
	$DataList = ParsingJson($json);
	//总页数
	$pageTotal = $DataList['pageTotal'];
	if($page>$pageTotal)
	{
		echo 'end';exit;//大于页数
	}
	//print_r($DataList);exit;
	$html  = '';
	if($DataList['rows']!='end')
	{
		if( $style == 1 )
		{
			foreach($DataList['rows'] as $k=>$v)
			{
				$j=ParsingJson($v['coverapth']);
				$html .= '<li>';
				$html .= '<a href="'.apth_url('?act=data_content&consid='.$v['id']).'" class="ul-learn-a">';
				$html .= '<img src="'.get_pixels($DataList['link1'].$j[0],350,230).'" alt="'.$v['title'].'">';
				$html .= '</a>';
				$html .= '<div class="middle">';
				$html .= '<h3><a href="'.apth_url('?act=data_content&consid='.$v['id']).'">'.subString($v['title'],11).'</a></h3>';
				$html .= '<section>';
				$html .= '<span>'.($v['statefile']==1?'通用':$v['areasmax']).'</span>';
				$html .= '<span>'.$v['flname'].'</span>';
				$html .= '<b>'.($v['integral']!=0?'&yen;'.$v['integral']:'免费').'</b>';
				$html .= '</section>';
				$html .= '</div>';
				$html .= '<a href="'.(apth_url('?act=data_content&consid='.$v['id'])).'" class="learn-btn">下载</a>';					
				$html .= '</li>';
			}	
		}
		else 
		{
			foreach($DataList['rows'] as $k=>$v)
			{
				$j=ParsingJson($v['coverapth']);
				$html .= '<li>';
				$html .= '<a href="data-details.html" class="ul-learn-a">';
				$html .= '<img src="'.get_pixels($DataList['link2'].$j[0],350,230).'" alt="'.$v['title'].'">';
				$html .= '<b>'.($v['tvlength']==0?'':formatSeconds($v['tvlength'])).'</b>';
				$html .= '</a>';
				$html .= '<div class="middle">';
				$html .= '<h3>'.subString($v['title'],11).'</h3>';
				$html .= '<section>';
				$html .= '<span>'.($v['statefile']==1?'通用':$v['areasmax']).'</span>';
				$html .= '<span>'.$v['flname'].'</span>';
				$html .= '<b>'.($v['integral']!=0?'&yen;'.$v['integral']:'免费').'</b>';
				$html .= '</section>';
				$html .= '</div>';
				$html .= '<a href="" class="learn-btn"><img src="'.apth_url('subject/htx/images/icon-17.png').'" alt="观看"></a>';
				$html .= '</li>';
			}	
		}	
	}
	else 
	{
			$html .= '<li style="line-height:50px;text-align:center;color:#999999;">对不起，没有找到符合条件的资料！</li>';
	}
	echo $html;
}
function getzfl()
{#获取子分类
	$id = htmlspecialchars($_POST['id'],ENT_QUOTES,'UTF-8',false);
	if($id!='all')
	{
		$json = vcurl(EXTLINK,'act=GetDataFL&pid='.$id);
		$DataFL = ParsingJson($json);
		$fl = $DataFL['rows'];
	}
	if( !empty($fl) )
	{
		echo json_encode(array('rows'=>$fl));
	}
	else 
	{
		echo json_encode(array('rows'=>'end'));
	}
}
function getzfl_video()
{#获取子分类
	$id = htmlspecialchars($_POST['id'],ENT_QUOTES,'UTF-8',false);
	$json = vcurl(EXTLINK,'act=GetDataFL_video&pid='.$id);
	$DataFL = ParsingJson($json);
	$fl = $DataFL['rows'];
	if( !empty($fl) )
	{
		echo json_encode(array('rows'=>$fl));
	}
	else 
	{
		echo json_encode(array('rows'=>'end'));
	}
}
###############################################注册程序######################################################################################
/**
 * 提交提问内容
 * */
function mQuestions()
{
	session_start();
	$virify1 = strtolower($_SESSION['virify']);
	$virify2 = strtolower($_POST['virify']);
	if($virify1 == $virify2)
	{		
		//发起请求
		$json = vcurl(EXTLINK,'act=mADDAboutQuestion_all&title='.$_POST['title'].'&body='.$_POST['body'].'&fhpid='.$_POST['fhpid'].'&cipid='.$_POST['cipid'].'&myid='.$_POST['myid']);
		echo $json;
	}
	else
	{
		$_SESSION['virify']=null;
		unset($_SESSION['virify']);
		echo json_encode(array('error'=>'1','txt'=>'验证码不正确'));
	}
}
/**
 * 记录导航点击
 * */
function SetFlag()
{
	session_start();
	$flag = $_POST['flag'];
	$_SESSION['SetFlag_in_id'] = $flag;
}
/**
 * 退出登录
 * */
function SignOut()
{
	session_start();
	$_SESSION['log_on_user']=null;
	unset($_SESSION['log_on_user']);
	
	//安全退出
	header('location:'.apth_url());
}
/**
 * 分页加载更多
 * */
function GetLoadContent()
{
	$pid = $_POST['pid']==''?'':$_POST['pid'];
	$areas = $_POST['area']==''?'':$_POST['area'];
	$flag = $_POST['flag']==''?'':$_POST['flag'];
	$page = $_POST['page']==''?'1':$_POST['page'];
	
	$json = vcurl(EXTLINK,'act=GetLoadContent&flag='.$flag.'&pid='.$pid.'&area='.$areas.'&page='.$page);
	$DataList = ParsingJson($json);	
	
	$html = '';
    if($DataList['rows']!='eixt')
    {
     	foreach( $DataList['rows'] as $k=>$v )
     	{
     		$j = ParsingJson($v['coverapth']);
     		if( $flag == 1 )
     		{
     			$html .= '<li>';
				$html .= '<a href="'.apth_url('?act=data_content&consid='.$v['id']).'" class="ul-learn-a">';
				$html .= '<img src="'.get_pixels($DataList['link1'].$j[0],350,230).'" alt="'.$v['title'].'">';
				$html .= '</a>';
				$html .= '<div class="middle">';
				$html .= '<h3><a href="'.apth_url('?act=data_content&consid='.$v['id']).'">'.subString($v['title'],11).'</a></h3>';
				$html .= '<section>';
				$html .= '<span>'.($v['statefile']==1?'通用':$v['areasmax']).'</span>';
				$html .= '<span>'.($v['flname']).'</span>';
				$html .= '<b>'.($v['integral']!=0?'&yen;'.$v['integral']:'免费').'</b>';
				$html .= '</section>';
				$html .= '</div>';
				$html .= '<a href="'.apth_url('?act=data_content&consid='.$v['id']).'" class="learn-btn">下载</a>';
				$html .= '</li>';
     		}
     		else 
     		{
     			$html .= '<li>';
				$html .= '<a href="#" class="ul-learn-a">';
				$html .= '<img src="'.get_pixels($DataList['link2'].$j[0],350,230).'" alt="'.$v['tags'].'">';
				$html .= '<b>'.($v['tvlength']==0?'':formatSeconds($v['tvlength'])).'</b>';
				$html .= '</a>';
				$html .= '<div class="middle">';
				$html .= '<h3>'.subString($v['tags'],11).'</h3>';
				$html .= '<section>';
				$html .= '<span>共'.$v['count'].'节</span>';
				$html .= '<span>'.$v['flname'].'</span>';
				$html .= '<b>'.($v['integral']!=0?'&yen;'.$v['integral']:'免费').'</b>';
				$html .= '</section>';
				$html .= '</div>';
				$html .= '<a href="" class="learn-btn"><img src="'.apth_url('subject/htx/images/icon-17.png').'" alt="看视频"></a>';
				$html .= '</li>';
     		}
     	}	        
    }
    else 
    {
    	$html = 'eixt';
    }   
    echo $html;
}
/**
 * 首页－推荐瀑布流
 * */
function GetRecommended()
{
	#接口组
	$InterfaceGroup = array('GetRecommend','MobileDownload','MobileVideo','GetHotTopics');
	#0=推荐;1=资料下载;2=视频培训;3=热门话题
	$flag = $_POST['flag'];
	#页码
	$page = $_POST['page'];
	
	#获取当前页码数据
	$json = vcurl(EXTLINK,'act='.$InterfaceGroup[$flag].'&page='.$page);	
	$data = ParsingJson($json);
	switch($flag)
	{
		case 0:
			$h = tuijian($data);#0=推荐
		break;
		case 1:
			$h = GetDownload($data);#1=资料下载
		break;
		case 2:
			$h = GetMobileVideo($data);#1=视频培训
		break;
		case 3:
			$h = GetHotTo($data);#3=热门话题
		break;
	}
	echo json_encode(array('rows'=>$h,'pageTotal'=>$data['pageTotal']));
}
#0=推荐
function tuijian($arr)
{
	$html  = '';
	if($arr['rows']!='end')
	{
		foreach($arr['rows'] as $k=>$v)
		{
			$j = ParsingJson($v['coverapth']);
			if( $v['typestate'] == 1 )
			{
				$html .= '<li>';
				$html .= '<a href="'.apth_url('?act=data_content&consid='.$v['id']).'" class="ul-learn-a">';
				$html .= '<img src="'.get_pixels($arr['link1'].$j[0],350,230).'" alt="'.$v['title'].'">';
				$html .= '</a>';
				$html .= '<div class="middle">';
				$html .= '<h3><a href="'.apth_url('?act=data_content&consid='.$v['id']).'">'.subString($v['title'],11).'</a></h3>';
				$html .= '<section>';
				$html .= '<span>'.($v['statefile']==1?'通用':$v['areasmax']).'</span>';
				$html .= '<span>'.$v['flname'].'</span>';
				$html .= '<b>'.($v['integral']!=0?'&yen;'.$v['integral']:'免费').'</b>';
				$html .= '</section>';
				$html .= '</div>';
				$html .= '<a href="'.apth_url('?act=data_content&consid='.$v['id']).'" class="learn-btn">下载</a>';
				$html .= '</li>';
			}
			else
			{
				$html .= '<li>';
				$html .= '<a href="" class="ul-learn-a">';
				$html .= '<img src="'.get_pixels($arr['link2'].$j[0],350,230).'" alt="'.$v['tags'].'">';
				$html .= '<b>'.$v['tvlength']==0?'':formatSeconds($v['tvlength']).'</b>';
				$html .= '</a>';
				$html .= '<div class="middle">';
				$html .= '<h3><a href="">'.subString($v['tags'],11).'</a></h3>';
				$html .= '<section>';
				$html .= '<span>'.$v['statefile']==1?'通用':$v['areasmax'].'</span>';
				$html .= '<span>'.$v['flname'].'</span>';
				$html .= '<b>'.$v['integral']!=0?'&yen;'.$v['integral']:'免费'.'</b>';
				$html .= '</section>';
				$html .= '</div>';
				$html .= '<a href="" class="learn-btn"><img src="'.apth_url('/subject/htx/images/icon-17.png').'" alt=""></a>';
				$html .= '</li>';
			}
		}
	}
	return $html;
}
#1=资料下载
function GetDownload($arr)
{
	$html  = '';
	if($arr['rows']!='end')
	{
		foreach($arr['rows'] as $k=>$v)
		{
			$j = ParsingJson($v['coverapth']);
			$html .= '<li>'; 
			$html .= '<a href="'.apth_url('?act=data_content&consid='.$v['id']).'" class="ul-learn-a">';
			$html .= '<img src="'.get_pixels($arr['link1'].$j[0],350,230).'" alt="'.$v['title'].'">';
			$html .= '</a>';
			$html .= '<div class="middle">';
			$html .= '<h3><a href="'.apth_url('?act=data_content&consid='.$v['id']).'">'.subString($v['title'],11).'</a></h3>';
			$html .= '<section>';
			$html .= '<span>'.$v['statefile']==1?'通用':$v['areasmax'].'</span>';
			$html .= '<span>'.$v['flname'].'</span>';
			$html .= '<b>'.($v['integral']!=0?'&yen;'.$v['integral']:'免费').'</b>';
			$html .= '</section>';
			$html .= '</div>';
			$html .= '<a href="'.apth_url('?act=data_content&consid='.$v['id']).'" class="learn-btn">下载</a>';
			$html .= '</li>';
		}
	}
	return $html;
}
#2=视频培训
function GetMobileVideo($arr)
{
	$html  = '';
	if($arr['rows']!='end')
	{
		foreach($arr['rows'] as $k=>$v)
		{
			$j = ParsingJson($v['coverapth']);
			$html .= '<li>';			
			$html .= '<a href="'.apth_url('?act=videoinfo&contid='.$v['id']).'" class="ul-learn-a">';
			$html .= '<img src="'.get_pixels($arr['link2'].$j[0],350,230).'" alt="'.$v['tags'].'">';
			$html .= '<b>'.($v['tvlength']==0?'':formatSeconds($v['tvlength'])).'</b>';
			$html .= '</a>';
			$html .= '<div class="middle">';
			$html .= '<h3><a href="'.apth_url('?act=videoinfo&contid='.$v['id']).'">'.subString($v['tags'],11).'</a></h3>';
			$html .= '<section>';
			$html .= '<span>共'.$v['count'].'节</span>';
			$html .= '<span>'.$v['flname'].'</span>';
			$html .= '<b>'.($v['integral']!=0?'&yen;'.$v['integral']:'免费').'</b>';
			$html .= '</section>';
			$html .= '</div>';
			$html .= '<a href="" class="learn-btn"><img src="'.apth_url('/subject/htx/images/icon-17.png').'" alt=""></a>';
			$html .= '</li>';
		}
	}
	return $html;
}
#3=热门话题
function GetHotTo($arr)
{
	$html  = '';
	if($arr['rows']!='end')
	{
		foreach($arr['rows'] as $k=>$v)
		{
			$j = ParsingJson($v['cover']);
			$html .= '<li>';
			if($v['cover']!='')
			{
				$html .= '<a href="'.apth_url('?act=ContentsNotes&contid='.$v['id'].'&user='.$v['userid']).'" class="ul-learn-a">';
				$html .= '<img src="'.get_pixels($arr['link1'].$j[0],350,230).'" alt="'.$v['title'].'">';
				$html .= '</a>';
			}
			$html .= '<div class="middle middle1">';
			$html .= '<h3><a href="'.apth_url('?act=ContentsNotes&contid='.$v['id'].'&user='.$v['userid']).'">'.subString($v['title'],11).'</a></h3>';
			$html .= '<section>';
			$html .= '<span>'.subString($v['description'],30).'</span>';
			$html .= '</section>';
			$html .= '</div>';
			$html .= '<div class="right">#【'.$v['username'].'】第'.$v['id'].'期 #</div>';
			$html .= '</li>';
		}
	}
	return $html;
}
/**
 * 返回私信-递归
 * */
function GetMyQ($arr){	
	 
	 $array=array_reverse($arr);
	 
	 foreach( $array as $key => $val )
	 {  
	 	$html .= '<div class="message-a message-b">';	 	
		$html .= '<img src="'.$val['pic'].'" alt="'.$val['useras'].'" />';
		$html .= '<div class="message-text26"><span>回复：</span>'.$val['body'].'</div>';
		$html .= '</div>';
        		
        if( $val['child'] != array() )
        {
        	$array2=array_reverse($val['child']);
        	foreach( $array2 as $key2 => $val2 )
	 		{ 
	        	$html .= '<div class="message-a message-b">';	 	
				$html .= '<img src="'.$val2['pic'].'" alt="'.$val2['useras'].'" />';
				$html .= '<div class="message-text26"><span>回复：</span>'.$val2['body'].'</div>';
				$html .= '</div>';
				if( $val2['child'] != array() )
        		{
					GetMyQ($val2['child']);
        		}
	 		}
        	
        }
    }  
     
    echo $html;                     	
}
/**
 * 提交数据－绑定邮箱
 * */
function mMailVerification_post()
{
	session_start();
	
	$code = strtolower($_SESSION['m_virify']);
	$virify = strtolower($_POST['virify']);
	$user_id = $_POST['user_id'];
	$email = $_POST['email'];
	
	if( $code == $virify )
	{
		$_SESSION['m_virify']=null;
		unset($_SESSION['m_virify']);
		
		$_SESSION['log_on_user']=null;
		unset($_SESSION['log_on_user']);
						
		//提交数据		
		$json = vcurl(EXTLINK,'act=mBindEmail&user_id='.$user_id.'&userName='.$email);
		
		$data = ParsingJson($json);
		$_SESSION['log_on_user'] = $data['row'];//登录信息	
		
		echo $json;		
	}
	else 
	{
		echo json_encode(array("error"=>'1',"txt"=>"验证码不正确"));
	}
}
/**
 * 提交数据－绑定手机
 * */
function mBindTel()
{
	session_start();
	
	$code = strtolower($_SESSION['m_virify']);
	$virify = strtolower($_POST['virify']);
	
	$user_id = $_POST['user_id'];
	$tel = $_POST['tel'];
	
	if( $code == $virify )
	{
		$_SESSION['m_virify']=null;
		unset($_SESSION['m_virify']);
		
		$_SESSION['log_on_user']=null;
		unset($_SESSION['log_on_user']);
		
		//提交数据		
		$json = vcurl(EXTLINK,'act=mBindTel&user_id='.$user_id.'&userName='.$tel);
		
		$data = ParsingJson($json);
		$_SESSION['log_on_user'] = $data['row'];//登录信息	
		
		echo $json;
	}
	else 
	{
		echo json_encode(array("error"=>'1',"txt"=>"验证码不正确"));
	}
}
/**
 * QQ快捷登录
 * */
function QQLogin_htx()
{
	$url = urldecode($_GET['call']);
	$flag = urldecode($_GET['flag']);
	
	#请求授权，传一个回调地址 http://www.gxhtx.com/qq/check_fans.php?page_id=50140137
	header('location:http://www.gxhtx.com/qq/index.php?api='.$url.'&flag='.$flag);
}
#QQ授权调回数据 
function QReturnData_url()
{
	session_start();
	
	$fl = $_GET['flag'];
		
	$data['qqname'] = htmlspecialchars($_GET['username'],ENT_QUOTES,'UTF-8',false);
	$data['username'] = $data['qqname'];
	$data['qqopenid'] = $_GET['openid'];
	$data['qq'] = 'ID:'.mt_rand(100000, 999999);//随机给个ID
	$data['sex'] = $_GET['sex']=='男'?'1':'2';
	$data['province'] = $_GET['province'];
	$data['city'] = $_GET['city'];
	$data['birthday'] = $_GET['birthday'];
	$data['pic'] = $_GET['pic'];
	$data['scope'] = '';
	$data['abstract'] = '';
	$data['background'] = '';
	$data['identity'] = '';	
	$data['powername'] = '普通会员';
	$data['autograph'] = '这家伙很懒，什么都没留下！';
	$data['binding_qq'] = 1;	
	$data['publitime'] = time();
	$data['userip'] = getIP();
	$data['power'] = 1;
		
	//GetQQLogin
	$json = vcurl(EXTLINK,'act=GetQQLogin&openid='.$_GET['openid']);	
	$int = ParsingJson($json);

	if( $int['qq'] == '' )
	{#第一次使用授权 
		vcurl(EXTLINK,'act=InsertInTo_qqinfo&data='.json_encode($data));
		
		$json = vcurl(EXTLINK,'act=GetUserInfo_qqopenid&qqopenid='.$_GET['openid']);		
		$name = ParsingJson($json);
		
		if( $fl == 1 )
		{						
			$_SESSION['log_on_user'] = $name;
			setcookie('log_on_user',null,time()-1,'/');			
		}
		else 
		{
			$_SESSION['log_on_user'] = $name;
			setcookie('log_on_user',null,time()-1,'/');
		}
		
		#POST工程网一份
		$post = http_build_query($_GET).'&flag1=coo04&qq='.$data['qq'];
		vcurl('http://www.htxgcw.com/other/userdatainter898989',$post);
		
		header('location:'.apth_url(''));
		
	}
	else 
	{#授权过
		$json = vcurl(EXTLINK,'act=GetUserInfo_qqopenid&qq='.$int['qq']);	
		$name = ParsingJson($json);
		
		if( $fl == 1 )
		{
			$_SESSION['log_on_user'] = $name;
			setcookie('log_on_user',null,time()-1,'/');			
		}
		else 
		{
			$_SESSION['log_on_user'] = $name;
			setcookie('log_on_user',null,time()-1,'/');	
		}
		
		#POST工程网一份
		$post = http_build_query($_GET).'&flag1=coo04&qq='.$int['qq'];
		vcurl('http://www.htxgcw.com/other/userdatainter898989',$post);
		
		header('location:'.apth_url(''));
	}
}
#用户充值第一站-跳转点
function UserChZhPents()
{
	$m = $_GET['number'];
	
	#获取价格
	if( $m == 1 )
	{
		$data['subject'] = '充值300元送200元=500元优惠装'; //商口名
		$data['p'] = 300;  //价格
		$data['s'] = 200;  //送
		$data['ordersn'] = 'HTX'.date('YmdHis').mt_rand(10000,99999); //订单号
	}
	elseif( $m == 2 )
	{
		$data['subject'] = '充值600元送500元=1100元特惠装'; //商口名
		$data['p'] = 600;  //价格
		$data['s'] = 500;  //送
		$data['ordersn'] = 'HTX'.date('YmdHis').mt_rand(10000,99999); //订单号
	}
	elseif( $m == 3 )
	{
		$data['subject'] = '充值1200元送800元=2000元超值装'; //商口名
		$data['p'] = 1200;  //价格
		$data['s'] = 800;  //送
		$data['ordersn'] = 'HTX'.date('YmdHis').mt_rand(10000,99999); //订单号
	}
	else 
	{
		echo "非法提交";exit;
	}
	
	#设置价格
	$subject = $data['subject']; //商口名
	$free = $data['p']; //价格
	$ordersn = $data['ordersn']; //订单号
	
	session_start();
	$_SESSION['user_recharge_next'] = array('subject'=>$subject,'free'=>$free,'ordersn'=>$ordersn,'s'=>$data['s']);
	
	//跳转至充值页面
	header('location:'.apth_url('?act=voucher_form_data'));
}
#用户充值第三站提交支付
function voucher_form_data()
{
	session_start();
	$pay_data = $_SESSION['user_recharge_next'];
	
	#获取会员信息
	$member_all = GetLoginInfo();
	$userid = $member_all['id']; //用户ID
	
	#请求参数
	$post_url = 'http://newjob.htxgcw.com/wapalipay/pay.php?pay=wapalipay';//提交地址
	$subject = trim ( $pay_data['subject'] ); // 商品名称
	$out_trade_no = trim ( $pay_data['ordersn'] ); // 订单号
	//$total_fee = trim ( $pay_data['free'] );//价格	
	$total_fee = '0.01';//测试	
	$paytype = '支付宝';//支付类型
		
	$json = vcurl(EXTLINK,'act=recharge_rows&ordersn='.$out_trade_no);
	$row = ParsingJson($json);
	
	if( $row['error'] == 1 )
	{#说明不存在-记录
		$data['userid'] = $userid;
		$data['rname'] = $subject;
		$data['free'] = $total_fee;
		$data['ordersn'] = $out_trade_no;
		$data['publitime'] = time();		
		$data['balance'] = ($total_fee+$pay_data['s']);
		$data['types'] = $paytype;
		$data['state'] = 0;
		
		$int = vcurl(EXTLINK,'act=recharge_insert&'.http_build_query($data));
	}
	else
	{#说明已经存在-修改
		if( $row['txt']['state'] == 2 )
		{
			//充值成功－跳转结果页面
			header('location:'.apth_url('?act=mypersonal_in&locat=8'));exit;
		}
		else 
		{
			$data['free'] = $total_fee;
			$data['balance'] = ($total_fee+$pay_data['s']);		
			$data['types'] = $paytype;
			$int = vcurl(EXTLINK,'act=recharge_update&ordersn='.$out_trade_no.'&'.http_build_query($data));
		}
	}	
	#POST参数
	echo '<form action="'.$post_url.'" method="post" id="frm">
			<input type="hidden" name="subject" value="'.$subject.'"/>
			<input type="hidden" name="out_trade_no" value="'.$out_trade_no.'"/>
			<input type="hidden" name="total_fee" value="'.$total_fee.'"/>
			<input type="hidden" name="flag_url" value="2"/>
		 </form><script>document.getElementById("frm").submit();</script>';		 
}
#支成功返回数据-同步返回
function voucher_callback()
{
		$out_trade_no = $_REQUEST['out_trade_no'];
		$times = time();
		$json = vcurl(EXTLINK,'act=recharge_rows&ordersn='.$out_trade_no);
		$row = ParsingJson($json);

		if( $row['error'] == 0 )
		{#已经存在
			if( $row['txt']['state'] != 2 )
			{#未支付 - 修改状态
				$data['state'] = 2;	
				$data['publitime'] = $times;
				$int = vcurl(EXTLINK,'act=recharge_results&ordersn='.$out_trade_no.'&'.http_build_query($data));
							
				if($int)
				{#计算充值余额
					
					#获取原来的余额
					$json = vcurl(EXTLINK,'act=recharge_mrow&id='.$row['txt']['userid']);
					$mrow = ParsingJson($json);
					$scr = $mrow['txt']['balance']==''?0:$mrow['txt']['balance'];
					
					#获取本次充值与原有余额相加
					$bc = $scr+$row['txt']['balance'];
					#记录本次充值
					vcurl(EXTLINK,'act=recharge_freeins&id='.$row['txt']['userid'].'&balance='.$bc);
					
					#异步邮件通知...暂时不需要		
					$body = '尊敬的用户，您在'.date('Y-m-d H:i').'充值成功。';
					vcurl(EXTLINK,'act=mail_notification&id='.$row['txt']['userid'].'&body='.$body.'&ordersn='.$out_trade_no);
				}
				//充值成功－跳转结果页面
				header('location:'.apth_url('?act=mypersonal_in&locat=8'));
			}
			else
			{#已支付-直接跳转结果页面
				//充值成功－跳转结果页面
				header('location:'.apth_url('?act=mypersonal_in&locat=8'));
			}
		}
		else
		{#不存在
			//充值成功－跳转结果页面
			header('location:'.apth_url());
		}
}
#支成功返回数据-异步返回
function voucher_notify()
{
		$out_trade_no = $_REQUEST['out_trade_no'];
		$times = time();
		$json = vcurl(EXTLINK,'act=recharge_rows&ordersn='.$out_trade_no);
		$row = ParsingJson($json);
		
		if( $row['error'] == 0 )
		{#已经存在
			if( $row['txt']['state'] != 2 )
			{#未支付 - 修改状态
				$data['state'] = 2;	
				$data['publitime'] = $times;
				$int = vcurl(EXTLINK,'act=recharge_results&ordersn='.$out_trade_no.'&'.http_build_query($data));
				if($int)
				{#计算充值余额
					
					#获取原来的余额
					$json = vcurl(EXTLINK,'act=recharge_mrow&id='.$row['txt']['userid']);
					$mrow = ParsingJson($json);
					$scr = $mrow['txt']['balance']==''?0:$mrow['txt']['balance'];
					
					#获取本次充值与原有余额相加
					$bc = $scr+$row['txt']['balance'];
					#记录本次充值
					vcurl(EXTLINK,'act=recharge_freeins&id='.$row['txt']['userid'].'&balance='.$bc);
					
					#异步邮件通知...暂时不需要		
					$body = '尊敬的用户，您在'.date('Y-m-d H:i').'充值成功。';
					vcurl(EXTLINK,'act=mail_notification&id='.$row['txt']['userid'].'&body='.$body.'&ordersn='.$out_trade_no);
				}
			}
		}
}