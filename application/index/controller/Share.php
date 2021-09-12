<?php

namespace app\index\controller;

use app\common\controller\Home;
use app\common\model\Withdraw;

class Share extends Home
{

    public function index(){
        return $this->fetch();
    }

    public function record(){

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

}