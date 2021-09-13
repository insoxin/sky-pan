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
        $end_time = strtotime(date('Y-m-d'));


        $profit = Profit::where('file_id',$id)
            ->where('uid',$this->userInfo['id'])
            ->whereTime('create_time','>=',$start_time)
            ->whereTime('create_time','<=',$end_time)
            ->fetchSql(true)
            ->select();


        var_dump($profit);
        exit;
        $this->assign('time_type',$time_type);
        return $this->fetch();
    }
}