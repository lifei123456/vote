<?php if (!defined('THINK_PATH')) exit();?><html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=0">
    <title>WeUI</title>
    <link rel="stylesheet" href="/luck/Public/css/weui.css">
    <link rel="stylesheet" href="/luck/Public/css/example.css">
    <script type="text/javascript" src="/luck/Public/js/jquery-2.1.4.js"></script>
</head>
<body ontouchstart="">
<div class="container js_container">
    <div class="page slideIn cell">
        <div class="bd">
            <div class="weui_cells_title">手机号</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" id="phonenum" type="number" placeholder="请输入">
                    </div>
                </div>
            </div>
            <div class="weui_cells_title">姓名</div>
            <div class="weui_cells weui_cells_form">
                <div class="weui_cell">
                    <div class="weui_cell_bd weui_cell_primary">
                        <input class="weui_input" type="text" placeholder="请输入">
                    </div>
                </div>
            </div>
            <div class="weui_btn_area">
                <a class="weui_btn weui_btn_primary" href="javascript:" id="submit">提交</a>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $("#submit").click(function () {
            if("<?php echo ($isSub); ?>" == "1") {
                var phonenum=$("#phonenum").val();
                if(phonenum != "") {
                    $.ajax({
                        url :"<?php echo ($ajaxSub); ?>",
                        data : { phonenum : $("#phonenum").val() },
                        type : "POST",
                        dataType : "JSON",
                        success : function (data) {
                            if(data.status == 1) {
                                alert("添加成功！");
                            }
                            else {
                                alert("内部错误，请重试");
                            }
                        },
                        error : function (error) {
                            console.log(error);
                        }
                    })
                }
                else {
                    alert("电话号码是必填项");
                }
            }
            else {
                alert("抱歉，未能查询到您的抽奖信息，您不能提交申请");
            }
        })
    })
</script>
</body>
</html>