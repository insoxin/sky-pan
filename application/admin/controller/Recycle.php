<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Policys;
use app\common\model\Profit;
use app\common\model\Stores;
use app\common\model\Users;

class Recycle extends Admin
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
                $map[] = ['delete_time','between time',[$start_time,$end_time]];
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
            $sort_key = input('get.sort_key','delete_time');
            $sort = input('get.sort',0);

            $order_type = empty($sort) ? 'desc' : 'asc';

            $list = Stores::onlyTrashed()->where($map)->page($page,$limit)->order($sort_key,$order_type)->select()->each(function($item){
                $item['icon'] = getFileIcon($item['ext'],'admin');
                $item['size'] = countSize($item['size']);
                $item['uid'] = Users::where('id',$item['uid'])->value('nickname');
                $item['policy_id'] = Policys::where('id',$item['policy_id'])->value('name');
                return $item;
            });

            $count = Stores::onlyTrashed()->where($map)->count();

            return json([
                'code' => 0,
                'msg' => '加载成功',
                'count' => $count,
                'data' => $list->toArray()
            ]);
        }
        $policy = Policys::field('id,name')->select();
        $this->assign('policy',$policy);
        return $this->fetch();
    }

    public function restore(){
        $ids = input('get.ids');
        $store = Stores::onlyTrashed()->where('id','in',$ids)->select();
        if(empty($store)){
            $this->returnError('数据不存在');
        }

        $succ = 0;

        foreach ($store as $item){
            $item->restore();
            $succ++;
        }

        $this->returnSuccess('处理完成，共恢复'.$succ.'个文件');
    }

    public function delete(){
        $ids = input('get.ids');
        $store = Stores::onlyTrashed()->where('id','in',$ids)->select();
        if(empty($store)){
            $this->returnError('数据不存在');
        }

        $succ = 0;

        foreach ($store as $item){
            $item->delete(true);

            // 删除分享信息
            Profit::where('file_id',$item['id'])
                ->where('uid',$item['uid'])
                ->delete();

            $succ++;
        }

        $this->returnSuccess('成功删除'.$succ.'个文件');
    }

}