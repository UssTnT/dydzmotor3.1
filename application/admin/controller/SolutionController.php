<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Request;

use app\admin\model\Solution;

class SolutionController extends BaseController
{
  /*显示关于资源列表*/
  public function index()
  {
    $model = new Solution;
    $row = $model->where('id',1)->find();
    $this->assign('row',$row);
    return $this->fetch();
  }
  
  /*显示编辑关于资源列表*/
  public function edit($id)
  {
    $model = new Solution;
    $row = $model::get($id);
    $this->assign('row',$row);
    return $this->fetch();
  }

  /*更新资源*/
  public function update(Request $request, $id) {
    $model = new Solution;
    $data = $request->post();
    $data['sTime'] = date('Y-m-d H:i:s',time());
    $int = $model->where('id', $id)->update($data);
    if ($int) {
      return $this->success('success', url('/admin/solution'));
    } else {
      return $this->error('error', url('/admin/solution/edit/id/' . $id));
    }
  }

}
