<?php

namespace app\http\middleware;

use app\common\model\Users;

class UserLoginCheck
{
    protected $except = [
        'User/login',
        'Index/index'
    ];

    public function handle($request, \Closure $next){

        $request_uri = $request->controller() . '/' . $request->action();

        if(!in_array($request_uri,$this->except) && !(new Users)->login_auth('default')){
            return redirect('user/login');
        }

        return $next($request);
    }

}