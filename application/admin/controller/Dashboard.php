<?php

namespace app\admin\controller;

use think\Controller;

class Dashboard extends Controller
{

    public function index(){
        return $this->fetch();
    }

}