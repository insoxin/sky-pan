/**
 * 回收站和文件管理通用函数
 */

if (!!window.ActiveXObject || "ActiveXObject" in window){
  alert("您的浏览器版本太过久了，可能无法正常访问，请使用360极速浏览器或者Chrome浏览器再来~");
}

//弹框
function noty_msg(type,msg,url,time){
    var msg = msg || "";
    var time = time || 800;
    if(type){
        type = "success";
    }else{
        type = "error";
    }
    if(url){
        var modal = true;
    }else{
        var modal = false;
    }
    Wind.use('noty', function () {
        noty({
            text: msg,
            type: type,
            layout: 'topCenter',
            modal: modal,
            timeout: time,
            callback: {
                afterClose: function () {
                    if(url){
                        window.location.href = url;
                    }
                }
            }
        }).show();
    });
}



//阻止事件 冒泡传播
function stopBubbling(e) {
    e = window.event || e;
    if (e.stopPropagation) {
        e.stopPropagation();      //阻止事件 冒泡传播
    } else {
        e.cancelBubble = true;   //ie兼容
    }
}


/*   右键菜单 -------开始   */


//点击悬浮更多按钮打开右键菜单
function clickGengduo(e,id) { 
    //清空事件 防止冲突
    window.onclick=function(e){
    }
    openList(1,$("#t"+id),e.pageX,e.pageY);
}

//鼠标悬浮显示更多按钮
function HoverGengduo()
{
    //悬浮事件
    $('#table tbody tr').hover(function(){
        $(this).find(".gengduo").show();   //第一个div显示
    },function(){
        $(this).find(".gengduo").hide();
    });
}


//清空右键菜单数据
function openListEmpty(obj)
{
    obj.attr("data-id","");    //清空
    obj.attr("data-filename",""); //清空
    obj.attr("data-folder",""); //清空
}

//打开右键菜单
function openList(isFile,obj,x,y)
{
    //判断是不是空白处右键
    if(isFile){
        var _jq = "#list1";
        var _jq2 = "#list2";
        
        openListEmpty($(_jq)); //清空以前的设置

        $(_jq).attr("data-id", obj.attr("data-id")); //设置ID
        $(_jq).attr("data-filename",obj.attr("data-filename")); //设置文件名
        $(_jq).attr("data-folder",obj.attr("data-folder")); //设置是不是文件夹

    }else{
        var _jq = "#list2";
        var _jq2 = "#list1";
    }
    
    $(_jq2).hide();
    $(_jq).show();
    //改变菜单的位置到事件发生的位置
    $(_jq).css('left', x);
    $(_jq).css('top', y);
    
    //延迟安装 防止更多按钮的点击冲突
    setTimeout(
        function(){
            window.onclick=function(e){
                //用户触发click事件就可以关闭了，因为绑定在window上，按事件冒泡处理，不会影响菜单的功能
                $(_jq).hide();

                if(isFile){
                    openListEmpty($(_jq));
                }
            }
        }
    , 1);
}

//安装右键事件
function clickList()
{
    var name = $("#menubar .select").attr("data");
    if(name == "shouyi")
    {
        $('.el-main').unbind("mousedown");
        $('.el-main').unbind("contextmenu");
        return;
    }
    
    //禁用默认右键事件
    $('.el-main').on('contextmenu', function () {
        if(!window.getSelection().toString()) {  //判断用户是否选中了文字
            return false;
        }
    });

    //右键菜单
    $('#table tbody tr').mousedown(function(e){
        if(window.getSelection().toString())    //判断用户是否选中了文字
        {
            return;
        }
        if(3 == e.which){   //鼠标右键
            openList(1,$(this).find('text'),e.pageX,e.pageY);
        }
        stopBubbling(e);    //防止重叠
    });

    //右键菜单
    $('.el-main').mousedown(function(e){
        if(window.getSelection().toString())    //判断用户是否选中了文字
        {
            return;
        }
        if(3 == e.which){   //鼠标右键
            openList(0,$(this).find('text'),e.pageX,e.pageY);

        }
    });
}
/*   右键菜单 -------结束   */



//复选框批量操作
function CilckCheck()
{
    var data = $('#table').bootstrapTable('getSelections');
    if(data.length)
    {
        $('th[data-field="file_name"] div:first').html("已选中 "+data.length+" 个文件(夹)");
        $("#CilckCheck").show();
        $("#CilckCheck2").hide();
    }else{
        $('th[data-field="file_name"] div:first').html("文件名");
        $("#CilckCheck").hide();
        $("#CilckCheck2").show();
    }
}
//获取复选框选中的ID
function getCheckIds(ids,idFs)
{
    var ids = ids || "";
    var idFs = idFs || "";

    var data = $('#table').bootstrapTable('getSelections');
    for(var i=0;i<data.length;i++)
    {
        $("#CheckTmp").html(data[i]['file_name']);
        var obj = $("#CheckTmp").find("text");
        var id = obj.attr("data-id");
        var isFolder = obj.attr("data-folder");

        if(isFolder=="1"){
            idFs += id+",";
        }else{
            ids += id+",";
        }
    }
    ids = ids.replace(new RegExp('\\,+$', 'g'), '');        //去掉某位ids
    idFs = idFs.replace(new RegExp('\\,+$', 'g'), '');
    return {'ids':ids,idFs:idFs};
}

/**
 * 操控剪切板
 */
function CopyText(text,msg)
{
    var oInput = document.createElement('textarea');
    oInput.value = text;
    document.body.appendChild(oInput);
    oInput.select(); // 选择对象
    document.execCommand("Copy"); // 执行浏览器复制命令
    oInput.className = 'oInput';
    oInput.style.display='none';
    if(window.screen.width < 768){
        layer.msg(msg, {time:1200});
    }
    else{
        layer.msg(msg, {time:1200, icon:1, shift:4});
    }
}


/**
 * 修改文件路径导航
 * @param {路径导航数据} data 
 * @param {数据总数} total 
 * @param {是否搜索} sousuo 
 */
function daohang(data,total,sousuo)
{
    $(".daohang-count").html(total);
    if(sousuo == ""){
        if(data)
        {
            var html = "";
            for(var i=0;i<data.length;i++)
            {
                info = data[i];
                if(info['isMy']){
                    //当前文件夹
                    html += '<span style="cursor:pointer;" onclick="getTable(\'当前文件夹\')">'+info['folder_name']+'</span>';

                    if(i != 0){
                        var shangyji = data[i-1]['id'];
                        $("#daohang-shangyiji").attr("onclick","getTable("+shangyji+")");
                    }else{
                        $("#daohang-shangyiji").attr("onclick","getTable()");
                    }
                }else{
                    html += '<a href="javascript:;" onclick="getTable('+info['id']+')">'+info['folder_name']+'</a>';
                    html += '<span class="fuhao">&gt;</span>';
                }
            }
            $("#daohang").html(html);
    
            $(".daohang-path").show();
            $(".daohang-all").hide();
        }else{
            $(".daohang-all").show();
            $(".daohang-path").hide();
        }
    }else{
        var html = "";
        html += '<span">搜索 "'+sousuo+'"</span>';

        $("#daohang").html(html);

        $(".daohang-path").show();
        $(".daohang-all").hide();
    }
}