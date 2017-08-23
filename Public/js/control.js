/**
 * Created by LiFei on 2017/4/9.
 */
var arr_Name = new Array();
var arr_Value = new Array();
var arr_Url = new Array();
var arr_Uid = new Array();
var arr_Luckcode=new Array();
var arr_Name_selected = new Array();
var arr_Value_selected = new Array();
var arr_Url_selected = new Array();
var arr_Name_del = new Array();
var arr_bookcode ;
var num = 0;
var data = [];
var n = 0;
var isUpdata = 0;//初始状态可更新

//./Public/resource/bookcode.json





window.now = 1;
$("#submit").click(
    function () {
        var TotalFinal = $("#num").val();
        if (TotalFinal != "") {
            $.ajax({
                url : ajaxData,
                data : { count : TotalFinal },
                type : "POST",
                dataType : "JSON",
                success : function (data) {
                    var luckData = data.data;
                    // console.log(data.count);
                    // console.log(TotalFinal);
                    // isUpdata = data.count == TotalFinal ? 1 : 2;
                    if(TotalFinal != data.count) {
                        isUpdata=1;//设置不能更新
                    }
                    // else {
                    //     isUpdata=2;
                    //     alert("抽奖总用户数小于待抽总人数！");
                    // }
                    window.PageFinal = parseInt(TotalFinal / 15) + 1;
                    $("#page2").html(now + "/" + PageFinal);
                    $(".hid").css("display", "none");
                    $(".span4").css("display", "");
                    $(".span6").css("display", "");
                    // console.log(data);
                    // console.log(luckData);
                    for (var s in luckData) {//遍历json数组时，这么写p为索引，0,1
                        //alert(json[p].name + " " + json[p].value);
                        arr_Name.push(luckData[s].name);
                        arr_Value.push(luckData[s].openid);
                        arr_Url.push(luckData[s].shareimg);
                        arr_Uid.push(luckData[s].unionid);
                        arr_Luckcode.push(luckData[s].luckcode);
                        //$("#in").append("<tr class='success'><td>"+json[p].name+"</td><td>"+json[p].value+"</td> </tr>");
                    }
                    for (j = num; j < num + 15; j++) {
                        if (typeof(arr_Value[j]) !== "undefined") {
                            $("#in").append("<tr class='success'><td>" + arr_Name[j] + "</td><td class='openid'>" + arr_Value[j] + "</td><td class='openid'>" + arr_Luckcode[j] + "</td>   <td class='check'>" +
                                "<a  style='float: right' class='check' data-img='" + arr_Url[j] + "'>查看</a></td><td><a  style='float: right' onclick='delall("+j+")' id="+j+ " class='del-all del' >删除</a></td> </tr>");
                        }
                    }
                    in_all = parseInt((arr_Name.length) / 15) + 1;
                    //alert(in_all);
                    $("#page1").html(in_now + "/" + in_all);
                    //console.log(arr_Uid);
                    initJson();
                },
                error : function (error) {
                    console.log(error);
                }
            })
        }
        else {
            alert("请填写抽奖人数");
        }
        // $(".del").on("click",function (e) {
        //     var id=$(this).attr("id");
        //     arr_Value.splice(id,1);
        //     arr_Uid.splice(id,1);
        //     arr_Name.splice(id,1);
        //     arr_Url.splice(id,1);
        //     arr_Luckcode.splice(id,1);
        //     var id2= $("#"+id).parent().parent().nextAll().children("td:nth-child(5)").children();
        //     $(this).parent().parent().remove();
        //     console.log(id2);
        //     for(var i=0; i<=id2.length; i++){
        //         var t=parseInt(id)+i;
        //         var ne=parseInt($("#"+t).attr("id"))-1;
        //         console.log(ne);
        //         $("#"+t).attr("id",ne);
        //     }
        //     if(isUpdata == 1) {
        //         isUpdata++;
        //         alert("总人数不足，无法为您自动填充，此消息不再提示");
        //     }
        //     else if (isUpdata == 0) {
        //         ajaxDel();
        //     }
        // });
    }
);

function initJson() {
    $.get("./Public/resource/bookcode.json",function (data) {
        arr_bookcode=data;
    })
}


var in_all, in_now = 1;
$(function () {


});

$("#useTpl").click(function () {
    var tpm = $("#template").val();
    $.post(setTpl, {tplText: tpm}, function (data) {
        if (data.status == "1") {
            alert("更改模板成功");
        }
        else {
            alert("出现错误");
        }
        // console.log(data);
    });
});

$("#init").click(function () {
    $("#example").css("display","none");
    $("#mu1").css("display","none");
    $.get(init, function (data) {
        if (data.status == "1") {
            alert("初始化成功，您可以重新开始抽奖活动了");
        }
    });
});

$("#in-back").click(
    function () {
        for (var j=0;j<arr_Name_del.length;j++){
            var h=$.inArray(arr_Name_del[j],arr_Name);
            arr_Value.splice(h,1);
            arr_Uid.splice(h,1);
            arr_Name.splice(h,1);
            arr_Url.splice(h,1);
            arr_Luckcode.splice(h,1);
        }
        arr_Name_del.splice(0,arr_Name_del.length);
        n=0;
        if (in_now > 1) {
            $(".success").remove();
            $(".del").remove();
            in_now--;
            num = num - 15;
            for (j = num; j < num + 15; j++) {
                if (typeof(arr_Value[j]) !== "undefined") {
                    $("#in").append("<tr class='success'><td>" + arr_Name[j] + "</td><td class='openid'>" + arr_Value[j] +
                        "</td><td class='openid'>" + arr_Luckcode[j] + "</td><td  class='check'><a  style='float: right' class='check'>查看</a></td><td> <a class='del'  style='float: right' onclick='delall("+j+")' id="+j+ ">删除</a></td> </tr>");
                }
            }
            $("#page1").html(in_now + "/" + in_all);
        } else {
            alert("到顶了")
        }
        //alert(num);

        console.log(arr_Name);
    }
);

$("#in-next").click(
    function () {
        for (var j=0;j<arr_Name_del.length;j++){
            var h=$.inArray(arr_Name_del[j],arr_Name);
            arr_Value.splice(h,1);
            arr_Uid.splice(h,1);
            arr_Name.splice(h,1);
            arr_Url.splice(h,1);
            arr_Luckcode.splice(h,1);
        }
        arr_Name_del.splice(0,arr_Name_del.length);
        n=0;
        if (in_now <= in_all) {
            $(".success").remove();
            $(".del").remove();
            in_now++;
            num = num + 15;
            for (j = num; j < num + 15; j++) {
                if (typeof(arr_Value[j]) !== "undefined") {
                    $("#in").append("<tr class='success'><td>" + arr_Name[j] + "</td><td class='openid'>" + arr_Value[j] +
                        "</td><td class='openid'>" + arr_Luckcode[j] + "</td><td  class='check'><a  style='float: right' class='check'>查看</a></td><td><a class='del' onclick='delall("+j+")' id="+j+ " style='float: right' >删除</a>&nbsp; &nbsp;</td> </tr>");
                    $("#page1").html(in_now + "/" + in_all);
                    //alert(num);
                }
            }
        } else {
            alert("到底了")
        }

        console.log(arr_Name);
    });
$(".modal-show").click(function () {
        $("#example").css("display","");
        $("#mu1").css("display","");
    }
);
$("#mu").click(function () {
    $("#img-all").css("display","none");
    $("#mu").css("display","none");
    for (var j=0;j<arr_Name_del.length;j++){
        var h=$.inArray(arr_Name_del[j],arr_Name);
        arr_Value.splice(h,1);
        arr_Uid.splice(h,1);
        arr_Name.splice(h,1);
        arr_Url.splice(h,1);
        arr_Luckcode.splice(h,1);
    }
    arr_Name_del.splice(0,arr_Name_del.length);
    n=0;
});

$("#mu1").click(function () {
    $("#example").css("display","none");
    $("#mu1").css("display","none")
});

$('#in').on('click', '.check', function (e) {
    n=$(this).parent().parent().find("td:nth-child(5)").children("a").attr("id");

    // console.log("dawd"+$(this).parent().parent().find("td:nth-child(5)").children("a").attr("id"));
    var name = $(this).parent().parent().find("td:nth-child(1)").html();
    $(".name-preview").remove();
    $(".openid-preview").remove();
    $(".test_remove").remove();
    // console.log(arr_Name[n]);
    // console.log(arr_Value[n]);
    // console.log(n);
    $("#det").append("<span class='name-preview' style='font-size: 18px'>"+arr_Name[n]+
        "</span><br class='test_remove'><span style='font-size: 18px' class='openid-preview'>"+arr_Value[n]+"</span>");

    $("#img-all").css("display","");
    $("#mu").css("display", "");
    // var n = $(this).parent().find("td:nth-child(3)").find("a").attr("id");//索引位置
    $("#detitle_img").attr("src", $(this).attr("data-img"));

    // $(".check").click(
    //     function () {
    //         $("#img-all").css("display", "");
    //         $("#mu").css("display", "");
    //         // var n = $(this).parent().find("td:nth-child(3)").find("a").attr("id");//索引位置
    //         $("#detitle_img").attr("src", $(this).attr("data-img"));
    //     }
    // );
    // $("#in").off(".check");
    // $(“.test-btn”).off(“click”);
    for (var j=0;j<arr_Name_del.length;j++){
        var h=$.inArray(arr_Name_del[j],arr_Name);
        arr_Value.splice(h,1);
        arr_Uid.splice(h,1);
        arr_Name.splice(h,1);
        arr_Url.splice(h,1);
        arr_Luckcode.splice(h,1);
    }
    arr_Name_del.splice(0,arr_Name_del.length);

});


$("#img-del").click(
    function () {

        if (typeof(arr_Value[++n]) !== "undefined") {
            console.log(n);
            // var n=$(this).parent().parent().find("td:nth-child(4)").children("a").attr("id");
            $(".name-preview").remove();
            $(".openid-preview").remove();
            $(".test_remove").remove();
            $("#det").append("<span class='name-preview' style='font-size: 18px'>"+arr_Name[n]+
                "</span><br class='test_remove'><span style='font-size: 18px' class='openid-preview'>"+arr_Value[n]+"</span>");
            // $("#img-all").css("display", "");
            // $("#mu").css("display", "");
            // var n = $(this).parent().find("td:nth-child(3)").find("a").attr("id");//索引位置
            // $("#detitle_img").attr("src", $(this).attr("data-img"));

            $("#detitle_img").attr("src",arr_Url[n]);
        }
        else {
            alert("这已经是最后一张了！");
        }
    }
);
$(".back").click(
    function () {
        if (now <= 1) {
            alert("前面没有了！");
        } else {
            now--;
            $("#page2").html(now + "/" + PageFinal);
        }
    }
);

$(".next").click(
    function () {
        if (now >= PageFinal) {
            alert("没有更多了！");
        } else {
            now++;
            $("#page2").html(now + "/" + PageFinal);
        }
    }
);

// $(".del").on("click",function (e) {
//     var id=$(this).attr("id");
//     arr_Value.splice(id,1);
//     arr_Uid.splice(id,1);
//     arr_Name.splice(id,1);
//     arr_Url.splice(id,1);
//     arr_Luckcode.splice(id,1);
//     var id2= $("#"+id).parent().parent().nextAll().children("td:nth-child(5)").children();
//     $(this).parent().parent().remove();
//     console.log(id2);
//     for(var i=0; i<=id2.length; i++){
//         var t=parseInt(id)+i;
//         var ne=parseInt($("#"+t).attr("id"))-1;
//         console.log(ne);
//         $("#"+t).attr("id",ne);
//     }
//     if(isUpdata == 1) {
//         isUpdata++;
//         alert("总人数不足，无法为您自动填充，此消息不再提示");
//     }
//     else if (isUpdata == 0) {
//         ajaxDel();
//     }
// });
function delall(id) {
    console.log(id);
    $("#"+id).parent().parent().css("display","none");
    // var openid = $(this).parent().parent().find("td:nth-child(2)").html();
    // arr_Value.splice(id,1);
    // arr_Uid.splice(id,1);
    // arr_Name.splice(id,1);
    // arr_Url.splice(id,1);
    // arr_Luckcode.splice(id,1);
    // var id2= $("#"+id).parent().parent().nextAll().children("td:nth-child(5)").children();
    // console.log(id2);
    // for(var i=0; i<=id2.length; i++){
    //     var t=parseInt(id)+i;
    //     var ne=parseInt($("#"+t).attr("id"))-1;
    //     console.log(ne);
    //     $("#"+t).attr("id",ne);
    //     $("#"+t).bind("click",function () {
    //         $("#"+t).prop("onclick",null).off("click");
    //         delall(ne);
    //     });
    // }
    var nameDel=$("#"+id).parent().parent().find("td:nth-child(1)").html();
    console.log(nameDel);
    var sy=$.inArray(nameDel,arr_Name);
    arr_Name_del.push(nameDel);
    console.log(arr_Name_del);
    if(isUpdata == 1) {
        isUpdata++;
        alert("总人数不足，无法为您自动填充，此消息不再提示");
    }
    else if (isUpdata == 0) {
        ajaxDel();
    }
}


// $('.table').on('click', '.del-all', function (e) {
//     console.log("Test");
//     $(this).parent().parent().remove();
//     var openid = $(this).parent().parent().find("td:nth-child(2)").html();
//     arr_Value.splice($.inArray(openid,arr_Value),1);
//     arr_Uid.splice($.inArray(openid,arr_Value),1);
//     arr_Name.splice($.inArray(openid,arr_Value),1);
//     arr_Url.splice($.inArray(openid,arr_Value),1);
//     arr_Luckcode.splice($.inArray(openid,arr_Value),1);
//
//     if(isUpdata == 1) {
//         isUpdata++;
//         alert("总人数不足，无法为您自动填充，此消息不再提示");
//     }
//     else if (isUpdata == 0) {
//         ajaxDel();
//     }
// });

function ajaxDel() {
    $.get(ajaxOne,function (data) {
        // console.log(data.data[0].openid);
        // console.log(arr_Value);
        // console.log($.inArray(data.data[0].openid,arr_Value));
        if($.inArray(data.data[0].openid,arr_Value) == -1) {
            luckData = data.data;
            arr_Name.push(luckData[0].name);
            arr_Value.push(luckData[0].openid);
            arr_Url.push(luckData[0].shareimg);
            arr_Uid.push(luckData[0].unionid);
            arr_Luckcode.push(luckData[0].luckcode);
            $("#out").append("<tr class='success'><td>" + luckData[0].name + "</td><td class='openid'>" + luckData[0].openid + "</td><td class='openid'>" + luckData[0].luckcode + "</td><td class='check'>" +
                "<a  style='float: right' class='check' data-img='" + luckData[0].shareimg + "'>查看</a></td><td><a  style='float: right' class='del-all del'  id=" + (arr_Name.length-1) + ">删除</a></td> </tr>");
            in_all = parseInt((arr_Name.length) / 15) + 1;
            //console.log(arr_Uid);
            $("#page1").html(in_now + "/" + in_all);
        }
        else {
            // console.log(data.data[0].openid+"已经存在，重新抓取!");
            ajaxDel();
        }
    });
}





$(".myButton").click(function () {
    // console.log(arr_bookcode);
    // console.log(arr_bookcode[1]);
    var dataArr=[];
    for(var index in arr_Value) {
        // console.log(index);
        dataArr[index]=[arr_Value[index],arr_bookcode[index],arr_Uid[index]];
    }
    console.log(dataArr);

    $.post(send,{openid : dataArr},function (data) {
        alert("成功向所有中奖人员发送中奖客服消息!");
        console.log(data);
        // return;
        // if(data.status == 1) {
        //     alert("成功向所有中奖人员发送中奖客服消息!");
        // }
        // else {
        //     alert("由于某种原因，未能成功向"+data.count+"名用户发送客服消息！");
        // }
    })
})

// console.log(JSON.stringify(arr_Value));
// console.log((arr_Value));