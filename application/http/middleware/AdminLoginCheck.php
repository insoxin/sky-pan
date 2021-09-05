<?php

namespace app\http\middleware;

use app\common\model\Admin;

class AdminLoginCheck
{

    protected $except = [
        'Auth/login',
        'Auth/verify'
    ];

    public function handle($request, \Closure $next){

        $request_uri = $request->controller() . '/' . $request->action();

        if(!in_array($request_uri,$this->except) && !(new Admin)->auth_login()){
            return redirect('auth/login');
        }

        return $next($request);
    }


}