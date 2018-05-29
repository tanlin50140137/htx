<?php
header('content-type:text/html;charset=utf-8');
/**
 *  入口文件 TanLin Mobile架构 
 * */
require 'public.php';

$act = $_REQUEST['act']==''?'index':$_REQUEST['act'];

if( $act!=null && function_exists( $act ) )
{
	$act();
}
else 
{
	echo $act.' - The interface does not exist or is occupied';
}