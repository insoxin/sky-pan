<?php

namespace app\common\model\driver;

abstract class PolicyStore
{

    protected $info;

    protected $policy;

    protected $path;


    public function __construct($info,$policy,$path)
    {
        $this->info = $info;
        $this->policy = $policy;
        $this->path = $path;
    }

    public function upload()
    {
        if(!empty($this->info['chunk']['chunks'])){
            return $this->uploadPart();
        }else{
            return $this->uploadSimple();
        }
    }

    public function uploadSimple()
    {
        return 0;
    }

    public function uploadPart()
    {
        return 0;
    }

    public function download($stores,$speed,$policy)
    {
        return 0;
    }

}