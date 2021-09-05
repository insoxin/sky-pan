<?php

namespace app\common\controller;

use think\Controller;

class AdminController extends Controller
{

    protected $middleware = ['AdminLoginCheck'];

}