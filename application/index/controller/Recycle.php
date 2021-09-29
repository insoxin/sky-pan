<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\FileManage;
use app\common\model\Folders;
use app\common\model\Profit;
use app\common\model\Shares;
use app\common\model\Stores;

class Recycle extends Home
{

    public function index(){
        return $this->fetch();
    }


    public function list(){

        $limit = input('get.rows',20);
        $page = input('get.page',1);
        $search = input('get.search','');

        $file_list = FileManage::RecycleFile($search,$this->userInfo['id'],$page,$limit);
        return json($file_list);
    }


    public function clear(){
        // 清空回收站
        $uid = $this->userInfo['id'];

        $success_ids = [];

        try {
            $stores = Stores::onlyTrashed()->where('uid',$uid)->select();
            foreach ($stores as $file){
                // 删除文件
                $file->delete(true);
                // 删除分享信息
                Profit::where('file_id',$file['id'])
                    ->where('uid',$uid)
                    ->delete();

                $success_ids[] = $file['id'];
            }

            $folders = Folders::onlyTrashed()->where('uid',$uid)->select();
            foreach ($folders as $dir){
                // 删除文件
                $dir->delete(true);
                // 获取子文件夹
                $folder_object = getFolderChildFiles($uid,$dir['id']);

                // 子文件夹
                if(!empty($folder_object['folder'])){
                    Folders::where('id','in',$folder_object['folder'])->delete(true);
                }

                // 子文件
                if(!empty($folder_object['file'])){
                    // 删除文件
                    Stores::where('id','in',$folder_object['file'])->delete(true);
                    // 删除分享信息
                    Profit::where('file_id','in',$folder_object['file'])
                        ->where('uid',$uid)
                        ->delete();
                }

                $success_ids[] = $dir['id'];
            }

            return json(['status' => 1,'msg' => '成功清空回收站，共删除 '.count($success_ids).' 个文件(夹)~']);

        }catch (\Throwable $e){
            return json(['status' => 0,'msg' => $e->getMessage()]);
        }
    }


    public function restore(){
        $ids = input('get.ids');
        $idFs = input('get.idFs');

        $uid = $this->userInfo['id'];

        $success_ids = [];

        if(empty($ids) && empty($idFs)){
            return json(['status' => 0,'msg' => '请选择需要还原的文件(夹)~']);
        }

        if(!empty($ids)){
            try {
                $stores = Stores::onlyTrashed()->where('id','in',$ids)->where('uid',$uid)->select();
                foreach ($stores as $file){
                    //还原文件
                    $file->restore();
                    $success_ids[] = $file['id'];
                }
            }catch (\Throwable $e){
                return json(['status' => 0,'msg' => $e->getMessage()]);
            }
        }

        if(!empty($idFs)){
            try {
                $folders = Folders::onlyTrashed()->where('id','in',$idFs)->where('uid',$uid)->select();
                foreach ($folders as $dir){
                    //还原文件
                    $dir->restore();
                    $success_ids[] = $dir['id'];
                }
            }catch (\Throwable $e){
                return json(['status' => 0,'msg' => $e->getMessage()]);
            }
        }

        return json(['status' => 1,'msg' => '成功还原 '.count($success_ids).' 个文件(夹)~','data' => $success_ids]);
    }


    public function delete(){
        $ids = input('get.ids');
        $idFs = input('get.idFs');

        $uid = $this->userInfo['id'];
        $success_ids = [];

        if(empty($ids) && empty($idFs)){
            return json(['status' => 0,'msg' => '请选择需要删除的文件(夹)~']);
        }

        if(!empty($ids)){
            try {
                $stores = Stores::onlyTrashed()->where('id','in',$ids)->where('uid',$uid)->select();
                foreach ($stores as $file){
                    // 删除文件
                    $file->delete(true);

                    // 删除分享信息
                    Profit::where('file_id',$file['id'])
                        ->where('uid',$uid)
                        ->delete();

                    $success_ids[] = $file['id'];
                }
            }catch (\Throwable $e){
                return json(['status' => 0,'msg' => $e->getMessage()]);
            }
        }

        if(!empty($idFs)){
            try {
                $folders = Folders::onlyTrashed()->where('id','in',$idFs)->where('uid',$uid)->select();
                foreach ($folders as $dir){
                    // 删除文件
                    $dir->delete(true);

                    // 获取子文件夹
                    $folder_object = getFolderChildFiles($uid,$dir['id']);

                    // 子文件夹
                    if(!empty($folder_object['folder'])){
                        Folders::where('id','in',$folder_object['folder'])->delete(true);
                    }

                    // 子文件
                    if(!empty($folder_object['file'])){
                        // 删除文件
                        Stores::where('id','in',$folder_object['file'])->delete(true);
                        // 删除分享信息
                        Profit::where('file_id','in',$folder_object['file'])
                            ->where('uid',$uid)
                            ->delete();
                    }

                    $success_ids[] = $dir['id'];
                }
            }catch (\Throwable $e){
                return json(['status' => 0,'msg' => $e->getMessage()]);
            }
        }

        return json(['status' => 1,'msg' => '成功彻底删除 '.count($success_ids).' 个文件(夹)~','data' => $success_ids]);
    }

}