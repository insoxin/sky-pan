//获取文件图标 《关于母猪的产后护养研究》
function getIcon(ext){

    ext = ext.toLowerCase();

    var d = new Array();
    d['video'] = ['mp4','avi','mov','rmvb','rm','asf','divx','mpg','mpeg','mpe','wmv','mp4','mkv','vob','swf','flv'];
    d['music'] = ['cd','mp3','flac','ape','wma','mid','midi','mmf','ncm','wav','dts','dsf'];
    d['code']  = ['c','php','py','py3','cpp','h','jar','html','hta','chm','css','js','htm','asp','aspx','jsp','dll','cs','go','sql',
    'xml','vb','java','lib','e','ec','db','bat','vbs','cmd','json','vbe','ocx','conf','sh','dat'];
    d['zip']   = ['zip','tar','gz','rar','7z','arj','z','iso','gho'];
    d['ps']    = ['ps','psd','pdd','eps','iff','tdi','pcx','raw'];
    d['img']   = ['png','bmp','rle','dib','gif','ico','jpeg','jpe','jff','jps','jpg','psb','svg','pbm','mp0'];
    d['fonts'] = ['ttf','eot','woff','otf','woff2'];
    d['text']  = ['txt','md','rtf','ini'];
    d['word']  = ['doc','docx','docm'];
    d['excel'] = ['xls','xlsx','xlsm'];
    d['ppt']   = ['ppt','pptx'];
    d['links'] = ['url','lnk'];
    d['pdf']   = ['pdf','pdp'];
    d['exe']   = ['exe','msi'];
    d['ipa']   = ['ipa'];
    d['apk']   = ['apk'];

    var extName = "file";
    var extName2 = extName;
    for (var index in d){
        //console.log("d["+index+"]  " + d[index]);

        for(var i=0;i<d[index].length;i++){
            
            if(d[index][i] == ext){
                extName = index;
                break;
            }
        }
        if(extName != extName2){
            break;
        }
    }
    return extName;
}


//打开上传窗口
function upload(){
        
    if(window.screen.width < 768){
        var area = ['99.5%','413px'];
        var offset = '';
    }
    else{
        var area = ['520px','413px'];
        var offset = ['15%','13%'];
    }
    layer.open({
        type: 2,
        title: '文件上传',
        area: area,
        offset: offset,
       // shadeClose: true,   // 是否点击遮罩关闭
        content: '/user/upload.html',
        end:function () {
            getTable('当前文件夹'); //刷新数据
        }
    });
}


//打开移动到
function moveFiles(ids="",idFs=""){
    
    if(ids =="" && idFs==""){
        var data = getCheckIds();
        ids = data['ids'];
        idFs = data['idFs'];
    }
    if(window.screen.width < 768){
        var area = ['99.5%','355px'];
        var offset = '';
    }
    else{
        var area = ['520px','355px'];
        var offset = ['15%','13%'];
    }

    layer.open({
        type: 2,
        title: '移动到',
        area: area,
        offset: offset,
        shadeClose: true,   // 是否点击遮罩关闭
        content: '/user/files/moveFiles.html?ids='+ids+"&idFs="+idFs,
        end:function () {
            //getTable(); //刷新数据
        }
    });
}


//重命名编辑框
function rename(){
    $(".filename").editable({
        toggle: 'manual',
        validate: function (v) {
            if (!v) return '文件名不能为空';

            var obj = $(this);
            var id = obj.attr("data-id");
            var filename = obj.attr("data-filename");
            var is_folder = obj.attr("data-folder");

            if (filename != v) {
                $.ajax({
                    type: "get",
                    url: '/user/files/toReanme.html?id=' + id + "&file_name=" + v + "&is_folder="+is_folder,
                    dataType: "json",
                    success: function (data) {
                        
                        if(data.status)
                        {
                            obj.attr("data-filename", v);
                            layer.msg(data.msg, {time:1000, icon:1, shift:4});
                        }else{
                            layer.alert(data.msg,{icon:2});
                        }

                    },
                    error: function (error) {
                        layer.alert("API请求失败，请联系客服",{icon:2});
                    }
                });
            }

        },
        mode: "inline", //编辑框的模式：支持popup和inline两种模式，默认是popup
        type: 'tel'
    });
}


/*   新建文件夹 -------开始   */
var setPassHtml = $("#setPass").html();
$("#setPass").html("");

//新建文件夹窗口
function mkdir() {
    layer.open({
        type: 2,
        title: '新建文件夹',
        area: ['342px', '350px'],
        fixed: false, //不固定
        shadeClose: true,   //遮罩层关闭
        content: '/user/folder/addFolder.html',
        end:function () {
            //location.reload()
        }
    });
}
/*   新建文件夹 -------end   */


//菜单栏删除批量删除
function CheckDelete()
{
    var data = $('#table').bootstrapTable('getSelections');
    layer.confirm('确定要删除所选的 '+data.length+' 个文件(夹)吗？', 
    {
        shadeClose: true,
        icon: 3,
        btn: ['确定','取消'] //按钮
    },
    function(index){
        var data = getCheckIds();
        var ids = data['ids'];
        var idFs = data['idFs'];

        //console.log(ids,idFs);

        
        var url = "/user/files/toDeleteAll.html?ids="+ids+"&idFs="+idFs;
        $.ajax({
            type: "get",
            url: url,
            dataType: "json",
            success: function (data2) {
                if(data2.status)
                {
                    data = data2.data;
                    for(var i=0;i<data.length;i++)
                    {
                        $("text[data-id='"+data[i]+"']").parent().parent().remove();
                    }

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


//设置提取码窗口
function setPass(id,filename)
{
    var idU = "#"+id+"-url";
    var idP = "#"+id+"-pass";
    var Url2= $(idU).html();
    var urlId= $(idU).attr("data-id");
    var Pass = $(idU).attr("data-pass");
    var PassStatus = $(idU).attr("data-pass-status");
    
    var Url = "链接："+Url2;
    
    if(window.screen.width < 768){
        var area = ['342px','380px'];
    }
    else{
        var area = ['325px','270px'];
    }

    layer.open({
        title: "分享设置："+filename,
        area: area,
        type: 1,
        shift: 7,
        resize: false, // 是否允许拉伸
        shadeClose: true,   //遮罩层关闭
        content: setPassHtml,
    });

    var data = Url;
    if(PassStatus == "1")  //密码开关
    {
        data = Url+"\n提取码："+Pass;
        $("#setPassClick").attr("checked","");
    }else{
        $("#setPassPass").attr("disabled","disabled");

    }
    
    $("#setPassUrl").val(data);
    $("#setPassPass").val(Pass);

    //密码开关冒泡
    $("#setPassClick").click(function(){
        if($("#setPassPass").attr("disabled"))  //是否禁用
        {
            var tmp = Url+"\n提取码："+$("#setPassPass").val();
            $("#setPassUrl").val(tmp);
            $("#setPassPass").removeAttr("disabled");
        }else{
            $("#setPassUrl").val(Url);
            $("#setPassPass").attr("disabled","disabled");
        }
    });

    //密码修改冒泡
    $("#setPassPass").bind("input propertychange", function() {
        var data = "链接："+Url2+"\n提取码："+$("#setPassPass").val();
        $("#setPassUrl").val(data);
    });

    if(window.screen.width < 768){
        //文本框击冒泡
        $('#setPassUrl').click(function () {
            CopyText($('#setPassUrl').val(),"复制分享链接成功~");
        });
    }
    else{
        //文本框双击冒泡
        $('#setPassUrl').dblclick(function () {
            CopyText($('#setPassUrl').val(),"复制分享链接成功~");
        });
    }

    //保存密码
    $("#setPassBtn").click(function(){
        var pass = $("#setPassPass").val();
        if(pass.length == 0)
        {
            $('#setPassPass').focus();
            return layer.msg('请填写提取码！', {time: 700});
        }
        if(pass.length < 2 || pass.length > 6)
        {
            return layer.alert("提取码长度最短为2，最长为6，请重新填写",{icon:2});
        }

        var passStatus = $("#setPassPass").attr("disabled") ? 0 : 1;
        var url = "/user/files/toSetPass.html?id="+urlId+"&pass="+pass+"&pass_status="+passStatus;
        $.ajax({
            type: "get",
            url: url,
            dataType: "json",
            success: function (data2) {
                if(data2.status)
                {
                    if(passStatus){
                        $(idP).html(pass);
                    }else{
                        $(idP).html("-");
                    }
                    $(idU).attr("data-pass",pass);
                    $(idU).attr("data-pass-status",passStatus);
                    layer.closeAll();
                    layer.msg(data2.msg, {time:1000, icon:1, shift:4});
                }else{
                    layer.alert(data2.msg,{icon:2});
                }
            },
            error: function (error) {
                layer.alert("API请求失败，请联系客服",{icon:2});
            }
        });
    });
    
}


/*   右键菜单 -------开始   */
$(".context-menu .list li").click(function(){
    var ac = $(this).html();
    var id = $('.context-menu .list').attr("data-id");
    var filename = $('.context-menu .list').attr("data-filename");
    var is_folder = $('.context-menu .list').attr("data-folder");

    //if(!id){
    //    return layer.msg('请选择文件~', {time: 700});;
    //}
    switch(ac) {
        case "刷新数据":{
            getTable("当前文件夹",$('#sousuo').val());
            break;
        }
        case "重新加载页面":{
            location.reload();
            break;
        }
        case "设置提取码":{
            setPass(id,filename);
            break;
        }
        case "新建文件夹":{
            mkdir();
            break;
        }
        case "移动到":{
            if(is_folder=="1"){
                moveFiles("",id);
            }else{
                moveFiles(id);
            }
            break;
        }
        case "下载":{
            if(is_folder=="1"){
                var Url2= $("#"+id+"-url").html();
                window.open(Url2);
            }
            window.open("/user/file/toDown.html?id="+id);
            break;
        }
        case "重命名":{
            setTimeout(function (){
                $("text[data-id='"+id+"'][data-folder="+is_folder+"]").editable('toggle');
            },1);
            break;
        }
        case "复制链接":{
            var Url2= $("#"+id+"-url").html();
            var Pass = $("#"+id+"-url").attr("data-pass");
            var PassStatus = $("#"+id+"-url").attr("data-pass-status");

            
            if(PassStatus == "1")  //密码开关
            {
                Url2 = "链接："+Url2+"\n提取码："+Pass;
            }
            
            CopyText(Url2,"复制分享链接成功~");
            break;
        }
        case "编辑文件":{


            if(is_folder=="1"){
                var title = "编辑文件夹";
                var url = '/user/folder/editFolder.html?id='+id;
                var area = ['342px', '350px'];
            }else{
                
                var title = "编辑文件";
                var url = '/user/file/editFile.html?id='+id;
                var area = ['450px', '460px'];
            }

            layer.open({
                type: 2,
                title:  title,
                area: area,
                fixed: false, //不固定
                shadeClose: true,   //遮罩层关闭
                content: url,
                end:function () {
                    //location.reload()
                }
            });
            break;
        }
        case "删除":{
                
            var url = "/user/files/toDelete.html?id="+id+"&isFolder="+is_folder;
            layer.confirm('确定要删除（'+filename+"）吗？", 
            {
                shadeClose: true,
                icon: 3,
                btn: ['确定','取消'] //按钮
            },
            function(index){
                $.ajax({
                    type: "get",
                    url: url,
                    dataType: "json",
                    success: function (data) {
                        if(data.status)
                        {
                            $("text[data-id='"+id+"']").parent().parent().remove();
                            layer.msg(data.msg, {time:1000, icon:1, shift:4});
                        }else{
                            layer.alert(data.msg,{icon:2});
                        }
                    },
                    error: function (error) {
                        layer.alert("API请求失败，请联系客服",{icon:2});
                    }
                });
                layer.close(index);
            },
            function(){
            });
            break;
        }
        default:{
            break;
        }
    } 
});
/*   右键菜单 -------结束   */


//安装文件夹点击事件
function clickFolder()
{
    $(".folder").click(function(){
        getTable($(this).attr("data-id"));
    });
}