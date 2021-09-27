<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\FileManage;
use app\common\model\FileUpload;
use app\common\model\Groups;
use app\common\model\Policys;
use app\common\model\Shares;
use app\common\model\Stores;
use think\Exception;
use think\facade\Cache;

class Upload extends Home
{

    public function index(){

        // 上传策略
        $policy = $this->getPolicy();

        // 上传文件数量限制
        $upload_limit = 20;

        // 上传限制
        $upload_rule = [
            'upsize_byte' => $this->groupData['max_storage'],
            'upsize' => countSize($this->groupData['max_storage']),
            'fsize_byte' => $this->groupData['max_storage'] * $upload_limit,
            'fsize' => countSize($this->groupData['max_storage'] * $upload_limit),
        ];

        $folder_id = FileManage::getFolderAllowPid(0,$this->userInfo['id']);

        $upload_api = $this->getPolicyUrl($policy,[
            'md' => 'upload',
            'size' => $this->groupData['max_storage'],
            'notify' => url('upload/notify','',false,true),
            'root_folder_id' => $folder_id
        ]);

        $this->assign('upload_api',$upload_api);

        $this->assign('upload_limit',$upload_limit);
        $this->assign('upload_rule',$upload_rule);

        return $this->fetch();
    }

    public function notify(){
        $data = input('get.');
        $policy_id = input('get.policy_id');
        $sign = input('get.sign');

        $policy = Policys::where('id',$policy_id)->find();

        if(empty($policy)){
            exit('Fail policy not found.');
        }

        if(empty($sign)){
            exit('Fail is no params a sign.');
        }

        $remote_sign = $this->remote_sign_params($data,$policy['config']['access_token']);

        if($remote_sign != $sign){
            exit('Fail is sign verify.');
        }

        // 加入数据库
        $data = [
            'uid' => $data['uid'],
            'origin_name' => $data['name'],
            'file_name' => $data['path'],
            'size' => $data['size'],
            'meta' => '',
            'mime_type' => $data['mime'],
            'ext' => $data['ext'],
            'parent_folder' => $data['folder_id'],
            'policy_id' => $policy['id'],
            'dir' => '',
            'create_time' => time(),
            'update_time' => time()
        ];

        $file_id = (new Stores)->insertGetId($data);

        $share_id = Shares::addShare($data['uid'],$file_id,0);

        Stores::where('id',$file_id)->update(['shares_id' => $share_id]);

        exit('UPLOAD_SUCCESS');
    }

    public function file(){

        // 存储策略
        $policy = $this->getPolicy();

        try {
            // 上传文件
            (new FileUpload())
                ->source($this->userInfo['id'],$policy)
                ->upload();

        }catch (Exception $e){
            return json(['code' => 0,'msg' => $e->getMessage()]);
        }

        exit;

        // folder uid policy

        // 超时时间5分钟
        @set_time_limit(5 * 60);

        // 目录校验获取
        $folder_id = input('post.folder_id',0);

        $folder_id = FileManage::getFolderAllowPid($folder_id,$this->userInfo['id']);

        // 获取上传文件大小
        $size = input('post.size',0);

        // 获取上传文件名
        $up_filename = input('post.name','');

        if(empty($up_filename)){
            return json(['code' => 0,'msg' => '上传文件名异常']);
        }

        if(empty($size)){
            return json(['code' => 0,'msg' => '文件大小异常']);
        }

        if(empty($folder_id)){
            return json(['code' => 0,'msg' => '上传目标文件夹不存在']);
        }

        //文件获取
        $files = request()->file('file');

        if(empty($files)){
            return json(['code' => 0,'msg' => '请选择上传的文件']);
        }

        // 存储策略
        $policy = $this->getPolicy();

        // 判断存储类型
        if($policy['type'] != 'local'){
            return json(['code' => 0,'msg' => '您不可以使用该存储策略']);
        }

        //判断存储类型
        $paths = $this->getUploadPaths($policy);

        $file_validate = [];

        // 验证文件大小
        if(!empty($policy['max_size'])){
            $file_validate['size'] = $this->groupData['max_storage'];

            if($size > $this->groupData['max_storage']){
                return json(['code' => 0,'msg' => '单文件最大上传大小'.countSize($this->groupData['max_storage'])]);
            }

        }

        // 验证文件类型
        if(!empty($policy['filetype'])){
            $file_validate['ext'] = $policy['filetype'];
            $file_ext = $this->getFileExt($up_filename);
            $allow_ext = explode(',',$policy['filetype']);
            if(!in_array($file_ext,$allow_ext)){
                return json(['code' => 0,'msg' => '不允许上传此类型的文件']);
            }
        }

        $chunk = input('post.chunk',0);
        $chunks = input('post.chunks',0);

        $post_name = input('post.name','');

        if(empty($post_name)){
            return json(['code' => 0,'msg' => '文件名不能为空']);
        }

        // 分片上传
        if(!empty($chunks)){
            $chunks_key = input('post.chunks_key');

            $real_chunk_path = env('root_path') . './public/chunks';

            is_dir($real_chunk_path) || mkdir($real_chunk_path, 0777, true);

            $chunk_path = realpath($real_chunk_path);


            if(empty($chunks_key)){
                return json(['code' => 0,'msg' => '分片密钥错误']);
            }

            $chunks_save_name = 'chunks_'.$this->userInfo['id'].'_'.$chunks_key;

            $chunk_list = Cache::get($chunks_save_name) ?? [];

            try {
                $chunk_files = $this->getChunkFile();

                if(strlen($chunk_files) > (2 * 1024 * 1024)){
                    throw new Exception('分片错误');
                }

                $chunks_filename = 'chunks_'.$this->userInfo['id'].$this->getRandomKey().'_'.$chunk;

                $chunkObj = fopen ($chunk_path . DIRECTORY_SEPARATOR .$chunks_filename.".chunk","w+");
                $chunkObjWrite = fwrite ($chunkObj,$chunk_files);

                if(!$chunkObj || !$chunkObjWrite){
                    throw new Exception('分片创建错误');
                }

                // 加入分片文件
                $chunk_list[] = $chunks_filename;

                Cache::set($chunks_save_name,$chunk_list);

                // 判断分片是否上传完成
                if($chunk == ($chunks - 1)){

                    // 融合文件名
                    $combine_name = "file_".$this->getRandomKey(8);

                    $fileObj = fopen($chunk_path.DIRECTORY_SEPARATOR.$combine_name,"a+");

                    foreach ($chunk_list as $value) {
                        $chunkObj = fopen($chunk_path.DIRECTORY_SEPARATOR .$value.".chunk", "rb");
                        if(!$fileObj || !$chunkObj){
                            throw new Exception('文件创建失败');
                        }
                        $content = fread($chunkObj, (2 * 1024 * 1024));
                        fwrite($fileObj, $content, (2 * 1024 * 1024));
                        unset($content);
                        fclose($chunkObj);
                        unlink($chunk_path.DIRECTORY_SEPARATOR .$value.".chunk");
                    }

                    // 移动文件
                    is_dir($paths['path']) ?: mkdir($paths['path'],0777,true);

                    $ext = strtolower(pathinfo($post_name, PATHINFO_EXTENSION));

                    $save_file_name = $paths['path'].$paths['name'].'.'.$ext;

                    if(!@rename($chunk_path.DIRECTORY_SEPARATOR.$combine_name,$save_file_name)){
                        throw new Exception('融合文件创建失败');
                    }

                    $size = filesize($save_file_name);

                    $finfo = finfo_open(FILEINFO_MIME_TYPE);

                    $mime_type = finfo_file($finfo,$save_file_name);

                    // 加入数据库
                    $data = [
                        'uid' => $this->userInfo['id'],
                        'origin_name' => $post_name,
                        'file_name' => $paths['file'] . $paths['name'] .'.'.$ext,
                        'size' => $size,
                        'meta' => '',
                        'mime_type' => $mime_type,
                        'ext' => $ext,
                        'parent_folder' => $folder_id,
                        'policy_id' => $policy['id'],
                        'dir' => '',
                        'create_time' => time(),
                        'update_time' => time()
                    ];

                    $file_id = (new Stores)->insertGetId($data);

                    $share_id = Shares::addShare($this->userInfo['id'],$file_id,0);

                    Stores::where('id',$file_id)->update(['shares_id' => $share_id]);

                    Cache::rm($chunks_save_name);

                    return json(['code' => 1,'msg' => '文件上传成功']);

                }

                return json(['code' =>1,'chunks' => $chunks_filename.'.chunk']);

            }catch (\Throwable $e){
                Cache::rm($chunks_save_name);
                return json(['code' => 0,'msg' => $e->getMessage(),'trace' => $e->getTraceAsString()]);
            }
        }

        // 文件上传
        $info = $files->validate($file_validate)->move($paths['path'],$paths['name']);
        // 保存结果
        if($info){
            // 加入数据库
            $data = [
                'uid' => $this->userInfo['id'],
                'origin_name' => $info->getInfo('name'),
                'file_name' => $paths['file'] . $info->getFilename(),
                'size' => $info->getInfo('size'),
                'meta' => '',
                'mime_type' => $info->getMime(),
                'ext' => $info->getExtension(),
                'parent_folder' => $folder_id,
                'policy_id' => $policy['id'],
                'dir' => '',
                'create_time' => time(),
                'update_time' => time()
            ];

            $file_id = (new Stores)->insertGetId($data);

            $share_id = Shares::addShare($this->userInfo['id'],$file_id,0);

            Stores::where('id',$file_id)->update(['shares_id' => $share_id]);

            return json(['code' => 1,'msg' => '文件上传成功']);

        }else{
            return json(['code' => 0,'msg' => $files->getError()]);
        }

    }

    protected function getChunkFile(){
        $file = request()->file('file');
        $size = $file->getSize();

        if($size > (2 * 1024 * 1024)){
            throw new Exception('分片错误');
        }
        $chunk_fh = fopen($file->getRealPath(),'r');
        $chunk_data = fread($chunk_fh,$size);
        fclose($chunk_fh);
        return $chunk_data;
    }

    protected function getRandomKey($length = 16){
        $charTable = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = "";
        for ( $i = 0; $i < $length; $i++ ){
            $result .= $charTable[ mt_rand(0, strlen($charTable) - 1) ];
        }
        return $result;
    }

    protected function getUploadPaths($policy): array
    {
        // 目录分割
        $ds = DIRECTORY_SEPARATOR;

        // 配置目录
        $save_dir = str_replace('/',$ds,$policy['config']['save_dir']);

        // 根目录
        $root_path = realpath(env('root_path') . './public') . $save_dir;

        // 当前保存目录
        $file_path = date('Ymd') . $ds . $this->userInfo['id'] . $ds;

        //文件名
        $file_name = uniqid( "file_") . time();

        return [
            'root' => $root_path,
            'file' => $file_path,
            'path' => $root_path . $file_path,
            'name' => $file_name
        ];

    }

    protected function getFileExt($filename){
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

}