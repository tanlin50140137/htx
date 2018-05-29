<?php
/**
 * 自定义函数 TanLin Mobile架构
 * */
#点击量
function click_nmu($consid,$numbers)
{
	session_start();
	$xb = 'click_htx_number'.$consid.$numbers;
	if( !isset($_SESSION[$xb]) )
	{
		$_SESSION[$xb] = 1;
	}
	else 
	{
		$count = $_SESSION[$xb];	
		$count++;	
		$_SESSION[$xb] = $count;		
	}
	
	#每次会话只记录3次
	if( $_SESSION[$xb] < 2 )
	{
		//Getvideoclick
		vcurl(EXTLINK,'act=Getvideoclick&consid='.$consid.'&numbers='.$numbers);
	}
}
#获flag
function GetFlag()
{
	session_start();
	return $_SESSION['SetFlag_in_id'];
}
#处理xml
function xml_str($array)
{
	$xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
	$xml .= '<box>'."\n";
	foreach($array as $key=>$val)
	{
		$xml .= '<'.$key.'>'.$val.'</'.$key.'>'."\n";
	}
	$xml .= '</box>';
	return $xml;
}
#检查登录状态
function LoginCheck($userinfo)
{		
	if($userinfo!=null)
	{#已经登录
	
		$post = 'act=mGetUserinfo_all&username='.$userinfo['username'];
		$json = vcurl(EXTLINK,$post);		
		$arr = ParsingJson($json);		
		if($arr==false)
		{#用户信息被册除域已经不存在
			session_start();
			$_SESSION['log_on_user']=null;
			unset($_SESSION['log_on_user']);
			header('location:'.apth_url());exit;
		}
		
		return array('username'=>$userinfo['username'],'pic'=>$userinfo['pic']);
	}
	else 
	{#未登录
		return false;
	}
}
#获取用户名
function GetLoginInfo()
{
	session_start();
	if($_SESSION['log_on_user']!='')
	{
		//GetUserInfo_alls
		$post = 'act=GetUserInfo_alls&users='.GetUserNames($_SESSION['log_on_user']);
		$json = vcurl(EXTLINK,$post);
		$Datainfo = ParsingJson($json);
		
		return $Datainfo;
	}
	else
	{
		return null;
	} 
}
#如果真实姓名保密
function GetXingSecrecy($name,$secrecy,$subordinate)
{
	$xing = $name;
	if( $secrecy == 1 && $subordinate == 1 )
	{
		if( mb_strlen($useras,'utf-8') > 3 )
		{#四字名
			$xing = mb_substr($xing, 0,2,'utf-8').'老师';
		}
		else 
		{#四字名以下
			$xing = mb_substr($xing, 0,1,'utf-8').'老师';
		}
	}
	return $xing;
}
#获取用户名称
function GetUserNames($member_all)
{
	$u = '';
	if($member_all['tel']!='')
	{
		$u = $member_all['tel'];
	}
	else
	{
		if($member_all['email']!='')
		{
			$u = $member_all['email'];
		}
		else
		{
			if($member_all['qq']!='')
			{
				$u = $member_all['qq'];
			}
			else 
			{
				$u = $member_all['username'];
			}
		}
	}
	return $u;
}
#获取GCW_URL_IN
function GCW_URL_IN($u)
{
	if( $u == false )
	{
	 	$url = GCW_URL;
	}
	else
	{
		$url = GCW_URL.GetUserNames($u);
	}
	return $url;
}
#根目录url
function apth_url($url='')
{
	return APTH_URL.($url==''?'':'/'.$url);
}
#路由url
function site_url($url='')
{
	return SITE_URL.'/'.$url;
}
#路由dir
function base_url($dir='')
{
	return BASE_URL.'/'.$dir;
}
#路由dir
function dir_url($dir='')
{
	return DIR_URL.($dir==''?'':'/'.$dir);
}
#解析JSON格式,返回数组
function ParsingJson($json)
{
	$rows = array();
	if( $json != '' )
	{
		$rows = json_decode($json,true);
	}
	return $rows;
}
#获取IP
function getIP()
{
	static $realip;
	if (isset($_SERVER)){
	      if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
	          $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
	 	  } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
	         $realip = $_SERVER["HTTP_CLIENT_IP"];
	 	  } else {
	         $realip = $_SERVER["REMOTE_ADDR"];
	 	  }
	 } else {
	        if (getenv("HTTP_X_FORWARDED_FOR")){
	            $realip = getenv("HTTP_X_FORWARDED_FOR");
	        } else if (getenv("HTTP_CLIENT_IP")) {
	            $realip = getenv("HTTP_CLIENT_IP");
	        } else {
	            $realip = getenv("REMOTE_ADDR");
	        }
	 }
	 return $realip;
}
#时间戳转成标准时间
function formatSeconds($value) { 
	$theTime = intval($value);// 秒 
	$theTime1 = 0;// 分 
	$theTime2 = 0;// 小时 
	// alert(theTime); 
	if($theTime > 60) { 
		$theTime1 = intval($theTime/60); 
		$theTime = intval($theTime%60); 
		// alert(theTime1+"-"+theTime); 
		if($theTime1 > 60) { 
			$theTime2 = intval($theTime1/60); 
			$theTime1 = intval($theTime1%60); 
		} 
	} 
	$result = "".intval($theTime).""; 
	if($theTime1 > 0) { 
		$result = "".intval($theTime1).":".$result; 
	} 
	if($theTime2 > 0) { 
		$result = "".intval($theTime2).":".$result; 
	} 
	return $result; 
}
#转换时间
function get_day_formt($t)
{
	$int = time()-$t;
	if( $int < 86400 )
	{#秒->分->时 换算
		$i = 0;
		while ( $int >= 60 )
		{
			$int /= 60;
			$i++;
		}
		$ext = array('秒前','分钟前','小时前');
	}
	
	if( $int >= 86400 && $int < 2592000)
	{#时->天 换算
		$i = 0;
		while ( $int >= 86400 )
		{
			$int /= 86400;
			$i++;
		}
		$ext = array('小时前','天前');
	}
	if( $int >= 2592000 && $int < 31104000)
	{#天->月 换算
		$i = 0;
		while ( $int >= 2592000 )
		{
			$int /= 2592000;
			$i++;
		}
		$ext = array('天前','个月前');
	}
	if( $int >= 31104000 )
	{#月->年 换算
		$i = 0;
		while ( $int >= 31104000 )
		{
			$int /= 31104000;
			$i++;
		}
		$ext = array('个月前','年前');
	}
	return floor($int).$ext[$i];
}
#curl异步会话
function vcurl($url, $post = '', $cookie = '', $cookiejar = '', $referer = '') {
	$tmpInfo = '';
	$cookiepath = getcwd () . '. / ' . $cookiejar;
	$curl = curl_init ();
	curl_setopt ( $curl, CURLOPT_URL, $url );
	curl_setopt ( $curl, CURLOPT_USERAGENT, $_SERVER ['HTTP_USER_AGENT'] );
	if ($referer) {
		curl_setopt ( $curl, CURLOPT_REFERER, $referer );
	} else {
		curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 );
	}
	if ($post) {
		curl_setopt ( $curl, CURLOPT_POST, 1 );
		curl_setopt ( $curl, CURLOPT_POSTFIELDS, $post );
	}
	if ($cookie) {
		curl_setopt ( $curl, CURLOPT_COOKIE, $cookie );
	}
	if ($cookiejar) {
		curl_setopt ( $curl, CURLOPT_COOKIEJAR, $cookiepath );
		curl_setopt ( $curl, CURLOPT_COOKIEFILE, $cookiepath );
	}
	// curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt ( $curl, CURLOPT_TIMEOUT, 100 );
	curl_setopt ( $curl, CURLOPT_HEADER, 0 );
	curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
	$tmpInfo = curl_exec ( $curl );
	if (curl_errno ( $curl )) {
		// echo ' < pre > < b > 错误: < /b><br / > ' . curl_error ( $curl );
	}
	curl_close ( $curl );
	return $tmpInfo;
}
#改变图片像素
function get_pixels($dir,$x,$y)
{
	return apth_url("system/img_pixels.php?dir=$dir&x=$x&y=$y");
}
#截取字符串
function subString($string,$len)
{
	if(mb_strlen($string,'utf-8')>=$len)
	{
		$string = mb_substr($string, 0,$len,'utf-8').'...';
	}
	return $string;
}
#判断当前终端
function isMobile(){ 
    $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : ''; 
    $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';       
    function CheckSubstrs($substrs,$text){ 
        foreach($substrs as $substr) 
            if(false!==strpos($text,$substr)){ 
                return true; 
            } 
            return false; 
    }
    $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
    $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod'); 
              
    $found_mobile=CheckSubstrs($mobile_os_list,$useragent_commentsblock) || 
              CheckSubstrs($mobile_token_list,$useragent); 
              
    if ($found_mobile){ //手机
        return true; 
    }else{ //电脑
        return false; 
    } 
}
#跳转终端
function LocationTerminal($url)
{
	if( isMobile() == false )
	{#手机
		header('location:'.$url);
	}
}