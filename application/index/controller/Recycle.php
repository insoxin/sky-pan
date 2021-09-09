<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\FileManage;
use app\common\model\Folders;
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

    }

    public function restore(){
        $ids = input('get.ids');
        $idFs = input('get.idFs');

        $success_ids = [];

        if(empty($ids) && empty($idFs)){
            return json(['status' => 0,'msg' => '请选择需要还原的文件(夹)~']);
        }

        if(!empty($ids)){
            try {
                $stores = Stores::onlyTrashed()->where('id','in',$ids)->select();
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
                $folders = Folders::onlyTrashed()->where('id','in',$idFs)->select();
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

    }

}