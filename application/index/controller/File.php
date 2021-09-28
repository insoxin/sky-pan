<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\FileManage;
use app\common\model\Folders;
use app\common\model\Policys;
use app\common\model\Shares;
use app\common\model\Stores;
use think\Exception;
use think\response\Download;

class File extends Home
{

    public function list(){
        $folder_id = input('get.folder_id',0);
        $search = input('get.search','');

        $page = input('get.page',1);
        $limit = input('get.rows',20);

        $file_list = FileManage::ListFile($folder_id,$search,$this->userInfo['id'],$page,$limit);

        return json($file_list);
    }

    public function mkdir(){

        $data = input('post.');
        $result = $this->validate($data,[
            'folder_id' => 'require|number',
            'folder_name' => 'require|chsDash|max:255',
            'folder_miaoshu' => 'max:255'
        ],[
            'folder_name.chsDash' => '文件名只能是汉字、字母、数字和下划线_及破折号-'
        ]);

        if($result !== true) return json(['status' => 0,'msg' => $result]);

        try {
            $dir_id = FileManage::createFolder($data['folder_id'],$data['folder_name'],$data['folder_miaoshu'],$this->userInfo['id']);

            return json(['status' => 1,'msg' => '新建文件夹成功~','data' => $dir_id]);
        }catch (\Throwable $e){
            return json(['status' => 0,'msg' => $e->getMessage()]);
        }
    }

    public function rename(){
        $id = input('get.id');
        $file_name = input('get.file_name');
        $is_folder = input('get.is_folder');

        $is_folder = intval($is_folder);

        try {
            if($is_folder){
                $result = $this->validate(['file_name' => $file_name],[
                    'file_name' => 'require|chsDash|max:255',
                ],[
                    'file_name.chsDash' => '文件(夹)名只能是汉字、字母、数字和下划线_及破折号-'
                ]);

                $info = Folders::where('id',$id)->where('uid',$this->userInfo['id'])->find();
                if(empty($info)){
                    throw new Exception('文件数据不存在');
                }
                $info['folder_name'] = $file_name;
                $info->save();
            }else{
                $result = $this->validate(['file_name' => $file_name],['file_name' => 'require|max:255',]);

                $info = Stores::where('id',$id)->where('uid',$this->userInfo['id'])->find();
                if(empty($info)){
                    throw new Exception('文件数据不存在');
                }
                $info['origin_name'] = $file_name;
                $info->save();
            }

            if($result !== true){
                throw new Exception($result);
            }

            return json(['status' => 1,'msg' => '重命名成功']);

        }catch (\Throwable $e){
            return json(['status' => 0,'msg' => $e->getMessage()]);
        }
    }

    public function delete(){
        $id = input('get.id');
        $is_folder = input('get.is_folder');
        $uid = $this->userInfo['id'];

        if(empty($id)){
            return json(['status' => 0,'msg' => '删除错误，参数缺失']);
        }

        //删除目录
        if(boolval($is_folder)){

            $pid = Folders::where('id',$id)->value('parent_folder');

            if($pid == 0){
                return json(['status' => 0,'msg' => '删除失败，您不能删除根目录']);
            }

            Folders::destroy(function($query) use($id,$uid){
                $query->where('id','=',$id)->where('uid','=',$uid);
            });

        }else{
            Stores::destroy(function($query) use($id,$uid){
                $query->where('id','=',$id)->where('uid','=',$uid);
            });
        }

        return json(['status' => 1,'msg' => '删除成功']);
    }

    public function edit_folder(){
        $id = input('get.id');
        $info = Folders::get($id);

        if(empty($info)){
            $this->error('数据不存在');
        }

        if($this->request->isPost()){
            $folder_name = input('post.folder_name');
            $folder_miaoshu = input('post.folder_miaoshu');
            $shouyi = input('post.shouyi');

            Folders::where('id',$id)->update([
               'folder_name' => $folder_name,
                'desc' => $folder_miaoshu,
                'size' => $shouyi
            ]);

            return json(['status' => 1,'msg' => '修改文件夹信息成功']);
        }

        $this->assign('info',$info);
        return $this->fetch();
    }

    public function edit_file(){
        $id = input('get.id');
        $info = Stores::get($id);

        if(empty($info)){
            $this->error('数据不存在');
        }

        if($this->request->isPost()){
            $file_name = input('post.file_name');
            $file_miaoshu = input('post.file_miaoshu');

            Stores::where('id',$id)->update([
                'origin_name' => $file_name,
                'desc' => $file_miaoshu
            ]);

            return json(['status' => 1,'msg' => '修改文件信息成功']);
        }

        $this->assign('info',$info);
        return $this->fetch();
    }

    public function delete_all(){
        $ids = input('get.ids');
        $idFs = input('get.idFs');

        $uid = $this->userInfo['id'];
        $success_ids = [];

        if(empty($ids) && empty($idFs)){
            return json(['status' => 0,'msg' => '请选择需要删除的文件(夹)~']);
        }

        if(!empty($ids)){
            $ids_list = explode(',',$ids);
            try {
                foreach ($ids_list as $file){
                    //删除
                    Stores::destroy(function($query) use($file,$uid){
                        $query->where('id','=',$file)->where('uid','=',$uid);
                    });
                    $success_ids[] = $file;
                }
            }catch (\Throwable $e){
                return json(['status' => 0,'msg' => $e->getMessage()]);
            }
        }

        if(!empty($idFs)){
            $idFs_list = explode(',',$idFs);
            try {
                foreach ($idFs_list as $dir){
                    $pid = Folders::where('id',$dir)->value('parent_folder');
                    if($pid == 0){
                        return json(['status' => 0,'msg' => '删除失败，目录操作异常']);
                    }
                    //删除
                    Folders::destroy(function($query) use($dir,$uid){
                        $query->where('id','=',$dir)->where('uid','=',$uid);
                    });
                    $success_ids[] = $dir;
                }
            }catch (\Throwable $e){
                return json(['status' => 0,'msg' => $e->getMessage()]);
            }
        }

        return json(['status' => 1,'msg' => '成功删除 '.count($success_ids).' 个文件(夹)~','data' => $success_ids]);
    }

    public function move_files(){
        $ids = input('get.ids');
        $idFs = input('get.idFs');

        $this->assign('ids',$ids);
        $this->assign('idFs',$idFs);
        return $this->fetch();
    }

    public function folder_list(){
        $folder_id = input('post.folder_id',0);
        $folder = FileManage::FolderList($folder_id,$this->userInfo['id']);
        return json(['status' => 1,'msg' => 'ok','data' => $folder]);
    }

    public function move_to_file(){
        $folder_id = input('post.folder_id',0);
        $ids = input('post.ids');
        $idFs = input('post.idFs');

        $uid = $this->userInfo['id'];

        if(empty($ids) && empty($idFs)){
            return json(['status' => 0,'msg' => '请选择需要移动的文件或者文件夹']);
        }

        $folder_id = FileManage::getFolderPid($folder_id,$uid);

        $root_folder = FileManage::getFolderPid(0,$uid);

        $ids_list = array_filter(explode(',',trim($ids)));
        $idFs_list = array_filter(explode(',',trim($idFs)));

        $success = [];

        //移动目录
        if(!empty($idFs_list)){
            foreach ($idFs_list as $dir){
                if($dir != $root_folder && $dir != $folder_id && !empty($dir)){
                    Folders::where('id',$dir)->where('uid',$uid)->update(['parent_folder' => $folder_id]);
                    $success[] = $dir;
                }
            }
        }

        //移动文件
        if(!empty($ids_list)){
            foreach ($ids_list as $file){
               if(!empty($file)){
                   Stores::where('id',$file)->where('uid',$uid)->update(['parent_folder' => $folder_id]);
                   $success[] = $file;
               }
            }
        }

        return json(['status' => 1,'msg' => '成功移动 '.count($success).' 个文件(夹)~','data' => $success]);
    }

    public function set_share_pass(){
        $id = input('get.id');
        $pass = input('get.pass');
        $is_folder = input('get.is_folder');
        $pass_status = input('get.pass_status');

        $is_folder = intval($is_folder);
        $pass_status = intval($pass_status);

        $uid = $this->userInfo['id'];

        if($is_folder){
            $info = Folders::where('id',$id)->where('uid',$uid)->find();
        }else{
            $info = Stores::where('id',$id)->where('uid',$uid)->find();
        }

        if(empty($info)){
            return json(['status' => 0,'msg' => '文件数据不存在']);
        }

        $result = $this->validate(['pass' => $pass],['pass|提取码' => 'require|alphaNum|length:4,6'],[
            'pass.require' => '提取码必须填写',
            'pass.alphaNum' => '提取码只能为字母或者数字',
            'pass.length' => '提取码长度只能为 4 ~ 6 位字母或者数字'
        ]);

        if($result !== true) return json(['status' => 0,'msg' => $result]);

        Shares::updateShare($info['shares_id'],[
            'pwd' => $pass,
            'pwd_status' => $pass_status
        ]);

        return json(['status' => 1,'msg' => '设置提取码成功']);

    }

    public function user_download(){
        $id = input('get.id',0);
        $info = Stores::where('id',$id)->where('uid',$this->userInfo['id'])->find();

        if(empty($info)){
            $this->error('数据不存在');
        }

        $policy = Policys::get($info['policy_id']);

        if(empty($policy)){
            $this->error('存储策略不存在');
        }



        // 判断策略类型
        switch ($policy['type']){
            case 'local':
                //文件地址
                $file_path = env('root_path').'public'.getSafeDirSeparator($policy->config['save_dir'] . $info['file_name']);
                // 文件不存在
                if(!is_file($file_path)){
                    $this->error('存储文件不存在');
                }
                //下载对象
                $down = new Download($file_path);
                //下载文件
                return $down->name($info['origin_name']);
                break;
            case 'remote':
                $down_url = getDownloadRemote($info['file_name'],$info['origin_name'],$policy->config['server_uri'],'',$policy->config['access_token']);
                $this->redirect($down_url);
                break;
        }

    }

}