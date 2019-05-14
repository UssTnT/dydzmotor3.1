<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Request;
use app\admin\model\Admin;

class AdminController extends BaseController
{
    public function login()
    {
        return $this->fetch('login');
    }

    public function logging(Request $request){
        $captcha = $request->post('captcha');
        if(captcha_check($captcha)){
            $username = $request->post('username');
            $password = md5($request->post('password'));
            $model = new Admin;
            $row = $model->where('username',$username)->where('password',$password)->find();
            if($row){
                session('userid',$row->id);
                session('username',$row->username);                      
                session('permission',$row->permission);
                return ['type'=>'0','msg'=>'/admin/index'];
            }
            return ['type'=>'1','msg'=>'Sorry, you entered the wrong user name or password.'];
        }
        return ['type'=>'2','msg'=>'Sorry, the verification code you entered is wrong.'];
    }
    
    public function logout(){
        /*session('userid',null);
        session('username',null);*/

        // 清除think作用域
        session(null, 'think');

        return $this->login();
    }
    
    public function create(){
        return $this->fetch();   
    }
    
    public function save(){
        $model = new Admin;
        $username = input('username');
        $row = $model->where('username',$username)->find();
        if($row) {
            return $this->error('error: Sorry, the username has been registered, please input again', url('/admin/create'));
        } else {
            $model->username = $username;
            $password = md5(input('password'));
            $model->password = $password;
            $model->name = input('name');
            $model->email = input('email');
            $model->phone = input('phone');
            $model->permission = input('permission');
            $model->save();
            return $this->redirect(url('/admin/info'));
        }
    }
    
    public function basic(){
        $id = session('userid');
        $model = new Admin;
        $admin = $model->where('id',$id)->find();
        $this->assign('row', $admin);

        return $this->fetch('index');
    }

    public function editBasic(){
        $username = input('username');
        $model = new Admin;
        $row = $model->where('username',$username)->find();
        if($row){
            $name = input('name');
            $email = input('email');
            $phone = input('phone');
            $permission = input('permission');
            if($permission) {
                $row = $model->where('username',$username)->update([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'permission' => $permission,
                ]);
                return ['name'=>$name,'email'=>$email,'phone'=>$phone,'permission'=>$permission];
            } else {
                $row = $model->where('username',$username)->update([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                ]);
                return ['name'=>$name,'email'=>$email,'phone'=>$phone];
            }
        }
    }

    public function editPwd(){
        $username = input('username');
        $model = new Admin;
        $row = $model->where('username',$username)->find();
        if($row){
            $oldpwd = md5(input('oldpwd'));      //25d55ad283aa400af464c76d713c07ad d959caadac9b13dcb3e609440135cf54
            if( $oldpwd == $row['password'] ) {
                $newpwd = md5(input('newpwd')); //12345678 1234567812345678
                $model->where('username',$username)->update([
                    'password' => $newpwd
                ]);
                return ['type'=>'1','msg'=>'Save Success !'];
            } else {
                return ['type'=>'0','msg'=>'Sorry, the original password is not correct !'];
            }
        }
    }
    
    public function edit(){
        $username = input('username');
        $model = new Admin;
        $row = $model->where('username',$username)->find();
        if($row){
            $password = md5(input('password'));
            $model->where('username',$username)->update([
                'password' => $password
            ]);
            return ['type'=>'0','msg'=>'success'];
        }
        return ['type'=>'1','msg'=>'The user you modified does not exist.'];
    }
    
    public function delete($id){
        //$data = explode ( ',', $id );
        //Admin::destroy($id);
        $model = new Admin;
        $int = $model->where('id', $id)->delete();
        if($int) {
          return ['type'=>'1','url'=>'/admin/info','msg'=>'The admin content with id ' . $id. ' has been removed.'];
        } else {
          return $this->error('error', url('/admin/info'));
        }
    }
    
    public function view($id){
        $admin = Admin::get($id);
        $this->assign('row', $admin);
        return $this->fetch('index');
    }

    public function info(){
        $model = new Admin;
        $permission = session('permission');
        if( $permission == 1 ) {
            $rowset = $model->where('username', session('username'))->select();
        } else if($permission == 2) {
            $rowset = $model->where('permission', 1)->select();
        } else {
            $rowset = $model->where('permission', 'ELT', 2)->select();
        }
        $this->assign('rowset',$rowset);
        return $this->fetch();
    }
}
