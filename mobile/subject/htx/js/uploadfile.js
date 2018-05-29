/**
 * 多文件切割上传
 */
var xhr = null;
if (window.XMLHttpRequest)
{
	xhr = new XMLHttpRequest();
}
else if (window.ActiveXObject)
{
	xhr = new ActiveXObject("Microsoft.XMLHTTP");
}

//参属配置
var username = document.getElementById('username').value;
var t = document.getElementById('file1');
var u = 'http://kehu.huotianxin.cn/upload.php';
var ProgressObj = document.getElementById('progress1');
var PixelSize = 2*1024*1024;
tar = 0;
var ftval = 0;
var radioFlag = false;
var colse = null;

$(".GetFile_data a").click(function(){
	var i = $(this).attr('data-file');
	radioFlag = ftval = i;
});
	
//调用函数
function CreateObject()
{	
	if( username == '' )
	{
		layer.open({
		    content: '请先登录'
		    ,skin: 'msg'
		    ,time: 2
		});
		return false;
	}
	if(radioFlag == false){
		layer.open({
		    content: '请选择分类（当前文件所属文件资料或视频）'
		    ,skin: 'msg'
		    ,time: 2
		});
		return false;
	}	
	if( t.files[0] == undefined ){
		return false;
	}	

	//执行上传
	colse = window.setInterval(run,500);
}
	
//执行上传
var run = function(){	
		var upfile = t.files[0];	
		var HLENG = PixelSize>upfile.size?upfile.size:PixelSize;
		var len = tar+HLENG;
		var bdol=0;
		var flag=true;
			
	return (function(){
					
		if(flag==false){
			return;
		}
			
		if(len>=upfile.size)
		{					
			window.clearInterval(colse);
		}
			
		upfile = t.files[0];	
		bdol = 	upfile.slice(tar,len);
					
		var form = new FormData();
			form.append("file",bdol);		
			form.append("name",upfile.name);//文件名
			form.append("fsize",upfile.size);//文件大小
			form.append("cla",ftval);//分类
			form.append("username",username);//关联用户名
			setFrom(form);
				
		    tar = len;
		    flag=false;
		    
		    var progres = 100*(len/upfile.size);
		    
		    var intproval = progres.toFixed(2);
		    
		    if( intproval >= 100 )
		    {
		    	intproval = 100;		    	
		    	t.value='';
				tar = 0;
				//上传完成切换面板
		    }		    	    
		    ProgressObj.style.width = intproval+'%';//进度条	 	
		    $("#max_ints").html(intproval+'%');
		    	    
	})();		
}
function setFrom(form){
	xhr.open('POST', u, false);
	xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200 || xhr.status == 304) { 
        	var obj = eval("("+xhr.responseText+")");
        	if( obj.error == 1 )
        	{	
        		$("#tishi").html(obj.txt);
        	}
        	else if( obj.error == 0 )
        	{
        		GetSearch();
        		//修改
        		$(".upload-b:eq(1)").hide(),$(".upload-b:eq(0)").show();
        		//显示上传按钮
        		$(".picture-btn:eq(1)").hide();
        	}
        	else if( obj.error == 2 )
        	{
        		$("#tishi").html(obj.txt);		
        	}	
        }
    };
	xhr.send(form);
}
/*节字转换*/
function GetFileSize(filesize)
{
	var i = 0;
	while( filesize > 1024 ){
		filesize /= 1024;
		i++;
	}
	var ext = new Array();
		ext[0] = "B";
		ext[1] = "KB";
		ext[2] = "MB";
		ext[3] = "GB";
		ext[4] = "TB";
		
	return 	filesize.toFixed(2)+ext[i];
}