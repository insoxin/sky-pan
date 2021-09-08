<?php

namespace app\admin\controller;

use app\common\controller\Admin;

class File extends Admin
{

    public function index(){
        return $this->fetch();
    }



}