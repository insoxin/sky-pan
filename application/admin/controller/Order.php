<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Order as OrderModel;
use app\common\model\Users;

class Order extends Admin
{

    public function index(){
        if($this->request->isAjax()){
            $page = input('get.page',1);
            $limit = input('get.limit',10);

            $search_type = input('get.search_type','');
            $search_value = input('get.search_value','');
            $status = input('get.status','');
            $map = [];

            if(!empty($search_type) && !empty($search_value)){
                $map[] = [$search_type,'like','%'.$search_value.'%'];
            }

            if(!empty($status)){
                $map[] = ['status','=',$status];
            }

            $pay_types = [
                'alipay' => '支付宝',
                'wxpay' => '微信'
            ];

            $list = OrderModel::where($map)
                ->page($page,$limit)
                ->select()->each(function ($item) use($pay_types){
                    $item['uid'] = Users::where('id',$item['uid'])->value('username');
                    $item['type'] = $pay_types[$item['type']] ?? '';
                    $item['money'] = number_format($item['money'],2).'￥';
                    $item['vip_day'] = $item['vip_day'].'天';
                    $item['out_trade_no'] = empty($item['out_trade_no']) ? '-' : $item['out_trade_no'];
                    $item['create_time'] = date('Y-m-d H:i',$item['create_time']);
                    $item['pay_time'] = empty($item['pay_time']) ? '-' : date('Y-m-d H:i',$item['pay_time']);
                    return $item;
                });

            $count = OrderModel::count();

            return json([
                'code' => 0,
                'msg' => '加载成功',
                'count' => $count,
                'data' => $list->toArray()
            ]);
        }
        return $this->fetch();
    }

    public function delete(){
        $ids = input('get.ids');

        OrderModel::where('id','in',$ids)->delete();

        $this->returnSuccess('删除订单成功');
    }

}