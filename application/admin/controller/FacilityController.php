<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Request;

use app\admin\model\Facility;

class FacilityController extends BaseController
{
  /*显示资源表格页*/
  public function index()
  {
    $model = new Facility;
    $rowset = $model->select();
    $this->assign('rowset',$rowset);
    return $this->fetch();
  }

  /*显示创建资源表单页*/
  public function create() {
    return $this->fetch();
  }

  /*添加资源*/
  public function save(Request $request) {
    $model = new Facility;
    $data = $request->post();
    //dump($data);
    if($request->has('fImg','file')) {
      $file = $request->file('fImg');
      if($file){
        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])->move (ROOT_PATH . 'public' . DS . 'uploads/img');
        if($info) {
          $data['fImg'] = str_replace('\\','/',$info->getSaveName());
        } else{
          return $this->error('error:' . $file->getError(), url('/admin/facility/create'));
        }
      }
    } else {
      $data['fImg'] = 'Noupload.jpg';
    }
    $data['fTime'] = date('Y-m-d H:i:s',time());
    $int = $model->insert($data);
    if ($int) {
      return $this->success('success', url('/admin/facility'));
    } else {
      return $this->error('error', url('/admin/facility/create'));
    }
  }

  /*显示修改资源表单页*/
  public function edit($id) {
    $model = new Facility;
    $row = $model::get($id);
    $this->assign('row',$row);
    return $this->fetch();
  }
  
  /*更新资源*/
  public function update(Request $request, $id) {
    $model = new Facility;
    $data = $request->post();
    //dump($data);
    if($request->has('fImg','file')) {
      $file = $request->file('fImg');
      if($file){
        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])->move (ROOT_PATH . 'public' . DS . 'uploads/img');
        if($info) {
          $data['fImg'] = str_replace('\\','/',$info->getSaveName());
        } else{
          return $this->error('error:' . $file->getError(), url('/admin/product/edit/id/' . $id));
        }
      }
    } else {
      $srcImg = $model->where('id', $id)->value('fImg');
      if(!strcmp('Noupload.jpg',$srcImg)) {
        $data['fImg'] = 'Noupload.jpg';
      } else {
        $data['fImg'] = $srcImg;
      }
    }
    $data['fTime'] = date('Y-m-d H:i:s',time());

    $int = $model->where('id', $id)->update($data);
    if ($int) {
      return $this->success('success', url('/admin/facility'));
    } else {
      return $this->error('error', url('/admin/facility/edit/id/' . $id));
    }
  }

  /*删除资源*/
  public function delete($id){
    $model = new Facility;
    $int = $model->where('id', $id)->delete();
    if($int) {
      return ['type'=>'1','url'=>'/admin/facility','msg'=>'The facility content with id ' . $id. ' has been removed.'];
    } else {
      return $this->error('error', url('/admin/facility'));
    }
  }

}
