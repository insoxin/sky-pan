<?php

namespace app\admin\controller;

use app\common\controller\AdminController;

class Dashboard extends AdminController
{

    public function index(){
        return $this->fetch();
    }

}