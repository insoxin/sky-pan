<?php

namespace app\http\middleware;

use app\common\model\Users;

class UserLoginCheck
{
    protected $except = [
        'User/login',
        'User/register',
        'User/forget',
        'User/reset',
        'Index/index',
        'Index/share',
        'Index/report',
        'Index/share_pass',
        'Index/qrcode'
    ];

    public function handle($request, \Closure $next){

        $request_uri = $request->controller() . '/' . $request->action();

        if(!in_array($request_uri,$this->except) && !(new Users)->login_auth('default')){
            (new Users)->logout('default');
            return redirect('user/login');
        }

        return $next($request);
    }

}