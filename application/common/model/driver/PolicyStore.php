<?php

namespace app\common\model\driver;

interface PolicyStore
{

    public function upload($info,$policy);

    public function download();

}