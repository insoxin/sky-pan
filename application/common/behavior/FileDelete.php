<?php

namespace app\common\behavior;

use app\common\model\Shares;
use app\common\model\Stores;
use GuzzleHttp\Client;

class FileDelete
{

    public function run($params){

        // 删除文件
        if(isset($params['file'])){
            // 删除分享记录
            Shares::where('source_id','in',$params['file'])->delete();
            // 获取文件列表
            $files = Stores::onlyTrashed()
                ->with(['policy' => function($query){
                    $query->field('id,type,config');
                }])
                ->where('id','in',$params['file'])
                ->select();

            foreach ($files as $item){
                switch ($item->policy['type']){
                    case 'local':
                        $this->localDelete($item['file_name']);
                        break;
                    case 'remote':
                        $this->remoteDelete($item['file_name'],$item->policy->config);
                        break;
                }
            }

        }

        if(isset($params['folder'])){
            // 删除分享记录
            Shares::where('source_id','in',$params['folder'])->delete();
        }

        return true;
    }


    protected function localDelete($file_path){
        // 获取真实文件地址
        $real_path = realpath(env('root_path') . './public') . $file_path;
        // 修复文件路径
        $real_path = str_replace(['\\','/','//','\\\\'],DIRECTORY_SEPARATOR,$real_path);

        // 文件是否存在
        if(is_file($real_path)){
            // 删除文件
            @unlink($real_path);
        }

    }

    protected function remoteDelete($file_path,$config){
        $remote_uri = $this->getRemoteSignUrl($file_path,$config);
        try {
            $client = new Client([
                'verify' => false
            ]);

            $client->get($remote_uri);

        }catch (\Throwable $e){

        }
    }

    protected function getRemoteSignUrl($path,$config): string
    {
        $post = [
            'md' => 'delete',
            'path' => $path
        ];

        $post['sign'] = $this->getRemoteSign($post,$config['access_token']);

        return $config['server_uri'] .'?' . urldecode(http_build_query($post));
    }

    protected function getRemoteSign($params,$key): string
    {
        // 过滤参数
        $params = array_filter($params,function($key) use ($params){
            if(empty($params[$key]) || $key == 'sign'){
                return false;
            }
            return true;
        },ARRAY_FILTER_USE_KEY);
        // ascii排序
        ksort($params);
        reset($params);
        // 签名
        return md5(urldecode(http_build_query($params)) . $key);
    }

}