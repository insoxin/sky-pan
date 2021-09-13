<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Record;
use app\common\model\Users;
use app\common\model\Withdraw as WithdrawModel;

class Withdraw extends Admin
{

    public function index(){
        if($this->request->isAjax()){
            $page = input('get.page',1);
            $limit = input('get.limit',20);
            $list = WithdrawModel::order('create_time desc')
                ->page($page,$limit)
                ->field('id,uid,money,alipay_account,alipay_name,create_time,status')
                ->select()->each(function($item){
                    $item['uid'] = Users::where('id',$item['uid'])->value('username');
                    $item['create_time'] = date('Y-m-d H:i',$item['create_time']);
                    return $item;
                });

            $count = WithdrawModel::count();

            return json([
                'code' => 0,
                'msg' => '获取成功',
                'data' => $list->toArray(),
                'count' => $count
            ]);
        }
        return $this->fetch();
    }

    public function delete(){
        $id = input('get.id');

        WithdrawModel::where('id',$id)->delete();

        return json(['code' => 200,'msg' => '删除成功']);
    }

    public function change(){
        $id = input('get.id');
        $status = input('get.status');

        $info = WithdrawModel::where('id',$id)->find();

        if(empty($info)){
            return json(['code' => 502,'msg' => '数据记录不存在']);
        }

        $info['status'] = $status;

        $info->save();

        if($status == 2){
            // 退回金额
            Record::addRecord($info['uid'],0,'提现退回',$info['money'],'提现系统退回金额');
        }

        return json(['code' => 200,'msg' => '操作成功']);
    }

}