<?php if (!defined('THINK_PATH')) exit();?><html>
<head>
	<meta charset="utf-8"/>
	<script type="text/javascript" src="/luck/Public/js/jquery-2.1.4.js"></script>
	<!--<script type="text/javascript" src="/luck/Public/js/jquery-ui"></script>-->
	<link rel="stylesheet" href="/luck/Public/css/weui.css?v=1.1">
	<link href="/luck/Public/css/bootstrap-combined.min.css" rel="stylesheet" media="screen">
	<script type="text/javascript" src="/luck/Public/js/bootstrap.min.js"></script>
	<style>
		.myButton {
			float: right;
			-moz-box-shadow: 3px 4px 0px 0px #899599;
			-webkit-box-shadow: 3px 4px 0px 0px #899599;
			box-shadow: 3px 4px 0px 0px #899599;
			background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #ededed), color-stop(1, #bab1ba));
			background:-moz-linear-gradient(top, #ededed 5%, #bab1ba 100%);
			background:-webkit-linear-gradient(top, #ededed 5%, #bab1ba 100%);
			background:-o-linear-gradient(top, #ededed 5%, #bab1ba 100%);
			background:-ms-linear-gradient(top, #ededed 5%, #bab1ba 100%);
			background:linear-gradient(to bottom, #ededed 5%, #bab1ba 100%);
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#ededed', endColorstr='#bab1ba',GradientType=0);
			background-color:#ededed;
			-moz-border-radius:15px;
			-webkit-border-radius:15px;
			border-radius:15px;
			border:1px solid #d6bcd6;
			display:inline-block;
			cursor:pointer;
			color:#3a8a9e;
			font-family:Arial;
			font-size:17px;
			padding:7px 25px;
			text-decoration:none;
			text-shadow:0px 1px 0px #e1e2ed;
		}
		.myButton:hover {
			background:-webkit-gradient(linear, left top, left bottom, color-stop(0.05, #bab1ba), color-stop(1, #ededed));
			background:-moz-linear-gradient(top, #bab1ba 5%, #ededed 100%);
			background:-webkit-linear-gradient(top, #bab1ba 5%, #ededed 100%);
			background:-o-linear-gradient(top, #bab1ba 5%, #ededed 100%);
			background:-ms-linear-gradient(top, #bab1ba 5%, #ededed 100%);
			background:linear-gradient(to bottom, #bab1ba 5%, #ededed 100%);
			filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#bab1ba', endColorstr='#ededed',GradientType=0);
			background-color:#bab1ba;
		}
		.myButton:active {
			position:relative;
			top:1px;
		}


	</style>
</head>
<body>

<div id="mu1"  style="display:none;"></div>
<div id="mu"  style="  display:none ;"></div>
<div id="img-all" style="  display: none;">
	<div id="img" style="background-color: rgba(77,109,96,0.36);opacity: 1"  >
		<div id="det">
		</div>
		<img id="detitle_img" style=";display: block;margin-left: auto;
    margin-right: auto;" src="/luck/Public/img/cs.png" alt="">
		<span id="img-del" style="background-image: url('/luck/Public/img/forward.png');
	margin: 15px 130px;height: 32px;width: 32px;display: block;margin-left: auto;
    margin-right: auto;"></span>
	</div>
</div>

<div class="container-fluid"  >
	<div class="row-fluid">
		<div class="span4" style="display: none">

			<h3 class="text-right">
				中奖用户
			</h3>
		</div>
		<div class="span4" style="display: none">
			<h3 class="text-right">
				新增中奖用户
			</h3>
		</div>
	</div>
	<div class="row-fluid">
		<h1 class="text-center hid"  style="margin-top: 100px">
			抽奖后台
		</h1>
		<div class="content hid" style="text-align: center;margin-top: 200px">

			<textarea  name="模板" id="template" cols="15" rows="10" style="resize: none;height: 200px;width: 600px"><?php echo ($tplText); ?></textarea><br>
			<button class="btn modal-show" style=""  type="button">初始化</button>
			<button class="btn" style="" id="useTpl" type="button">使用模板</button>
			<div class="view" style="margin-top: 20px;text-align: center">

				<input placeholder="抽奖人数" style="border-radius: 0;height: 35px" id="num" class="input-medium search-query" type="text"
					   onkeyup="value=value.replace(/[^\d]/g,'') "
					   onbeforepaste="clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))">
				<button type="btn" id="submit" class="btn" >点击抽奖</button>

			</div>
		</div>
		<div class="span6" style="border: 2px #b5c0b5 dashed;border-radius: 5px; display: none">
			<table class="table">
				<thead>
				<tr style="background-color: rgba(145,127,130,0.27)">
					<th>
						昵称
					</th>
					<th>
						openID
					</th>
					<th>
						luckCode
					</th>
					<th>
						<!--<button id="Sure" style="float: right">确定删除</button>-->
					</th>
					<th>
						<!--<button id="Sure" style="float: right">确定删除</button>-->
					</th>
				</tr>
				</thead>
				<tbody id="in">

				</tbody>
			</table>
			<div class="pagination pagination-centered" style="margin-bottom: 1%" >
				<ul>
					<li id="in-back">
						<a href="#">上一页</a>
					</li>
					<li >
						<a id="page1" href="#">0</a>
					</li>
					<li id="in-next">
						<a href="#">下一页</a>
					</li>
				</ul>
				<a href="#" class="myButton">确定并通知</a>
			</div>
		</div>
		<div class="span4" style="border: 2px #b5c0b5 dashed;border-radius: 5px;display: none">
			<table class="table">
				<thead>
				<tr style="background-color: rgba(145,127,130,0.27)">

					<th>
						昵称
					</th>

					<th>
						openID
					</th>
					<th>
						luckCode
					</th>
					<th>
						<!--<button id="Sure" style="float: right">确定删除</button>-->
					</th>
					<th>
						<!--<button id="Sure" style="float: right">确定删除</button>-->
					</th>
				</tr>
				</thead>
				<tbody id="out">

				</tbody>

			</table>
			<div class="pagination pagination-centered" >
				<ul>
					<li class="back">
						<a href="#">上一页</a>
					</li>

					<li>
						<a id="page2" href="#">0</a>
					</li>

					<li class="next">
						<a href="#">下一页</a>
					</li>

				</ul>
			</div>
		</div>

	</div>

</div>
<div class="container">
	<div id="example" class="modal  fade in" style="display:none ; ">
		<div class="modal-header">
			<a onclick={$("#example").css("display","none");$("#mu1").css("display","none")} class="close" data-dismiss="modal">×</a>
			<h3>温馨提示</h3>
		</div>
		<div class="modal-body">
			<p>初始化之后将完全变更当前的抽奖状态为最初状态，所有信息也都会刷新，请慎重选择，此操作不可逆。（详情请咨询管理员）</p>
		</div>
		<div class="modal-footer">
			<a href="#" id="init"  class="btn btn-success">确定</a>
			<a href="#" onclick='{$("#example").css("display","none");$("#mu1").css("display","none")}'  class="btn" >取消</a>
		</div>
	</div>
</div>
<script>
    $(".modal-show").click(function () {
            $("#example").css("display","");
            $("#mu1").css("display","");
        }
    );

    $("#mu").click(function () {
        $("#img-all").css("display","none");
        $("#mu").css("display","none");
    });

    $("#mu1").click(function () {
        $("#example").css("display","none");
        $("#mu1").css("display","none")
    });
</script>
</body>
<script>
    var ajaxData = "<?php echo ($ajaxData); ?>";
    var setTpl = "<?php echo ($setTpl); ?>";
    var ajaxOne = "<?php echo ($ajaxOne); ?>";
    var init = "<?php echo ($init); ?>";
    var send = "<?php echo ($send); ?>";
</script>
<script type="text/javascript" src="/luck/Public/js/control.js?v=<?php echo rand(100,10000);?>"></script>
</html>