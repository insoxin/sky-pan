<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\FileManage;

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

}