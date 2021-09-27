<?php

namespace app\common\model\driver;

class TxyunOss
{

    public static function upload($info,$policy){

        $accessKeyId = "yourAccessKeyId";
        $accessKeySecret = "yourAccessKeySecret";
        $endpoint = "yourEndpoint";
        $bucket= "examplebucket";
        $object = "exampledir/exampleobject.txt";
        $content = "Hello OSS";

        return 0;
    }

}