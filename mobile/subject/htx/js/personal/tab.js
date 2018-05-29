// JavaScript Document

  
 


$(document).ready(function(e) {

           
    $(".search-ul li").click(
    function(e){
    var i=$(this).index();
    $('.swiper-wrapper .swiper-slide').removeClass('search-none');
    $('.swiper-wrapper .swiper-slide').eq(i).addClass('search-none');
    })  

    $(".search-ul li").click(
    function(e){
      $(".search-ul .li").removeClass("li")
      $(this).addClass("li")
    })


    $(".tabs li").click(
    function(e){
    var i=$(this).index();
    $('.swiper-wrapper .swiper-slide').removeClass('swiper-slide-1');
    $('.swiper-wrapper .swiper-slide').eq(i).addClass('swiper-slide-1');
    })  

    $(".tabs li a").click(
    function(e){
    $('.tabs li .default').removeClass('default');
      $(this).addClass("default")
    })  
 
    $(".sm-div").click(function(){
      $(".sm-div-box").fadeIn(500);
      });

    $(".sm-div-box .li").click(function(){
      $(".sm-div-box").fadeOut(500);
      });

    $(".more-icon").click(function(){
      $(".bott-morebox").fadeIn(500);
      });

    $(".bott-morebox").click(function(){
      $(".bott-morebox").fadeOut(500);
      });




  });


