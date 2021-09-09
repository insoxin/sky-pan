<?php

namespace app\common\model;

use think\Exception;

class FileManage
{

    public static function getFolderPid($folder_id,$uid){
        if(empty($folder_id)){
            $folder_id = Folders::where('uid',$uid)->where('parent_folder',$folder_id)->value('id');
        }
        return $folder_id;
    }

    public static function ListFile($folder_id,$search,$uid){

        //获取当前根目录
        $folder_id = self::getFolderPid($folder_id,$uid);

        $folders = Folders::where('uid',$uid)->where('parent_folder',$folder_id)->select();
        $files = Stores::where('uid',$uid)->where('parent_folder',$folder_id)->select();

        $data = [
            'data' => []
        ];

        foreach ($folders->toArray() as $item){
            $data['data'][] = [
                'file_name' => '
                <img class="file-icon" src="'.getFileIcon('dir','index').'" />
                <text class="filename folder" id="t'.$item['id'].'" data-id="'.$item['id'].'"  data-filename="'.$item['folder_name'].'" data-folder="1">'.$item['folder_name'].'</text>
                <div class="gengduo" onclick="clickGengduo(event,'.$item['id'].')">  
                    <span><em class="icon icon-more icon-color" title="更多"></em></span>
                </div>',
                'file_size' => '',
                'url' => '',
                'url_pass' => '',
                'count_down' => '<font color="#f81" size="4">0</font>',
                'count_open' => 0,
                'update_time' => date('Y-m-d H:i',$item['create_time'])
            ];
        }

        foreach ($files->toArray() as $item){
            $data['data'][] = [
                'file_name' => '
                <img class="file-icon" src="/static/user/file/code.png" />
                <text class="filename" id="t3080" data-id="3080"  data-filename="QQBrowserConfig.dat" data-folder="0">QQBrowserConfig.dat</text>
                <div class="gengduo" onclick="clickGengduo(event,3080)">  
                    <span><em class="icon icon-more icon-color" title="更多"></span>
                </div>',
                'file_size' => '114B',
                'url' => '',
                'url_pass' => '',
                'count_down' => '<font color="#f81" size="4">0</font>',
                'count_open' => 0,
                'update_time' => $item['update_time']
            ];
        }

        //查询上级目录
        $data['parent'] = "";

        if($folder_id != 0){
            $parent_ids  = self::getUserDirParents($uid,$folder_id);
            if(!empty($parent_ids)){
                $data['parent'] = Folders::where('id','in',$parent_ids)
                    ->where('uid',$uid)
                    ->field('id,folder_name')
                    ->select()->toArray();
            }
        }

        $data['total'] = count($data['data']);

        return $data;
    }

    /**
     * @throws Exception
     */
    public static function createFolder($folder_pid, $folder_name, $folder_desc, $uid){

        $folder_name = str_replace(" ","",$folder_name);
        $folder_name = str_replace("/","",$folder_name);

        //获取当前根目录
        $folder_pid = self::getFolderPid($folder_pid,$uid);

        if(empty($folder_name)){
            throw new Exception('目录名不能为空');
        }


        // 判断路径
        if(Folders::where('id',$folder_pid)->where('uid',$uid)->find() == null){
            throw new Exception('文件夹路径不存在');
        }

        // 是否重复
        if(
            Folders::where('parent_folder',$folder_pid)
                ->where('folder_name',$folder_name)
                ->where('uid',$uid)->find() != null
        ){
            throw new Exception('文件夹已存在');
        }

        return (new Folders)->insertGetId([
            'uid' => $uid,
            'folder_name' => $folder_name,
            'parent_folder' => $folder_pid,
            'desc' => $folder_desc,
            'create_time' => time()
        ]);
    }

    protected static function getUserDirParents($uid,$folder_id): array
    {
        $folders = self::getUserDirs($uid);
        return self::getDirParents($folders,$folder_id);
    }

    protected static function getDirParents($folders,$folder_id): array
    {
        $ids = [];
        foreach ($folders as $item){
            if($item['id'] == $folder_id){
                if($item['pid']){
                    $ids[] = $item['id'];
                    $ids = array_merge(self::getDirParents($folders,$item['pid']),$ids);
                }
            }
        }
        return $ids;
    }

    protected static function getUserDirs($uid): array
    {
        return Folders::where('uid',$uid)->field('id,parent_folder as pid')->select()->toArray();
    }

}