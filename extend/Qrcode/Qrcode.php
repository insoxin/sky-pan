<?php

namespace Qrcode;

class Qrcode
{

    public static function createQrcode($url){
        \PHPQRCode\QRcode::png($url,false,"H",10,1);
    }

}