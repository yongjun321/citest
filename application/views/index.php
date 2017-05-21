<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control" content="no-cache" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no" />
<meta name="MobileOptimized" content="240" />
<meta name="apple-touch-fullscreen" content="YES" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta content="telephone=no" name="format-detection" />  
<meta content="email=no" name="format-detection" />
<!-- uc强制竖屏 -->
<meta name="screen-orientation" content="portrait">
<!-- QQ强制竖屏 -->
<meta name="x5-orientation" content="portrait">
<!-- UC强制全屏 -->
<meta name="full-screen" content="yes">
<!-- QQ强制全屏 -->
<meta name="x5-fullscreen" content="true">
<!-- UC应用模式 -->
<meta name="browsermode" content="application">
<!-- QQ应用模式 -->
<meta name="x5-page-mode" content="app">
<!-- windows phone 点击无高光 -
<meta name="msapplication-tap-highlight" content="no">
<!-- 适应移动端end -->
<title>诚信315 花生帮你忙</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/css/com.css').'?20170302';?>">
<script type="text/javascript" src="<?php echo base_url('public/js/jquery.min.js');?>"></script>
<script type="text/javascript" src="<?php echo base_url('public/js/swipe.js');?>"></script>
<script>

	function checkMobile(s){
		var length = s.length;
		if(length == 11 && /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1})|(14[0-9]{1})|)+\d{8})$/.test(s) )
		{
			return true;
		}else{
			return false;
		}
	}
	//获取弹幕列表
	function getBarrageList() {

		$.post("<?php echo site_url('barrage/lists');?>", function (data) {
			//alert(data);
			$('#play_tanmu').append(data);
			//$('#play_tanmu div:lt(3)').remove()
		});
	}

	function prompt(msg){
		$(".send-success").html(msg).show();
		setTimeout(function(){$(".send-success").hide()},2000);
	}
	//发射弹幕
	function save(){
		var content = $("#comment-face").val();
		//alert(content);
		if(content == '' || content.match(/^\s+$/)){
			prompt('发射内容不能为空');
			return;
		}
		//$("#comment-face").val("").focus();
		$.post("<?php echo site_url('barrage/save');?>", { content: content},function(data){
			if(data.code == -1){
				$("#fadebg,#reg_form").show();
			}else if(data.code == -2){
				prompt(data.msg);
				return;
			}else if(data.code == 1){
				//发送成功
				$("input").blur();
				prompt('弹幕已发送成功，稍后显示');
				$("#comment-face").val("").focus();
				$('#play_tanmu').append(data.msg);
			}
		}, "json");
	}
	$(function(){

		//setInterval(getBarrageList,100000)
		//替换表情
		function replace_em(id, str){
			str = str.replace(/\</g,'&lt;');
			str = str.replace(/\>/g,'&gt;');
			str = str.replace(/\n/g,'<br/>');
			// str = str.replace(/\[em_([0-9]*)\]/g,'<img src="' + $("#basePath").val() + 'face/$1.gif" border="0" />');
			str = str.replace(/\[em_([0-9]*)\]/g,'<img src="face/$1.gif" border="0" />');
			return $("#"+id).html(str);
		}

	})
</script>
</head>
<body>

   <img src="<?php echo base_url('public/images/mainbg.jpg').'?v20150113';?>" alt="" class="mainbg">
	<div class="top-bar"><a href="javascript:history.back(-1);" class="back-prepage"></a>诚信315 花生帮你忙</div>
	<div class="ad-wrap">
		<div class="down-wrap">
			<img src="<?php echo base_url('public/images/regbg.png');?>" alt="" class="ad-img">
			<a href="javascript:;" class="down"></a>
		</div>
		<div class="up-wrap">
			<img src="<?php echo base_url('public/images/ad_img.jpg').'?v20150113 ';?>" alt="" class="ad-img">
			<a href="javascript:;" class="up"></a>
		</div>
		<!-- <div class="ad-imgbox">广告位</div> -->
	</div>
	<!-- -->
	<div id="play_tanmu">
		<?php echo $list;?>
    </div>

	<div class="fixed-bottom">
		<div class="input-emoji-wrap">
			<div class="emoji">
				<div class="input-box"><input type="text" id="comment-face" class="tm-content" placeholder="3.15 你要对商家吐槽吗？" maxlength="70"></div>
				<span class="switch-ico sr-emoji"></span>
			</div>  <!--   sr-wz 文字输入   sr-emoji 切入到表情输入  -->
			<a href="javascript:;" class="send-btn">发送弹幕</a>
		</div>
		<div class="pub-faces">
			<div class="ui-carousel js-slide" data-ride="carousel" id="slider">
			</div>
		</div>
	</div>
	<div class="send-success">弹幕已发送成功，稍后显示</div>
	<!-- 弹窗 -->
	<div class="black_overlay" id="fadebg"></div>
	<div class="form-box" id="reg_form">
		<div class="form-title">发射准备</div>
		<div class="form-content">
			<div class="tel-inputbox">
				<input type="text"  class="tel-input" id="phone">
			</div>
			<a href="javascript:;" class="tel-btn">输入手机号提交</a>
			<div class="checkbox-contain clearfix">
				<div class="check-select fl">
					<img src="<?php echo base_url('public/images/man.png');?>" alt="" class="man">
					<input type="radio" id="man" name="sex" value="1">
					<label  name="man" class="radio-lab check-left" for="man" ><img src="<?php echo base_url('public/images/selected01.png');?>" alt=""></label>
				</div>
				<div class="check-select fr">
					<input type="radio" id="women" name="sex" value="2">
					<label  name="women" class="radio-lab check-right" for="women" class="women"><img src="<?php echo base_url('public/images/selected01.png');?>" alt=""></label>
					<img src="<?php echo base_url('public/images/women.png');?>" alt="" class="women">
				</div>
			</div>
			<div class="tc"><a href="http://content.metro.wifi8.com/content/detail/content/1366/" class="rule-desc-a">奖品及规则介绍</a></div>
		</div>
	</div>
	<div class="confirm-box" id="confirm_form">
		<div class="confirm-title">提示 <a href="javascript:;" class="close-confirm"><img src="<?php echo base_url('public/images/close.png');?>" alt=""></a></div>
		<div class="confirm-content">
			<div class="alert-text">宝贝你说的太快了<br />休息一下再发吧</div>
			<a href="javascript:;" class="confirm-btn">确定</a>
			<!-- 亲爱的手机号码好像有问题<br />重新检查下哟 -->
			<!-- 宝贝你说的有点猛<br />重新编辑一下吧-->
			<!-- 告诉我你是哥哥还是妹妹-->
			<!-- 宝贝你说的太快了<br />休息一下再发吧-->
		</div>
	</div>
<script>
$(function(){
	//rem 设置
	function Rem() {
	var docEl = document.documentElement,
	oSize = docEl.clientWidth / 37.5;

	if (oSize > 20) {
	oSize = 20; // 限制rem值 720 / 36 =20
	}
	//console.log(oSize);
	docEl.style.fontSize = oSize + 'px';
	}
	window.addEventListener('resize', Rem, false);
	Rem();
	var win_h =$(window).height();
	$("#play_tanmu").css("height",(win_h-78)+"px");
	//qq表情调用
	$(".switch-ico").click(function(e){
		if($(this).hasClass("sr-emoji")){
			if($(".pub-faces").css("display")=="none"){
				$(".pub-faces").show();
				faceInitialization();
			}
			else{
				$(".pub-faces").hide();
				$("#slider").empty();
			}
			$(this).removeClass("sr-emoji").addClass("sr-wz");
			$(".tm-content").blur();
		}
		else{
			$(".pub-faces").hide();
			$("#slider").empty();
			$(this).removeClass("sr-wz").addClass("sr-emoji");
			//$(".tm-content").focus();
		}
	});

	$(".tm-content").focus(function(){
		$(".switch-ico").removeClass("sr-emoji").addClass("sr-wz");
		$(".pub-faces").hide();
		$("#slider").empty();
	});
	//切换性别
	$("label.radio-lab").click(function(){
	  	var radioId = $(this).attr('name');
	  	if(!$(this).hasClass("checked")){
	  		$(this).addClass("checked");
	  		$(this).siblings("#"+radioId).attr('checked', 'checked');
	  		$(this).find("img").attr("src","<?php echo base_url('public/images/selected02.png');?>")
	  		$(this).parent(".check-select").siblings(".check-select").find(".radio-lab").removeClass("checked");
	  		$(this).parent(".check-select").siblings(".check-select").find(".radio-lab").siblings("input[type='radio']").removeAttr('checked');
	  		$(this).parent(".check-select").siblings(".check-select").find(".radio-lab img").attr("src","<?php echo base_url('public/images/selected01.png');?>")
	  	}
	  });

	//调用弹窗
	$(".send-btn").click(function(){
		save();
	});
	$(".tel-btn").click(function(){
		var phone = $("#phone").val();
		var sex=$('input:radio[name="sex"]:checked').val();
		if(phone == ''){
			prompt('手机号码不能为空');
			return;
		}
		if(!checkMobile(phone)){
			prompt('亲爱的手机号好像有问题，重新检查下哟！');
			return;
		}
		if(sex == undefined || sex == ''){
			prompt('告诉我你是哥哥还是妹妹哦！');
			return;
		}
		$.post("<?php echo site_url('barrage/reg');?>", { phone: phone,sex:sex},function(data){
			if(data.code == '-1'){
				$(".alert-text").html(data.msg);
	     		$("#fadebg,#reg_form").hide();
				$("#fadebg,#confirm_form").show();
			}else{
				$("#fadebg,#reg_form").hide();
				save();
			}
		}, "json");
	});
	$("#fadebg").click(function(){
		$("#fadebg,#reg_form,#confirm_form").hide();
	});

	$("#play_tanmu").click(function(){
		$(".pub-faces").hide();
	})
	//关闭提示窗口
	$(".confirm-btn,.close-confirm").click(function(){

		if($(this).parent().parent().attr('prompt-data')!=1){
			$("#confirm_form").hide();
			$("#reg_form").show();
		}else{
			$("#confirm_form,#fadebg").hide();
		}
		$("#confirm_form").attr('prompt-data','0');


	});

	//弹幕
	var num = 0;
	var arrColor = ['#5dd9ff','#fbe091','#a74747','#b5d8f4','#4da747','#0ff','#83dd57','#b7359b','#b4f4ff','#ccc','#fff','#41d6cf','#ded931','#18da78','#13de52'];
	function suiji() {
		var strColor = arrColor[parseInt(15*Math.random())];
		var topPos = 7.7+3*10*Math.random();
		if($('#play_tanmu .message-box').eq(num).attr("tag")!="me"){
			$('#play_tanmu .message-box').eq(num).css('top',topPos+"rem");
			$('#play_tanmu .message-box').eq(num).find(".ico-small").css("border-right-color",strColor);
		}else{
			$('#play_tanmu .message-box').eq(num).css('top',"50%");
		}

		$('#play_tanmu .message-box').eq(num).animate({'left':-1500},15000);
	}
    suiji();
    setInterval(function () {
    	var tocal = $('#play_tanmu .message-box').length;
    	for(var i=0;i<tocal;i++){
    		 if($('#play_tanmu .message-box ').eq(i).css("left")=="-1500px"){
    		 	$('#play_tanmu .message-box ').eq(i).css("left","100%");
    		 	//$('#play_tanmu .message-box ').eq(i).remove();
    		 }
    	}
        num++;
        if (num>=tocal) {
            num = 0;
        }
        suiji();
    },1000);

	//折叠层
	setTimeout(function(){if($(".up-wrap").css("display")!="none"){$(".up-wrap").slideToggle(400);}},5000);
	setTimeout(function(){if($(".down-wrap").css("display")!="block"){$(".down-wrap").slideToggle();}},5400);
	$(".down-wrap").click(function(){
		$(".down-wrap").hide();
		$(".up-wrap").slideToggle(400);
	})
	$(".up").click(function(){
		$(".up-wrap").slideToggle(400);
		setTimeout(function(){$(".down-wrap").slideToggle();},400);
	});

	$("#play_tanmu").click(function(){
		$(".pub-faces").hide();
		$("#slider").attr("visibility","hidden").empty();
		$(".switch-ico").removeClass("sr-wz").addClass("sr-emoji")
	});

});	

</script>
<script>
var curFocus = {
	    fid: 'comment-face',
	    start: 0,
	    end: 0
};
function faceInitialization(){
	var expressionHtml = '<ul class="ui-carousel-inner face-panel-wrap">';
	for (var i = 1; i <= 5; i++) {
	    expressionHtml += '<li class="ui-carousel-item face-panel face-panel-'+ i +'">';
	    for(var j = 1; j <= 20; j++){
	        var n = 20*(i-1)+j;
	        expressionHtml += '<span class="express" index="'+ n +'" alt="[em_'+ n +']"></span>';
	    }
	    expressionHtml += '<span class="express" index="-1" alt=""></span></li>';
	}
	expressionHtml += '</ul>';
	var bottomHtml = '<ol id="position" class="ui-carousel-indicators">' +
	                    '<li class="js-active"></li>' +
	                    '<li class=""></li>' +
	                    '<li class=""></li>' +
	                    '<li class=""></li>' +
	                    '<li class=""></li>' +
	                  '</ol>';
	expressionHtml += bottomHtml;
	$("#slider").append(expressionHtml);

	var slider =
	  Swipe(document.getElementById('slider'), {
	    continuous: true,
	    callback: function(pos) {

	      var i = bullets.length;
	      while (i--) {
	        bullets[i].className = ' ';
	      }
	      bullets[pos].className = 'js-active';
	    }
	  });
	var bullets = document.getElementById('position').getElementsByTagName('li');
	

	$('#comment-face').blur(function() {
	    curFocus.fid = 'comment-face';
	    curFocus.start = $(this).get(0).selectionStart;
	    curFocus.end = $(this).get(0).selectionEnd;
	});

	// 点击表情
	$('.express').on('click', function(e) {
	    // 获取表情对应code
	    var imgCode = $(this).attr('alt');
	    // 获取编号判断是否为删除按钮
	    var index = $(this).attr('index');
	    var ta = document.querySelector('textarea');
	    // 删除操作
	    if(index == -1){
	        if ($('#' + curFocus.fid).length) {
	            var text = $('#' + curFocus.fid).val();
	            // 获取光标之前的字符串
	            var changedText = text.substr(0, curFocus.start);
	            var len = changedText.length;
	            var reg=/\[em_([0-9]*)\]$/g;
	            // 删除表情code块或最后一个字符
	            if(reg.test(changedText)){
	                changedText=changedText.replace(reg,"");
	            }else{
	                changedText=changedText.substring(0,changedText.length-1);
	            }
	            var resText = changedText + text.substr(curFocus.end, text.length);
	            $('#' + curFocus.fid).val(resText);
	            // 调整光标位置
	            curFocus.start = curFocus.end = curFocus.end - (len - changedText.length);
	        }
	    // 添加操作
	    }else if ($('#' + curFocus.fid).length) {
	        var text = $('#' + curFocus.fid).val();
	        // 添加表情code块到光标位置
	        var resText = text.substr(0, curFocus.start) + imgCode + text.substr(curFocus.end, text.length);
	        $('#' + curFocus.fid).val(resText);
	        curFocus.start = curFocus.end = curFocus.end + imgCode.length;
	    }
	    e.stopPropagation();
	});
}
</script>
</body>
</html>