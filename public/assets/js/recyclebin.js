
/**
 * 删除/还原 回收站的文件
 */
function Recyclebin(ids,idFs,action)
{
    var url = "/recycle/"+action+"?ids="+ids+"&idFs="+idFs;
    $.ajax({
        type: "get",
        url: url,
        dataType: "json",
        success: function (data2) {
            if(data2.status){
                data = data2.data;
                for(var i=0;i<data.length;i++)
                {
                    $("text[data-id='"+data[i]+"']").parent().parent().remove();
                }

                layer.msg(data2.msg, {time:1500, icon:1, shift:4},function(){
                    window.location.reload();
                });
            }else{
                layer.alert(data2.msg,{icon:2});
            }
        },
        error: function (error) {
            layer.alert("API请求失败，请联系客服",{icon:2});
        }
    });
}



//菜单栏删除批量删除
function CheckDelete()
{
    var data = $('#table').bootstrapTable('getSelections');
    
    layer.confirm('确定要彻底删除所选的 '+data.length+' 个文件(夹)吗？', 
    {
        title: "一旦删除无法找回！",
        shadeClose: true,
        icon: 3,
        btn: ['确定','取消'] //按钮
    },
    function(index){
        var data = getCheckIds();
        var ids = data['ids'];
        var idFs = data['idFs'];
        Recyclebin(ids,idFs,"delete");
    },
    function(){
    });
}

//菜单栏还原
function CheckBack()
{
    var data = getCheckIds();
    var ids = data['ids'];
    var idFs = data['idFs'];
    Recyclebin(ids,idFs,"restore");
}


//菜单栏清空回收站
function CheckEmpty()
{
    layer.confirm('确定要清空回收站吗？', 
    {
        title: "一旦删除无法找回！",
        shadeClose: true,
        icon: 3,
        btn: ['确定','取消'] //按钮
    },
    function(index){
        var url = "/recycle/clear";
        $.ajax({
            type: "get",
            url: url,
            dataType: "json",
            success: function (data2) {
                if(data2.status)
                {
                    getTable();
                    layer.msg(data2.msg, {time:1500, icon:1, shift:4});
                }else{
                    layer.alert(data2.msg,{icon:2});
                }
            },
            error: function (error) {
                layer.alert("API请求失败，请联系客服",{icon:2});
            }
        });
    },
    function(){
    });
}


/*   右键菜单 -------开始   */
$(".context-menu .list li").click(function(){
    var ac = $(this).html();
    var id = $('.context-menu .list').attr("data-id");
    var filename = $('.context-menu .list').attr("data-filename");
    var is_folder = $('.context-menu .list').attr("data-folder");
    var ids = is_folder == "1" ? "" : id;
    var idFs = is_folder == "1" ? id : "";

    switch(ac) {
        case "刷新":{
            getTable($('#sousuo').val());
            break;
        }
        case "重新加载页面":{
            location.reload();
            break;
        }
        case "彻底删除":{
            layer.confirm('确定要彻底删除（'+filename+"）吗？", 
            {
                title: "一旦删除无法找回！",
                shadeClose: true,
                icon: 3,
                btn: ['确定','取消'] //按钮
            },
            function(index){
                Recyclebin(ids,idFs,"delete");
            },
            function(){
            });
            break;
        }
        case "还原":{
            Recyclebin(ids,idFs,"restore");
        }
        default:{
            break;
        }
    } 
});
/*   右键菜单 -------结束   */