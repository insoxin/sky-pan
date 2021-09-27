<?php

namespace app\common\model\driver;

interface PolicyStore
{

    public function upload($info,$policy,$path);

    public function download();

}