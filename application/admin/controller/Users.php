<?php

namespace app\admin\controller;

use app\common\controller\AdminController;
use app\common\model\User;
use think\Exception;

class Users extends AdminController
{

    public function index(){
        if($this->request->isAjax()){
            $page = input('get.page',1);
            $limit = input('get.limit',10);

            $list = User::page($page,$limit)->select();

            $count = User::count();

            return json([
                'code' => 0,
                'msg' => '加载成功',
                'count' => $count,
                'data' => $list->toArray()
            ]);
        }else{
            return $this->fetch();
        }
    }

    public function add(){
        if($this->request->isPost()){
            $data = input('post.');
            $result = $this->validate($data,[
               'phone|手机号码' => 'require|mobile',
                'password|登录密码' => 'require|alphaNum|length:6,18',
                'repassword|重复密码' => 'require|confirm:password',
                'email|安全邮箱' => 'require|email'
            ],[
                'phone.mobile' => '请输入合法的手机号码'
            ]);

            if($result !== true) return json(['code' => 502,'msg' => $result]);

            try {
                (new User)->register($data['phone'],$data['email'],$data['password']);
                return json(['code' => 200,'msg' => '账号新增成功']);
            }catch (Exception $e){
                return json(['code' => 503,'msg' => $e->getMessage()]);
            }
        }else{
            return $this->fetch();
        }
    }

}