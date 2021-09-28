<?php

namespace app\common\model\driver;

use think\Exception;
use think\facade\Cache;

class Local extends PolicyStore
{

    public function uploadSimple()
    {
        $file_path = $this->path['path'] . $this->path['name'];
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
        $file_info = $this->info['file']['data']->move($this->path['temp_path'],$this->path['filename']);

        if(!$file_info){
            throw new Exception('分片创建错误：'.$this->info['file']['data']->getError());
        }

        // 分片存储列表
        $chunk_list = Cache::get($chunks_saveKey) ?? [];

        // 加入分片文件
        $chunk_list[] = $temp_file;

        

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

    public function download()
    {
        // TODO: Implement download() method.
    }

}