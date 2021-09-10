function getApi()
{
    $.ajax({
        type: "post",
        url: "/file_share/pass",
        data: {'id': $("#urlId").val(),'pass': $("#accessCode").val()},
        dataType: "json",
        success: function (data) {
            if(data.status)
            {
                location.reload(); //刷新网页
            }else{
                $("#passCuowu").html(data.msg);
            }
        },
        error: function (error) {
            $("#passCuowu").html("API请求失败，请重试");
        }
    });
}
//搜索回车
$("#accessCode").keydown(function(e){
    if(e.keyCode==13){
        getApi();
    }
});
$("#submitBtn").click(function(){
    getApi();
});