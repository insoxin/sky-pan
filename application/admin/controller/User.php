<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Users;
use think\Exception;

class User extends Admin
{

    public function index(){
        if($this->request->isAjax()){
            $page = input('get.page',1);
            $limit = input('get.limit',10);
            $phone = input('get.phone','');

            $map = [];

            if(!empty($phone)){
                $map[] = ['phone','like','%'.$phone.'%'];
            }

            $list = User::where($map)->page($page,$limit)->field('id,phone,grade,is_auth,amount,email,status,create_time')->select();

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

    public function del(){
        $del_list = input('get.ids');
        //删除用户
        User::destroy($del_list);
        /**
         * 待完成功能：删除用户后删除相关数据，如：上传储存的数据，订单数据，目录数据，分享数据等
         */
        return json(['code' => 200,'msg' => '删除成功']);
    }

    public function edit(){
        $id = input('get.id');
        $info = User::get($id);

        if(empty($info)){
            $this->error('用户数据不存在');
        }

        if($this->request->isPost()){
            $data = input('post.');
            $result = $this->validate($data,[
                'email|安全邮箱' => 'email',
                'password|登录密码' => 'alphaNum|length:6,18',
                'grade|用户等级' => 'require|number',
                'grade_time|到期时间' => 'dateFormat:Y-m-d'
            ]);

            if($result !== true) return json(['code' => 502,'msg' => $result]);

            $update = [];

            if(!empty($data['email'])){
                $update['email'] = $data['email'];
            }

            if(!empty($data['password'])){
                $salt = substr(md5(uniqid()),0,6);
                $en_password = md5($data['password'].$salt);
                $update['salt'] = $salt;
                $update['password'] = $en_password;
            }

            $update['grade'] = $data['grade'];

            if($data['grade'] > 0){
                $grade_time = strtotime($data['grade_time']);
                if($grade_time < time()){
                    unset($update['grade']);
                }else{
                    $update['grade_time'] = $grade_time;
                }
            }

            User::where('id',$id)->update($update);

            return json(['code' => 200,'msg' => '用户信息修改成功']);

        }else{
            $this->assign('info',$info);
            return $this->fetch();
        }
    }

    public function change_status(){
        $id = input('get.id');
        $status = input('get.status');

        $info = User::get($id);

        if($info->isEmpty()){
            return json(['code' => 502,'msg' => '用户数据不存在']);
        }

        User::where('id',$id)->update(['status' => $status]);

        return json(['code' => 200,'msg' => '用户状态切换成功']);
    }

}