<?php
/**
 * 配置文件 TanLin Mobile架构
 * */
$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
################################################################################################
#自动获取
$arr = explode('/', $_SERVER['REQUEST_URI']);
if(!empty($arr)){array_pop($arr);$site_url = join('/',$arr);}
#自动获取
define("SITE_URL", $sys_protocal.$_SERVER['HTTP_HOST'].$site_url);#绝对路径url
define("BASE_URL", $_SERVER['DOCUMENT_ROOT'].$site_url);#绝对路径dir

/**
 * 手动设置路由，域名指向，项目所在位置
 * */
define("APTH_URL", 'http://192.168.5.32/mobile');#绝对路径url

#自动获取
define("DIR_URL", $_SERVER['DOCUMENT_ROOT'].str_replace($sys_protocal.$_SERVER['HTTP_HOST'], '', APTH_URL));#绝对路径dir

/********************************************************************************************************************************************
 * 时区设置
 * */
date_default_timezone_set('PRC');
/**
 * 开发调试，Off关闭 || On开启
 * */
ini_set('display_errors', 'Off');

/**
 * 外部链接 
 * */
define("GCW_URL_Z", 'http://www.htxgcw.com/');
define("GCW_URL", 'http://www.htxgcw.com/other/datajump?user=');
define('EXTURL', 'http://f.htxgcw.com');
define('EXTURL_DATA', 'http://data.huotianxin.cn');
define('EXTLINK', 'http://f.htxgcw.com/subject/htx/api/data-info.php');
define('EXTLINK_IN', 'http://f.htxgcw.com/subject/htx/api/htx-api.php');
define('EXTERNAL', 'http://kehu.huotianxin.cn/external_interface.php');