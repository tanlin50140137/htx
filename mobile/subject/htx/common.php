<?php
/**
 * 公共文件
 * */
#跳转终端
LocationTerminal('http://f.htxgcw.com');
#全局信息
$json = vcurl(EXTLINK,'act=GlobalInformation');
$global = ParsingJson($json);
#获取用户信息
$member_all = GetLoginInfo();
#检查登录状态
$boolstr = LoginCheck($member_all);
#获取选中状态
$state_select = GetFlag();
#获取消息状态
$json = vcurl(EXTLINK,'act=GetInspectionNotice&id='.$member_all['id']);
$Ins = ParsingJson($json);