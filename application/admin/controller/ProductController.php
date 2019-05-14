<?php

namespace app\admin\controller;

use app\admin\controller\BaseController;
use think\Request;
use erusev\Parsedown;
use app\admin\model\Product;
use app\admin\model\Details;
use app\admin\model\Type;
use app\admin\model\Category;


class ProductController extends BaseController
{
  /*显示产品资源列表*/
  public function index() {
    $model = new Product;
    $cmodel = new Category;
    $row = $model->order('id','desc')->select();
    $crow = $cmodel->select();
    if(!$row->isEmpty()) {
      $this->assign('rowset',$row);
      $this->assign('crowset',$crow);

      return $this->fetch();
    } else {
      return $this->error('error: Sorry, no product information has been found. You can add a product first', url('/admin/product/create'));
    }
  }

  /*显示添加资源列表*/
  public function create() {
    $cmodel = new Category;
    $tmodel = new Type;
    $crow = $cmodel->select();
    $trow = $tmodel->select();
    $this->assign('crows', $crow);
    $this->assign('trows', $trow);
    return $this->fetch();
  }

  /*添加资源*/
  public function save(Request $request) {
    $model = new Product;
    $dmodel = new Details;
    $data = $request->post();
    $pdata = array();
    $ddata = array();
    //dump($data);
    if($request->has('pImg','file')) {
      $file = $request->file('pImg');
      if($file){
        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])->move (ROOT_PATH . 'public' . DS . 'uploads/img');
        if($info) {
          $pdata['pImg'] = str_replace('\\','/',$info->getSaveName());
        } else{
          return $this->error('error:' . $file->getError(), url('/admin/product/create'));
        }
      }
    } else {
      $pdata['pImg'] = 'Noupload.jpg';
    }
    if($request->has('dPdf','file')) {
      $file = $request->file('dPdf');
      if($file){
        $info = $file->validate(['ext'=>'pdf'])->move (ROOT_PATH . 'public' . DS . 'uploads/pdf');
        if($info) {
          //$ddata['dPdf'] = $info->getSaveName();
          $ddata['dPdf'] = str_replace('\\','/',$info->getSaveName());
        } else{
          return $this->error('error:pdf' . $file->getError(), url('/admin/product/create'));
        }
      }
    }
    $pdata['pName'] = $data['pName'];
    $pdata['cId'] = $data['cId'];
    if($request->has('isNew')) {
      $pdata['isNew'] = 1;
    } else {
      $pdata['isNew'] = 0;
    }
    $pdata['pTime'] = date('Y-m-d H:i:s',time());
    

    $ddata['dChara'] = $data['pChara'];
    $ddata['dDesc'] = $data['pDesc'];

    $int = $model->insert($pdata);//insertGetId
    $pid = $model->getLastInsID();
    $sint = $dmodel->where('pId', $pid)->find();
    $dint = 0;
    if($sint) {
      $dint = $dmodel->where('pId', $pid)->update($ddata);
    } else {
      $ddata['pId'] = $pid;
      $dint = $dmodel->insert($ddata);
    }
    if ($int && $dint) {
      return $this->success('success', url('/admin/product'));
    } else {
      return $this->error('error', url('/admin/product/create'));
    }
  }

  /*显示修改资源列表*/
  public function edit($id) {
    $model = new Product;
    $dmodel = new Details;
    $cmodel = new Category;
    $tmodel = new Type;
    $row = $model::get($id);
    $drow = $dmodel->where('pId', $id)->find();
    $crow = $cmodel->select();
    $trow = $tmodel->select();
    $this->assign('row', $row);
    if($drow) {
      $this->assign('drow', $drow);
    }
    $this->assign('crows', $crow);
    $this->assign('trows', $trow);
    return $this->fetch();
  }

  /*更新资源*/
  public function update(Request $request, $id) {
    $model = new Product;
    $dmodel = new Details;
    $data = $request->post();
    $pdata = array();
    $ddata = array();
    //dump($data);
    if($request->has('pImg','file')) {
       //think\File;
      // 上传文件的信息
      $file = $request->file('pImg');
      if($file){
        // 成功上传的文件信息
        $info = $file->validate(['size'=>2097152,'ext'=>'jpg,png,gif'])->move (ROOT_PATH . 'public' . DS . 'uploads/img');
        if($info) {
          //$data ['pImg'] = str_replace('\\','/',$uploadFile->getSaveName ());
          // 成功上传后 获取上传信息
          // 输出 jpg
          //echo $info->getExtension();
          // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
          //echo $info->getSaveName();
          // 输出 42a79759f284b767dfcb2a0197904287.jpg
          //echo $info->getFilename();
          //$pdata['pImg'] = $info->getSaveName();
          $pdata['pImg'] = str_replace('\\','/',$info->getSaveName());
        } else{
          // 上传失败获取错误信息
          //echo $file->getError();
          //$pdata['pImg'] = 'Noupload.jpg';
          return $this->error('error:' . $file->getError(), url('/admin/product/edit/id/' . $id));
        }
      }
    } else {
      $srcImg = $model->where('id', $id)->value('pImg');
      if(!strcmp('Noupload.jpg',$srcImg)) {
        $pdata['pImg'] = 'Noupload.jpg';
      } else {
        $pdata['pImg'] = $srcImg;
      }
    }
    if($request->has('dPdf','file')) {
      $file = $request->file('dPdf');
      if($file){
        $info = $file->validate(['ext'=>'pdf'])->move (ROOT_PATH . 'public' . DS . 'uploads/pdf');
        if($info) {
          //$ddata['dPdf'] = $info->getSaveName();
          $ddata['dPdf'] = str_replace('\\','/',$info->getSaveName());
        } else{
          return $this->error('error:pdf' . $file->getError(), url('/admin/product/edit/id/' . $id));
        }
      }
    } else {
      $srcPdf = $dmodel->where('pId', $id)->value('dPdf');
      if(!$srcPdf) {
        $ddata['pImg'] = $srcPdf;
      } else {
        $ddata['pImg'] = null;
      }
    }
    $pdata['pName'] = $data['pName'];
    $pdata['cId'] = $data['cId'];
    if($request->has('isNew')) {
      $pdata['isNew'] = 1;
    } else {
      $pdata['isNew'] = 0;
    }
    //$pdata['pTime'] = date('Y-m-d H:i:s',time());
    

    $ddata['dChara'] = $data['pChara'];
    $ddata['dDesc'] = $data['pDesc'];

    $int = $model->where('id', $id)->update($pdata);
    $sint = $dmodel->where('pId', $id)->find();
    $dint = 0;
    if($sint) {
      $dint = $dmodel->where('pId', $id)->update($ddata);
    } else {
      $ddata['pId'] = $id;
      $dint = $dmodel->insert($ddata);
    }
    if ($int || $dint) {
      return $this->success('success', url('/admin/product'));
    } else {
      return $this->error('error', url('/admin/product/edit/id/' . $id));
    }
  }

  /*删除资源*/
  public function delete($id){
    $model = new Product;
    $int = $model->where('id', $id)->delete();
    if($int) {
      return ['type'=>'1','url'=>'/admin/product','msg'=>'The product content with id ' . $id. ' has been removed.'];
    } else {
      return $this->error('error', url('/admin/product'));
    }
  }

  public function txtarea() {
    //dump(input('txtarea'));
    $Parsedown = new Parsedown;
    $Parsedown->text(input('txtarea')); 
    //print_r(input('txtarea'));
    //dump(nl2br(input('txtarea')));
    //print_r(nl2br(input('txtarea')));
    
    //print_r(htmlentities(nl2br(input('txtarea'))));
    /*$txtarea['msg'] = nl2br(input('txtarea'));
    $this->assign('txtarea', $txtarea);
    return $this->fetch('/product/index');*/

    $md = input('md');
    $html = $Parsedown->setBreaksEnabled(true)->setMarkupEscaped(true)->text($md);
    return ['md'=>$md,'html'=>$html];
  }
	 
}
