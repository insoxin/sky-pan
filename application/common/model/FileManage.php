<?php

namespace app\common\model;

use think\Exception;

class FileManage
{

    /**
     * 获取上级目录ID
     * @param $folder_id
     * @param $uid
     * @return mixed
     */
    public static function getFolderPid($folder_id,$uid){
        if(empty($folder_id)){
            $folder_id = Folders::where('uid',$uid)->where('parent_folder',$folder_id)->value('id');
        }
        return $folder_id;
    }

    /**
     * 获取有效的目录ID
     * @param $folder_id
     * @param $uid
     * @return mixed
     */
    public static function getFolderAllowPid($folder_id,$uid){
        if(empty($folder_id)){
            $folder_id = Folders::where('uid',$uid)->where('parent_folder',$folder_id)->value('id');
        }else{
            $folder_id = Folders::where('uid',$uid)->where('id',$folder_id)->value('id');
        }

        return $folder_id;
    }

    /**
     * 获取文件列表
     * @param $folder_id
     * @param $search
     * @param $uid
     * @param $page
     * @param $limit
     * @return array|array[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function ListFile($folder_id,$search,$uid,$page,$limit): array
    {

        //获取当前根目录
        $folder_id = self::getFolderPid($folder_id,$uid);


        $maps = [
            ['uid','=',$uid],
            ['parent_folder','=',$folder_id],
            ['delete_time','null','']
        ];


        $files_sql = db('stores')
            ->where($maps)
            ->where('origin_name','like','%'.$search.'%')
            ->field('id,uid,shares_id,origin_name as name,ext,size,count_down,count_open,update_time')
            ->fetchSql(true)
            ->select();

        $list = db('folders')
            ->where($maps)
            ->where('folder_name','like','%'.$search.'%')
            ->field('id,uid,shares_id,folder_name as name,ext,size,count_down,count_open,update_time')
            ->union($files_sql,true)
            ->page($page,$limit)
            ->select();

        $files_count = db('stores')
            ->where($maps)
            ->where('origin_name','like','%'.$search.'%')
            ->field('id')
            ->count();

        $folder_count = db('folders')
            ->where($maps)
            ->where('folder_name','like','%'.$search.'%')
            ->field('id')
            ->count();

        $data = ['data' => []];

        foreach ($list as $item){

            $file_item = [
                'file_name' => '',
                'file_size' => '-',
                'url' => '',
                'url_pass' => '',
                'count_down' => '<font color="#f81" size="4">'.$item['count_down'].'</font>',
                'count_open' => $item['count_open'],
                'update_time' => date('Y-m-d H:i',$item['update_time'])
            ];

            if($item['ext'] == 755){
                $file_item['file_name'] = '
                <img class="file-icon" src="'.getFileIcon('dir','index').'" />
                <text class="filename folder" id="t'.$item['id'].'" data-id="'.$item['id'].'"  data-filename="'.$item['name'].'" data-folder="1">'.$item['name'].'</text>
                <div class="gengduo" onclick="clickGengduo(event,'.$item['id'].')">  
                    <span><em class="icon icon-more icon-color" title="更多"></em></span>
                </div>';
                $share_info = Shares::getShare($item['uid'],$item['id'],1);
            }else{
                $file_item['file_name'] = '
                <img class="file-icon" src="'.getFileIcon($item['ext'],'index').'" />
                <text class="filename" id="t'.$item['id'].'" data-id="'.$item['id'].'"  data-filename="'.$item['name'].'" data-folder="0">'.$item['name'].'</text>
                <div class="gengduo" onclick="clickGengduo(event,'.$item['id'].')">  
                    <span><em class="icon icon-more icon-color" title="更多"></em></span>
                </div>';
                $share_info = Shares::getShare($item['uid'],$item['id'],0);
                $file_item['file_size'] = countSize($item['size']);
            }

            $is_folder = $item['ext'] == 755 ? 1 : 0;

            if(!empty($share_info)){
                $share_url = getShareUrl($share_info['code']);
                $file_item['url'] = '<a id="'.$item['id'].'-url" data-id="'.$item['id'].'" data-pass="'.$share_info['pwd'].'"  data-pass-status="'.$share_info['pwd_status'].'" href="'.$share_url.'" target="_blank">'.$share_url.'</a>';
                if(empty($share_info['pwd']) || $share_info['pwd_status'] == 0){
                    $share_info['pwd'] = '-';
                }
                $file_item['url_pass'] = '<a id="'.$item['id'].'-pass" onclick="if($(\'#'.$item['id'].'-pass\').html() != \'-\'){CopyText($(\'#'.$item['id'].'-pass\').html(),\'复制提取码成功~\')}else{setPass('.$item['id'].',$(\'#t'.$item['id'].'\').html(),'.$is_folder.')}">'.$share_info['pwd'].'</a>';
            }

            $data['data'][] = $file_item;
        }

        //查询上级目录
        $data['parent'] = "";

        if($folder_id != 0){
            $parent_ids  = self::getUserDirParents($uid,$folder_id);
            if(!empty($parent_ids)){
                $folder_parents = Folders::where('id','in',$parent_ids)
                    ->where('uid',$uid)
                    ->field('id,folder_name')
                    ->order('id desc')
                    ->select()->toArray();

                $folder_parents = array_reverse($folder_parents);
                $data['parent'] = $folder_parents;
            }
        }

        $data['total'] = $files_count + $folder_count;

        return $data;
    }


    public static function ShareListFile($folder_id,$uid){
        //获取当前根目录
        $folder_id = self::getFolderPid($folder_id,$uid);

        $maps = [
            ['uid','=',$uid],
            ['parent_folder','=',$folder_id],
            ['delete_time','null','']
        ];

        $files_sql = db('stores')
            ->where($maps)
            ->field('id,uid,shares_id,origin_name as name,ext,size,update_time')
            ->fetchSql(true)
            ->select();

        $list = db('folders')
            ->where($maps)
            ->field('id,uid,shares_id,folder_name as name,ext,size,update_time')
            ->union($files_sql,true)
            ->select();


        $share_ids = array_column($list,'shares_id');
        // 查询分享代码
        $share_list = Shares::where('id','in',$share_ids)->column('code','id');

        // 返回数据
        $data = [];

        foreach ($list as $item){
            $type = $item['ext'] == 755 ? 'dir' : $item['ext'];

            $files = [
                'id' => $item['id'],
                'type' => $item['ext'] == 755 ? 'dir' : 'file',
                'icon' => getFileIcon($type,'index'),
                'name' => $item['name'],
                'size' => empty($item['size']) ? '-' : countSize($item['size']),
                'time' => friendDate($item['update_time'])
            ];

            $code = $share_list[$item['shares_id']] ?? '';

            if(empty($code)){
                $files['url'] = 'javascript:;';
            }else{
                $files['url'] = getShareUrl($code);
            }

            $data[] = $files;
        }

        return $data;
    }


    public static function FolderList($folder_id,$uid): array
    {
        //获取当前根目录
        $folder_id = self::getFolderPid($folder_id,$uid);

        $maps = [
            ['uid','=',$uid],
            ['parent_folder','=',$folder_id],
            ['delete_time','null','']
        ];

        return Folders::withTrashed()
            ->where($maps)
            ->field('id,folder_name')
            ->select()->each(function($item) use ($uid){
                if(Folders::withTrashed()->where('parent_folder',$item['id'])->where('uid',$uid)->count() > 0){
                    $item['down'] = 1;
                }else{
                    $item['down'] = 0;
                }
                return $item;
            })->toArray();
    }

    /**
     * 回收站文件列表
     * @param $search
     * @param $uid
     * @param $page
     * @param $limit
     * @return array|array[]
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function RecycleFile($search,$uid,$page,$limit){

        $file_sql = db('stores')
            ->where('uid',$uid)
            ->where('delete_time','not null')
            ->where('origin_name','like','%'.$search.'%')
            ->field('id,uid,shares_id,origin_name as name,ext,size,count_down,count_open,update_time')
            ->fetchSql(true)
            ->select();

        $list = db('folders')
            ->where('uid',$uid)
            ->where('delete_time','not null')
            ->where('folder_name','like','%'.$search.'%')
            ->field('id,uid,shares_id,folder_name as name,ext,size,count_down,count_open,update_time')
            ->union($file_sql,true)
            ->page($page,$limit)
            ->select();

        $stores_count = db('stores')
            ->where('uid',$uid)
            ->where('delete_time','not null')
            ->where('origin_name','like','%'.$search.'%')
            ->field('id')
            ->count();

        $folders_count = db('folders')
            ->where('uid',$uid)
            ->where('delete_time','not null')
            ->where('folder_name','like','%'.$search.'%')
            ->field('id')
            ->count();

        $data = ['data' => []];

        foreach ($list as $item){

            $file_item = [
                'file_name' => '',
                'file_size' => '-',
                'url' => '',
                'url_pass' => '',
                'count_down' => '<font color="#f81" size="4">'.$item['count_down'].'</font>',
                'count_open' => $item['count_open'],
                'update_time' => date('Y-m-d H:i',$item['update_time'])
            ];

            if($item['ext'] == 755){
                $file_item['file_name'] = '
                <img class="file-icon" src="'.getFileIcon('dir','index').'" />
                <text class="filename folder" id="t'.$item['id'].'" data-id="'.$item['id'].'"  data-filename="'.$item['name'].'" data-folder="1">'.$item['name'].'</text>
                <div class="gengduo" onclick="clickGengduo(event,'.$item['id'].')">  
                    <span><em class="icon icon-more icon-color" title="更多"></em></span>
                </div>';
                $share_info = Shares::getShare($item['uid'],$item['id'],1);
            }else{
                $file_item['file_name'] = '
                <img class="file-icon" src="'.getFileIcon($item['ext'],'index').'" />
                <text class="filename" id="t'.$item['id'].'" data-id="'.$item['id'].'"  data-filename="'.$item['name'].'" data-folder="0">'.$item['name'].'</text>
                <div class="gengduo" onclick="clickGengduo(event,'.$item['id'].')">  
                    <span><em class="icon icon-more icon-color" title="更多"></em></span>
                </div>';
                $share_info = Shares::getShare($item['uid'],$item['id'],0);
                $file_item['file_size'] = countSize($item['size']);
            }

            $is_folder = $item['ext'] == 755 ? 1 : 0;

            if(!empty($share_info)){
                $share_url = getShareUrl($share_info['code']);
                $file_item['url'] = '<a id="'.$item['id'].'-url" data-id="'.$item['id'].'" data-pass="'.$share_info['pwd'].'"  data-pass-status="'.$share_info['pwd_status'].'" href="'.$share_url.'" target="_blank">'.$share_url.'</a>';
                if(empty($share_info['pwd']) || $share_info['pwd_status'] == 0){
                    $share_info['pwd'] = '-';
                }
                $file_item['url_pass'] = '<a id="'.$item['id'].'-pass" onclick="if($(\'#'.$item['id'].'-pass\').html() != \'-\'){CopyText($(\'#'.$item['id'].'-pass\').html(),\'复制提取码成功~\')}else{setPass('.$item['id'].',$(\'#t'.$item['id'].'\').html(),'.$is_folder.')}">'.$share_info['pwd'].'</a>';
            }

            $data['data'][] = $file_item;
        }

        //查询上级目录
        $data['parent'] = "";

        $data['total'] = $stores_count + $folders_count;

        return $data;

    }

    /**
     * 创建目录
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

        $dir_id = (new Folders)->insertGetId([
            'uid' => $uid,
            'folder_name' => $folder_name,
            'parent_folder' => $folder_pid,
            'desc' => $folder_desc,
            'create_time' => time(),
            'update_time' => time()
        ]);

        $share_id = Shares::addShare($uid,$dir_id,1);

        (new Folders)->where('id',$dir_id)->update(['shares_id' => $share_id]);

        return $dir_id;
    }

    /**
     * 获取用户上级目录列表
     * @param $uid
     * @param $folder_id
     * @return array
     */
    protected static function getUserDirParents($uid,$folder_id): array
    {
        $folders = self::getUserDirs($uid);
        return self::getDirParents($folders,$folder_id);
    }

    /**
     * 递归获取上级目录
     * @param $folders
     * @param $folder_id
     * @return array
     */
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

    /**
     * 获取用户目录
     * @param $uid
     * @return array
     */
    protected static function getUserDirs($uid): array
    {
        return Folders::where('uid',$uid)->field('id,parent_folder as pid')->select()->toArray();
    }

}