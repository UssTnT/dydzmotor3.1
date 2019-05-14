<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Request;

use app\admin\model\Contact;
use app\admin\model\Msg;

class ContactController extends BaseController
{
  /*显示联系资源列表*/
  public function index()
  {
    $model = new Contact;
    $mmodel = new Msg;
    $rowset = $model->select();
    $this->assign('rowset',$rowset);
    $mrowset = $mmodel->select();
    $this->assign('mrowset',$mrowset);
    return $this->fetch();
  }

  /*显示留言资源列表*/
  public function view($id)
  {
    $model = new Msg;
    $row = $model::get($id);
    $this->assign('row',$row);
    return $this->fetch();
  }

  /*显示修改资源表单页*/
  public function edit($id) {
    $model = new Contact;
    $row = $model::get($id);
    $this->assign('row',$row);
    return $this->fetch();
  }
  
  /*更新资源*/
  public function update(Request $request, $id) {
    $model = new Contact;
    $data = $request->post();
    $int = $model->where('id', $id)->update($data);
    if ($int) {
      return $this->success('success', url('/admin/contact'));
    } else {
      return $this->error('error', url('/admin/contact/edit/id/' . $id));
    }
  }

  /*删除资源*/
  public function delete($id){
    $model = new Msg;
    $int = $model->where('id', $id)->delete();
    if($int) {
      return ['type'=>'1','url'=>'/admin/contact','msg'=>'The msg content with id ' . $id. ' has been removed.'];
    } else {
      return $this->error('error', url('/admin/contact'));
    }
  }

}
