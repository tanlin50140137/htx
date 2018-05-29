








// JavaScript Document
// 
$(document).ready(function(e) {

    $(".gender span").click(
    function(e){
      $(".gender .span").removeClass("span")
      $(this).addClass("span")
    })


	$(".gl-btn").click(function(){
	$(".associa-dl").slideToggle(0);
	});

});

$(document).ready(function(e) {
    $(".associa-a-btn1").click(function(){
        var vl=$(this).html();
        if(vl=="绑定"){
            $(this).html("确定");
			$(".span10").hide();
			$(".input-a1").show();
            
        }else if(vl=="确定"){
            $(this).html("绑定");
			$(".span10").show();
			$(".input-a1").hide();
        }            
    });

});

$(document).ready(function(e) {
    $(".associa-a-btn2").click(function(){
        var vl=$(this).html();
        if(vl=="绑定"){
            $(this).html("确定");
			$(".span10").hide();
			$(".input-a2").show();
            
        }else if(vl=="确定"){
            $(this).html("绑定");
			$(".span10").show();
			$(".input-a2").hide();
        }            
    });

});

