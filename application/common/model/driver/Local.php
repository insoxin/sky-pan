<?php

namespace app\common\model\driver;

class Local implements PolicyStore
{

    public function upload($info, $policy,$path){

        return 0;
    }

    public function download()
    {
        // TODO: Implement download() method.
    }
}