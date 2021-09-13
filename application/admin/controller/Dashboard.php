<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Profit;
use app\common\model\Stores;
use app\common\model\Users;

class Dashboard extends Admin
{

    public function index(){
        // 获取当月所有时间
        $month_time = [];
        $time_type = [];

        for ($i = 0;$i < 30;$i++){
            $month_time[] = strtotime('-'.$i.' day');
            $time_type[] = "'".date('m-d',strtotime('-'.$i.' day'))."'";
        }

        $time_type = array_reverse($time_type);
        $time_type = implode(',',$time_type);

        // 获取当月间隔时间
        $start_time = strtotime(date('Y-m-d',strtotime('-29 day')));
        $end_time = strtotime('now');

        // 获取30天的统计记录
        $profit = Profit::whereTime('create_time','>=',$start_time)
            ->whereTime('create_time','<=',$end_time)
            ->order('create_time desc')
            ->select();


        // 图表统计
        $series_reg = str_split(str_repeat('0',30),1);
        $series_order = str_split(str_repeat('0',30),1);
        $series_money = str_split(str_repeat('0',30),1);

        foreach ($month_time as $key => $item){
            $item_time = date('Y-m-d',$item);
            foreach ($profit as $profit_item){
                $profit_time = date('Y-m-d',$profit_item['create_time']);
                if($item_time == $profit_time){
                    $series_reg[$key] = $profit_item['count_reg'];
                    $series_order[$key] = $profit_item['count_order_yes'];
                    $series_money[$key] = $profit_item['count_money'];
                }
            }
        }

        $series_reg = array_reverse($series_reg);
        $series_reg = implode(',',$series_reg);

        $series_order = array_reverse($series_order);
        $series_order = implode(',',$series_order);

        $series_money = array_reverse($series_money);
        $series_money = implode(',',$series_money);

        // 用户数量统计

        $default_group = config('register.default_group');
        $vip_group = config('vip.vip_group');

        $default_user = Users::where('group',$default_group)->count();
        $vip_user = Users::where('group',$vip_group)->count();

        $file = Stores::count();
        $size = Stores::sum('size');

        $this->assign('count',[
            'user' => $default_user,
            'vip' => $vip_user,
            'file' => $file,
            'size' => countSize($size)
        ]);

        $this->assign('profit',$profit);

        $this->assign('series_reg',$series_reg);
        $this->assign('series_order',$series_order);
        $this->assign('series_money',$series_money);
        $this->assign('time_type',$time_type);

        return $this->fetch();
    }

}