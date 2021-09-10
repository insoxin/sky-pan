<?php
namespace app\index\controller;

use app\common\controller\Home;

class Index extends Home
{
    public function index()
    {
        return $this->fetch();
    }

    public function share(){
        return $this->fetch();
    }
}
