<?php

namespace app\common\model\driver;

class AliyunOss implements PolicyStore
{

    public function upload($info,$policy){

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