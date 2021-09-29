<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\driver\AliyunOss;
use app\common\model\driver\Local;
use app\common\model\driver\TxyunOss;
use app\common\model\Policys;
use app\common\model\Stores;
use app\common\model\Users;
use think\response\Download;

class File extends Admin
{

    public function index(){
        if($this->request->isAjax()){
            $page = input('get.page',1);
            $limit = input('get.limit',10);
            $map = [];

            // 获取筛选条件
            $start_time = input('get.start_time','');
            $end_time = input('get.end_time','');
            $policy_id = input('get.policy','');
            $ext = input('get.ext','');
            $uid = input('get.uid','');
            $origin_name = input('get.origin_name','');

            if(!empty($start_time) && !empty($end_time)){
                $map[] = ['create_time','between time',[$start_time,$end_time]];
            }

            if(!empty($policy_id)){
                $map[] = ['policy_id','=',$policy_id];
            }

            if(!empty($ext)){
                $map[] = ['ext','=',$ext];
            }

            if(!empty($uid)){
                $map[] = ['uid','=',$uid];
            }

            if(!empty($origin_name)){
                $map[] = ['origin_name','like','%'.$origin_name.'%'];
            }

            //获取排序条件
            $sort_key = input('get.sort_key','create_time');
            $sort = input('get.sort',0);

            $order_type = empty($sort) ? 'desc' : 'asc';

            $list = Stores::where($map)->page($page,$limit)->order($sort_key,$order_type)->select()->each(function($item){
                $item['icon'] = getFileIcon($item['ext'],'admin');
                $item['size'] = countSize($item['size']);
                $item['uid'] = Users::where('id',$item['uid'])->value('nickname');
                $item['policy_id'] = Policys::where('id',$item['policy_id'])->value('name');
                return $item;
            });

            $count = Stores::where($map)->count();

            return json([
                'code' => 0,
                'msg' => '加载成功',
                'count' => $count,
                'data' => $list->toArray()
            ]);
        }else{

            $policy = Policys::field('id,name')->select();
            $this->assign('policy',$policy);
            return $this->fetch();
        }
    }

    public function delete(){
        $ids = input('get.ids');
        $store = Stores::withTrashed()->where('id','in',$ids)->select();
        if(empty($store)){
            $this->returnError('数据不存在');
        }

        $succ = 0;

        foreach ($store as $item){
            $item->delete();
            $succ++;
        }

        $this->returnSuccess('成功删除'.$succ.'个文件');
    }

    public function download(){
        $id = input('get.id',0);
        $info = Stores::get($id);
        if(empty($info)){
            $this->error('数据不存在');
        }

        $policy = Policys::get($info['policy_id']);

        if(empty($policy)){
            $this->error('存储策略不存在');
        }

        // 远程文件下载
        if($policy['type'] == 'remote'){
            $down_url = getDownloadRemote($info['file_name'],$info['origin_name'],$policy->config['server_uri'],"",$policy->config['access_token']);
            $this->redirect($down_url);
        }

        // 存储驱动下载
        switch ($policy['type']){
            case 'aliyunoss':
                $driver = new AliyunOss(0,0,0);
                break;
            case 'txyunoss':
                $driver = new TxyunOss(0,0,0);
                break;
            default:
                $driver = new Local(0,0,0);
                break;
        }

        // 下载
        return $driver->download($info,"",$policy);
    }

    public function info(){
        $id = input('get.id',0);
        $info = Stores::get($id);
        if(empty($info)){
            $this->error('数据不存在');
        }

        $username = Users::where('id',$info['uid'])->value('username');
        $policy_name = Policys::where('id',$info['policy_id'])->value('name');
        $file_path = $info['file_name'];

        $this->assign('username',$username);
        $this->assign('policy_name',$policy_name);
        $this->assign('file_path',$file_path);
        $this->assign('info',$info);
        return $this->fetch();
    }

}