<?php

namespace app\common\model\driver;

class TxyunOss implements PolicyStore
{

    public function upload($info,$policy,$path){

        $accessKeyId = "yourAccessKeyId";
        $accessKeySecret = "yourAccessKeySecret";
        $endpoint = "yourEndpoint";
        $bucket= "examplebucket";
        $object = "exampledir/exampleobject.txt";
        $content = "Hello OSS";

        return 0;
    }

    public function download()
    {
        // TODO: Implement download() method.
    }
}