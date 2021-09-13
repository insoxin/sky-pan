<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\Profit;
use app\common\model\Record;
use app\common\model\Withdraw;

class Share extends Home
{

    public function index(){
        $time = input('get.time');
        $query = [];
        if(!empty($time)){
            $query = ['time' => $time];
        }else{
            $time = date('Y-m-d');
        }

        $list = Profit::where('uid',$this->userInfo['id'])
            ->whereBetweenTime('create_time',$time)
            ->order([
                'count_money' => 'desc',
                'id' => 'desc'
            ])
            ->paginate(15,false,[
                'query' => $query
            ]);

        $this->assign('time',$time);
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function record(){

        $list = Record::where('uid',$this->userInfo['id'])->order('create_time desc')->paginate(15);

        $this->assign('list',$list);

        return $this->fetch();
    }

    public function withdraw(){

        $list = Withdraw::where('uid',$this->userInfo['id'])->order('create_time desc')->paginate(15);
        $money = Withdraw::where('uid',$this->userInfo['id'])->where('status',1)->sum('money');

        $this->assign('money',$money);
        $this->assign('list',$list);
        $this->assign('amount',$this->userInfo['amount']);
        return $this->fetch();
    }

    public function echarts(){
        $id = input('get.id');

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
        $profit = Profit::where('file_id',$id)
            ->where('uid',$this->userInfo['id'])
            ->whereTime('create_time','>=',$start_time)
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

        $this->assign('profit',$profit);

        $this->assign('series_reg',$series_reg);
        $this->assign('series_order',$series_order);
        $this->assign('series_money',$series_money);
        $this->assign('time_type',$time_type);
        return $this->fetch();
    }


}