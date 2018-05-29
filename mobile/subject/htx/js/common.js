// JavaScript Document

  
 
//  轮播
var page = 2;
$('#your-slider').flexslider({
  playAfterPaused: 1000,
  before: function(slider) {
    if (slider._pausedTimer) {
      window.clearTimeout(slider._pausedTimer);
      slider._pausedTimer = null;
    }
  },
  after: function(slider) {
    var pauseTime = slider.vars.playAfterPaused;
    if (pauseTime && !isNaN(pauseTime) && !slider.playing) {
      if (!slider.manualPause && !slider.manualPlay && !slider.stopped) {
        slider._pausedTimer = window.setTimeout(function() {
          slider.play();
        }, pauseTime);
      }
    }
  }


});

$(document).ready(function(e) {

//选项卡 课件 资料    
	$(".tab dl dt").click(function(e){
    	var i=$(this).index();
	    //$('.tab-1 .tab-div').removeClass('tab-block');
	    //$('.tab-1 .tab-div').eq(i).addClass('tab-block');
	    //记录下标
	    $("#flag_dt").val(i);
	    page = 2;
	    //切换
	    $("#pri_text").hide();
	    $("#pri_text").html('<img src="'+$("#pri_text").attr('data-url')+'" style="display:inline;width:15px;" alt="加载更多">');
	    //重置
	    $.ajax({
			url:'http://'+window.location.host+'/mobile/index.php',
			type:'POST',
			data:'act=GetRecommended&flag='+i+'&page=1',
			success:function(result){
				var obj = eval("("+result+")");
				$(".ul-learn").empty();
				$(".ul-learn").append(obj.rows);
			}
	   });
	});

    $(".tab dl dt").click(
    function(e){
      $(".tab dl dt.tab-dt").removeClass("tab-dt")
      $(this).addClass("tab-dt")
    })

    $(".ask-slide p").click(
    function(e){
      $(".ask-slide .p").removeClass("p")
      $(this).addClass("p")
    })

    $(".upload-a a").click(
    function(e){
      $(".upload-a .a").removeClass("a")
      $(this).addClass("a")
    })
 

//底部更多    
    
    $(".more-icon").click(function(){
      $(".bott-morebox").fadeIn(500);
      });

    $(".bott-morebox").click(function(){
      $(".bott-morebox").fadeOut(500);
      });


    $(".share-btn").click(function(){
      $(".share-div").fadeIn(500);
      });

    $(".outdiv").click(function(){
      $(".share-div").fadeOut(500);
      });

    $(".pay-bott a").click(function(){
      $(".share-div").fadeIn(500);
      });
    $(".click_down10").click(function(){
        $(".share-div").fadeIn(500);
        });
  });


