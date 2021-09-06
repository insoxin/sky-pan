<?php

namespace app\admin\controller;

use app\common\controller\Admin;

class Dashboard extends Admin
{

    public function index(){
        return $this->fetch();
    }

}