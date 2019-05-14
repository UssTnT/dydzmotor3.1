<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Request;

use app\admin\model\About;

class AboutController extends BaseController
{
  /*显示关于资源列表*/
  public function index()
  {
    $model = new About;
    $row = $model->where('id',1)->find();
    $this->assign('row',$row);
    return $this->fetch();
  }
  
  /*显示编辑关于资源列表*/
  public function edit($id)
  {
    $model = new About;
    $row = $model::get($id);
    $this->assign('row',$row);
    return $this->fetch();
  }

  /*更新资源*/
  public function update(Request $request, $id) {
    $model = new About;
    $data = $request->post();
    $data['aTime'] = date('Y-m-d H:i:s',time());
    $int = $model->where('id', $id)->update($data);
    if ($int) {
      return $this->success('success', url('/admin/about'));
    } else {
      return $this->error('error', url('/admin/about/edit/id/' . $id));
    }
  }

}
