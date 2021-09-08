<?php

namespace app\index\controller;

use app\common\controller\Home;

class Upload extends Home
{

    public function index(){
        return $this->fetch();
    }

}