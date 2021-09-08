<?php

namespace app\admin\controller;

use app\common\controller\Admin;
use app\common\model\Groups;
use app\common\model\Users;
use think\Exception;

class User extends Admin
{

    public function index(){
        if($this->request->isAjax()){
            $page = input('get.page',1);
            $limit = input('get.limit',10);
            $search_type = input('get.search_type','');
            $search_value = input('get.search_value','');
            $map = [];

            if(!empty($search_type) && !empty($search_value)){
                $map[] = [$search_type,'like','%'.$search_value.'%'];
            }

            $groups = Groups::column('group_name','id');
            $default_group = config('register.default_group');

            $list = Users::where($map)
                ->page($page,$limit)
                ->field('id,nickname,username,group,is_auth,amount,email,status,create_time')
                ->select()->each(function ($item) use ($groups,$default_group){
                    $group_name = $groups[$item['group']] ?? '未知';

                    if($item['group'] == 1){
                        $item['group'] = '<span class="layui-badge layui-bg-blue">'. $group_name .'</span>';
                    }elseif ($item['group'] == $default_group){
                        $item['group'] = '<span class="layui-badge layui-bg-gray">'. $group_name .'</span>';
                    }else{
                        $item['group'] = '<span class="layui-badge layui-bg-red">'. $group_name .'</span>';
                    }

                    return $item;
                });

            $count = Users::count();

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

        $default_group = config('register.default_group');

        if($this->request->isPost()){
            $data = input('post.');
            $result = $this->validate($data,[
               'username|用户帐号' => 'require|alphaNum|length:6,26',
                'group|用户组' => 'require|number',
                'password|登录密码' => 'require|alphaNum|length:6,18',
                'repassword|重复密码' => 'require|confirm:password',
                'email|安全邮箱' => 'require|email'
            ]);

            if($result !== true) return json(['code' => 502,'msg' => $result]);

            if(empty($data['group'])){
                $data['group'] = $default_group;
            }

            try {
                (new Users)->register($data['username'],$data['email'],$data['password'],$data['group']);
                return json(['code' => 200,'msg' => '账号新增成功']);
            }catch (Exception $e){
                return json(['code' => 503,'msg' => $e->getMessage()]);
            }
        }else{
            $group = Groups::field('id,group_name')->select()->toArray();
            $this->assign('default_group',$default_group);
            $this->assign('group',$group);
            return $this->fetch();
        }
    }

    public function del(){
        $ids = input('get.ids');

        if(count(explode(',',$ids)) > 20){
            $this->returnError('删除失败，不能一次删除20条以上的数据');
        }

        $delete_users = Users::where('id','in',$ids)->field('id,group')->select()->toArray();

        foreach ($delete_users as $item){
            if($item['id'] == 1){
                $this->returnError('删除失败，删除帐号中包含总管理员帐号');
                break;
            }

            /**
             * 待完成功能：删除用户后删除相关数据，如：上传储存的数据，订单数据，目录数据，分享数据等
             */

            Users::where('id',$item['id'])->delete();
        }

        return json(['code' => 200,'msg' => '删除成功']);
    }


    public function edit(){
        $id = input('get.id');
        $info = Users::get($id);

        if(empty($info)){
            $this->error('用户数据不存在');
        }

        if($this->request->isPost()){
            $data = input('post.');
            $result = $this->validate($data,[
                'email|安全邮箱' => 'email',
                'password|登录密码' => 'alphaNum|length:6,18',
                'group|用户组' => 'require|number'
            ]);

            if($result !== true) return json(['code' => 502,'msg' => $result]);

            $update = [];

            if(!empty($data['email'])){
                $update['email'] = $data['email'];
            }

            if(!empty($data['password'])){
                $en_password = md5($data['password'] . config('app.pass_salt'));
                $update['password'] = $en_password;
            }

            if($info['group'] == 1 && $info['id'] != $this->adminInfo['id'] && !empty($data['password']) && $this->adminInfo['id'] != 1){
                $this->returnError('操作失败，您不能更改其他管理员组用户的密码');
            }

            if($info['id'] == $this->adminInfo['id'] && $data['group'] != $info['group']){
                $this->returnError('操作失败，您不能更改自己的用户组');
            }

            if($info['id'] == 1 && $data['group'] != $info['group']){
                $this->returnError('操作失败，您不能更改总管理员的用户组');
            }

            $update['group'] = $data['group'];

            Users::where('id',$id)->update($update);

            return json(['code' => 200,'msg' => '用户信息修改成功']);

        }else{
            $group = Groups::field('id,group_name')->select()->toArray();
            $this->assign('group',$group);
            $this->assign('info',$info);
            return $this->fetch();
        }
    }


    public function change_status(){
        $id = input('get.id');
        $status = input('get.status');

        $info = Users::get($id);

        if($info->isEmpty()){
            $this->returnError('用户数据不存在');
        }

        if($info['id'] == 1){
            $this->returnError('操作失败，您不能封禁总管理员的帐号');
        }

        if($info['id'] == $this->adminInfo['id']){
            $this->returnError('操作失败，您不能封禁自己的帐号');
        }

        Users::where('id',$id)->update(['status' => $status]);

        return json(['code' => 200,'msg' => '用户状态切换成功']);
    }

}