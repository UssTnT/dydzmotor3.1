<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Request;

use app\admin\model\News;

class NewsController extends BaseController
{
  /*显示资源表格页*/
  public function index()
  {
    $model = new News;
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
    $model = new News;
    $data = $request->post();
    //dump($data);
    if($request->has('nImg','file')) {
      $file = $request->file('nImg');
      if($file){
        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])->move (ROOT_PATH . 'public' . DS . 'uploads/img');
        if($info) {
          $data['nImg'] = str_replace('\\','/',$info->getSaveName());
        } else{
          return $this->error('error:' . $file->getError(), url('/admin/news/create'));
        }
      }
    } else {
      $data['nImg'] = 'Noupload.jpg';
    }
    $data['nTime'] = date('Y-m-d H:i:s',time());
    $int = $model->insert($data);
    if ($int) {
      return $this->success('success', url('/admin/news'));
    } else {
      return $this->error('error', url('/admin/news/create'));
    }
  }

  /*显示修改资源表单页*/
  public function edit($id) {
    $model = new News;
    $row = $model::get($id);
    $this->assign('row',$row);
    return $this->fetch();
  }
	
  /*更新资源*/
  public function update(Request $request, $id) {
    $model = new News;
    $data = $request->post();
    //dump($data);
    if($request->has('nImg','file')) {
      $file = $request->file('nImg');
      if($file){
        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])->move (ROOT_PATH . 'public' . DS . 'uploads/img');
        if($info) {
          $data['nImg'] = str_replace('\\','/',$info->getSaveName());
        } else{
          return $this->error('error:' . $file->getError(), url('/admin/product/edit/id/' . $id));
        }
      }
    } else {
      $srcImg = $model->where('id', $id)->value('nImg');
      if(!strcmp('Noupload.jpg',$srcImg)) {
        $data['nImg'] = 'Noupload.jpg';
      } else {
        $data['nImg'] = $srcImg;
      }
    }
    //$data['nTime'] = date('Y-m-d H:i:s',time());

    $int = $model->where('id', $id)->update($data);
    if ($int) {
      return $this->success('success', url('/admin/news'));
    } else {
      return $this->error('error', url('/admin/news/edit/id/' . $id));
    }
  }

  /*删除资源*/
  public function delete($id){
    $model = new News;
    $int = $model->where('id', $id)->delete();
    if($int) {
      return ['type'=>'1','url'=>'/admin/news','msg'=>'The news content with id ' . $id. ' has been removed.'];
    } else {
      return $this->error('error', url('/admin/news'));
    }
  }

}
