<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\facade\Route;

if(ENTRY_MODULE == 'index'){
    Route::any('/','index/index');
    Route::any('/s/:code','index/share');
    Route::any('/file_share/file_report','index/report');
    Route::any('/file_share/pass','index/share_pass');
    Route::any('/file_share/qrcode','index/qrcode');
    Route::any('/download','index/download');
}
