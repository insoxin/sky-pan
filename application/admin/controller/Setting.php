<?php

namespace app\admin\controller;

use app\common\controller\Admin;

class Setting extends Admin
{

    public function basic(){
        return $this->fetch();
    }

}