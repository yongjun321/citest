<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="copyright" content=" " />
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta name="HandheldFriendly" content="true"/>
	<meta http-equiv="x-rim-auto-match" content="none"/>
	<meta name="format-detection" content="telephone=no">
	<title>广告</title>
	
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/css/style.css').'?20170302';?>">


</head>

<body>
<div class="content">
	<div class="wrap"><img src="<?php echo base_url('public/images/01.jpg');?>" alt=""></div>
	<div class="wrap"><img src="<?php echo base_url('public/images/02.jpg');?>" alt=""></div>
	<div class="main">
		<ul class="clearfix">
			<li>
				<label for="name" class="title">姓名:</label>
				<div class="item"><input type="text" class="request" id="name"></div>
			</li>
			<li>
				<label for="" class="title">电&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;话:</label>
				<div class="item"><input type="text" id="tel" class="request"></div>
			</li>
			<li>
				<label for="" class="title">城市:</label>
				<div class="item clickItem">
					<span class="data request" id="city"></span>
					<div class="menu">
						<ul>
							<li>广州</li>
						</ul>
					</div>
				</div>
			</li>
			<li>
				<label for="" class="title">意向车型:</label>
				<div class="item clickItem">
					<span class="data request" id="card"></span>
					<div class="menu">
						<ul>
							<li>C6</li>
							<li>C4世嘉</li>
							<li>全新C4L</li>
							<li>C3-XR</li>
							<li>全新爱丽舍</li>
							<li>T时代新C5</li>
						</ul>
					</div>
				</div>
			</li>
		</ul>
		<button class="btn" id="submit">点击预约</button>
		<!-- 弹窗 -->
		<div class="alert" id="alert">预约成功！</div>
	</div>
	<div class="wrap"><img src="<?php echo base_url('public/images/adimg/03.jpg');?>" alt=""></div>
	<div class="wrap"><img src="<?php echo base_url('public/images/ad/04.jpg');?>" alt=""></div>
	<div class="wrap"><img src="<?php echo base_url('public/images/ad/05.jpg');?>" alt=""></div>
	<div class="wrap"><img src="<?php echo base_url('public/images/ad/06.jpg');?>" alt=""></div>
	<div class="wrap"><img src="<?php echo base_url('public/images/ad/07.jpg');?>" alt=""></div>
</div>
<script src="<?php echo base_url('public/js/jquery_new.js');?>"></script>
<script src="<?php echo base_url('public/js/fastclick.js');?>"></script>
<script>
$(function(){

	$('.clickItem').on('click',function(){
		if($(this).children(".menu").css('display')=='none'){
			$(this).children('.menu').show();
			$(this).children('.menu').children('ul').children('li').on('click',function(){
				$(this).parent('ul').parent('.menu').siblings("span").html($(this).html());
			})
		}else{
			$(this).children('.menu').hide();
		}
	});
	// 提交
	function hideAlert(){
		$('#alert').hide();	
	}
	$('#submit').on('click',function(){
		var name=$('#name').val();
		var tel=$('#tel').val();
		var card=$('#card').html();
		var city = $('#city').html();
		var re = /^1\d{10}$/;
		if(name==""|| tel==""|| card==''||city==''){
			$('#alert').html("用户信息不能为空");
			$('#alert').show();
			window.setTimeout(hideAlert,3000);
			console.log('name:'+name+'&tel:'+tel+'&card:'+card+'&city:'+city); 

		}else if (!re.test(tel)){
			$('#alert').html("请输入正确的电话号码").show();
			window.setTimeout(hideAlert,3000);
			console.log('name:'+name+'&tel:'+tel+'&card:'+card+'&city:'+city); 
		}else{
			//
			console.log('name:'+name+'&tel:'+tel+'&card:'+card+'&city:'+city); 
			var data={
				name:name,
				tel:tel,
				card:card,
				city:city
			};
			// console.log(tel); 

			$.ajax({
			  type: "GET",
			  url: "test.js",
			  dataType: "json",
			  data:data,
			  success:function(data){
			  		$('#alert').html(data.msg).show();
			  		window.setTimeout(hideAlert,3000);

			  }
			});
		}
	});
})
</script>
</body>
</html>
