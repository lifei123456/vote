<?php if (!defined('THINK_PATH')) exit();?>﻿<html>
<head>
	<meta charset="utf-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="description" content="微信活动" />
	<meta id="view" name="viewport" content="target-densitydpi=device-dpi, width=640px, user-scalable=no">
	<meta name="format-detection" content="telephone=no" />
	<meta name="format-detection" content="email=no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<link rel="stylesheet" href="/luck/Public/css/main.css">
	<script src="/luck/Public/js/jquery-2.1.4.js"></script>
	<title>微信活动</title>
	<style type="text/css">
		.myButton2 {
			-moz-box-shadow: 0px 0px 0px 0px #3dc21b;
			-webkit-box-shadow: 0px 0px 0px 0px #3dc21b;
			box-shadow: 0px 0px 0px 0px #3dc21b;
			background-color:#44c767;
			-moz-border-radius:3px;
			-webkit-border-radius:3px;
			border-radius:3px;
			display:inline-block;

			color:#ffffff;
			font-family:Arial;
			font-size:30px;
			padding:8px 31px;
			text-decoration:none;
			text-shadow:0px 1px 17px #2f6627;
			/*margin-top: -50px;*/
			margin-bottom: 20px;
			width: 77%;
			margin-left: 6%;
			margin-right: auto;
			height: 50px;
			line-height: 50px;
			text-align: center;
		}

	</style>
</head>
<script>
    var DEFAULT_WIDTH = 640,
        ua = navigator.userAgent.toLowerCase(), // 根据 user agent 的信息获取浏览器信息
        deviceWidth = window.screen.width, // 设备的宽度
        devicePixelRatio = window.devicePixelRatio || 1, // 物理像素和设备独立像素的比例，默认为1
        targetDensitydpi;
    // Android4.0以下手机不支持viewport的width，需要设置target-densitydpi
    if (ua.indexOf("android") !== -1 && parseFloat(ua.slice(ua.indexOf("android")+8)) < 4) {
        targetDensitydpi = DEFAULT_WIDTH / deviceWidth * devicePixelRatio * 160;
        document.getElementById('view').setAttribute('content', 'target-densitydpi=' + targetDensitydpi + ', width=device-width, user-scalable=no');
    }
</script>
<body>
<div class="wrap" id="wraper">


	<div class="header">
		<div class="header1 icon"></div>
		<div class="header2 icon"></div>
	</div>
	<div class="content">
		<div class="main box" id="main">
			<div class="main-hd main-hd-style">
				<h2>小象送书啦~</h2><!--前500名送888元体验金999-->
				<p>同学们，把下面链接中的文章转发到朋友圈，然后在上传朋友圈截图，就有机会获得《Python机器学习——预测分析核心算法》一本哦~
				</p><!--关注微信公众号参与讨论，前500名参加活动送888体验金，不要再错过啦~-->
			</div>
			<div class="item mt38 linkContanier">
				<div class="noLinkTitle icon icon-link"></div>
				<div class="text">
					<h3 class="mb25 noLinkTitle">点击以下链接，转发到朋友圈并截图</h3>
					<a class="link" href="http://mp.weixin.qq.com/s/2LhFRcLY3c_LfHMRdb4BlQ">
						<!--<h4 class="link-hd">这里有足够让你尖叫的惊喜啊啊啊！</h4>&lt;!&ndash;爱占便宜能赚钱吗？&ndash;&gt;-->
						<div class="link-bd">
							<div class="pic">
								<img src="/luck/Public/img/hmbb.jpeg"/>
							</div>
							<div class="link-text">
								戳我戳我戳我！<!--快来，前500个吐槽有奖哦~-->
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
		<div class="box-link1" style="display:none">
			<b></b>
		</div>

		<div class="box hide" id="winnersContanier"></div>
		<div class="box hide resultContanier" id="resultContanier"></div>
		<div class="box explain"  >
			<div class="box-link icon"></div>

			<p id="tellMe" style="color:#fbad04;text-align: center;margin-top:40px;font-size: 28px">在此提交截图</p>
			<div id="pic" style="margin-top: 15px;margin-left: 20%;width:55%;height:280px;background-image:url(<?php echo ($initImg); ?>);background-repeat:no-repeat;background-size: 100% 100%;">
				<input type="file" id="file" style="height:280px;background-image:url(<?php echo ($initImg); ?>);background-repeat:no-repeat;background-size: 100% 100%;opacity: 0"
					   accept="image/gif,image/jpeg,image/jpg,image/png" name="file" >
			</div>
			<img id="image" src="" width="500" height="200" style="display: none"/><br/>
			<!--<button style="background-image:url(./img/upload.jpg);background-repeat:no-repeat;background-size: 100% 100%"-->
			<!--class="btn-submit"  id="toWechat"></button>-->
		</div>
		<div class="box explain">
			<div class="box-link icon"></div>
			<h3>
				活动时间
			</h3>
			<p>
				4月11日-4月12日21：00
			</p>
			<h3>
				活动奖励
			</h3>
			<p>
				《Python机器学习——预测分析核心算法》1本（100名）<br />
			</p>
			<h3>
				特别说明
			</h3>
			<p>
				本活动最终解释权归小象学院所有，如有疑问，请在公众号内留言反馈你的问题。
			</p>
			<!--<h3>活动时间</h3>
			<p>4月14日-4月15日</p>-->
			<a href="javascript:void(0);" class="myButton2">提交</a >
	</div>

	<!-- <div class="footer">
		<div class="btn_left">打开微信</div>
		<div class="btn_right">分享到朋友圈</div>
	</div> -->

</div>


<script type="text/javascript" src="/luck/Public/js/lrz.bundle.js"></script>
<script type="text/javascript">
	var upImg = "<?php echo ($upImg); ?>",startText = "请先在公众号小象中参与抽奖活动，否则不会记入最后抽奖名单，先到先得呦~",imgData="";
    document.querySelector('input').addEventListener('change', function () {
        // this.files[0] 是用户选择的文件
        lrz(this.files[0], {width: 1024})
            .then(function (rst) {
                // 把处理的好的图片给用户看看呗
                $("#image").attr("src",rst.base64);
                $("#pic").css("background-image","url("+rst.base64+")");
                return rst;
            })
            .then(function (rst) {
                // 这里该上传给后端啦
                imgData=rst.base64;
//                ajaxData(rst.base64);
            })
            .catch(function (err) {
                // 万一出错了，这里可以捕捉到错误信息
                // 而且以上的then都不会执行
                alert("人数太多，遇到了点问题，请刷新页面！");
            })
            .always(function () {
                // 不管是成功失败，这里都会执行
            });
    });

    $(".myButton2").click(function () {
        ajaxData(imgData);
    });

    function ajaxData(_imgData) {
        if(_imgData == "") {
            alert("请先上传图片！");
            return ;
		}
        if(upImg == "1") {
            $.ajax({
                url : "<?php echo ($ajaxImgData); ?>",
                data : { imgData : _imgData },
                type : "POST",
                dataType : "JSON",
                success : function (data) {
                    alert("成功上传分享截图！");
                    console.log(data);
                },
                error : function (error) {
                    console.log(error);
                }
            })
		}
        else {
            alert(startText);
		}
    }

</script>
</body>
</html>