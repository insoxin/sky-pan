<?php

namespace app\common\model\driver;

use think\Exception;
use think\facade\Cache;

class Local extends PolicyStore
{

    public function uploadSimple()
    {
        // 保存文件
        $file_info = $this->info['file']['data']->move($this->path['path'],$this->path['name']);

        if(!$file_info){
            throw new Exception($this->info['file']['data']->getError());
        }

        $policy_save_dir = $this->policy['config']['save_dir'];

        return getDiyDirSeparator('/',$policy_save_dir. $this->path['file'].$this->path['filename']);
    }

    public function uploadPart()
    {
        // 分片数据存储名称
        $chunks_saveKey = 'chunks_'.$this->info['uid'].'_'.$this->info['chunk']['key'];

        // 文件名称
        $temp_filename = 'chunks_'.md5($this->info['chunk']['key']).'_'.$this->getRandomKey(4) . '_part_'.$this->info['chunk']['chunk'].'.chunk';
        // 临时文件地址
        $temp_file = $this->path['temp_path'] . $temp_filename;

        // 保存临时文件
        $file_info = $this->info['file']['data']->move($this->path['temp_path'],$temp_filename);

        if(!$file_info){
            throw new Exception('分片创建错误：'.$this->info['file']['data']->getError());
        }

        // 分片存储列表
        $chunk_list = Cache::get($chunks_saveKey) ?? [];

        // 加入分片文件
        $chunk_list[] = $temp_file;

        // 保存分片临时存储列表
        Cache::set($chunks_saveKey,$chunk_list);

        // 分片上传成功
        if($this->info['chunk']['chunk'] == ($this->info['chunk']['chunks'] - 1)){

            $save_file = $this->path['path'] . $this->path['filename'];

            // 融合文件路径
            $fileObj = fopen($save_file,"a+");

            // 融合文件
            foreach ($chunk_list as $value) {
                $chunkObj = fopen($value, "rb");

                if(!($fileObj && $chunkObj)){
                    throw new Exception('分片融合文件创建失败');
                }

                $content = fread($chunkObj, (2 * 1024 * 1024));
                fwrite($fileObj, $content, (2 * 1024 * 1024));
                unset($content);
                fclose($chunkObj);
                // 删除分片文件
                unlink($value);
            }

            // 清空分片临时存储列表
            Cache::rm($chunks_saveKey);

            $policy_save_dir = $this->policy['config']['save_dir'];

            return getDiyDirSeparator('/',$policy_save_dir. $this->path['file'] . $this->path['filename']);
        }

        return 'chunk_file';

    }

    protected function getRandomKey($length = 16){
        $charTable = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = "";
        for ( $i = 0; $i < $length; $i++ ){
            $result .= $charTable[ mt_rand(0, strlen($charTable) - 1) ];
        }
        return $result;
    }

    public function download($stores,$speed,$policy)
    {
        //存储文件地址
        $_file = getSafeDirSeparator(env('root_path').'public'.$stores['file_name']);

        // 文件不存在
        if(!is_file($_file)){
            throw new Exception('文件不存在，可能已被删除');
        }

        $_file_path = $stores['file_name'];

        $_file_limit_size = round(intval($speed) * 1024);

        // 启用 nginx X-Accel 下载
        header('Content-Type: application/octet-stream');
        $encoded_fname = rawurlencode($stores['origin_name']);
        header('Content-Disposition: attachment;filename="'.$encoded_fname.'";filename*=utf-8'."''".$encoded_fname);

        header('X-Accel-Redirect: '. $_file_path);
        header('X-Accel-Buffering: yes');

        // 不限速下载
        if($speed !== ""){
            header('X-Accel-Limit-Rate:'.$_file_limit_size);
        }

    }

}