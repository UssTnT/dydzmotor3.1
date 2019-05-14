<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Request;

use app\admin\model\Job;

class JobController extends BaseController
{
  /*显示关于资源列表*/
  public function index()
  {
    $model = new Job;
    $row = $model->where('id',1)->find();
    $this->assign('row',$row);
    return $this->fetch();
  }
  
  /*显示编辑关于资源列表*/
  public function edit($id)
  {
    $model = new Job;
    $row = $model::get($id);
    $this->assign('row',$row);
    return $this->fetch();
  }

  /*更新资源*/
  public function update(Request $request, $id) {
    $model = new Job;
    $data = $request->post();
    $data['jTime'] = date('Y-m-d H:i:s',time());
    $int = $model->where('id', $id)->update($data);
    if ($int) {
      return $this->success('success', url('/admin/job'));
    } else {
      return $this->error('error', url('/admin/job/edit/id/' . $id));
    }
  }

}
