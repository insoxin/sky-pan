<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\FileManage;
use app\common\model\Folders;
use app\common\model\Stores;

class File extends Home
{

    public function list(){
        $folder_id = input('get.folder_id',0);
        $search = input('get.search','');

        $file_list = FileManage::ListFile($folder_id,$search,$this->userInfo['id']);

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


}