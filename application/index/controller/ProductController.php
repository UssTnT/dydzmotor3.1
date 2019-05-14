<?php
namespace app\index\controller;

use think\Request;
use erusev\Parsedown;
use think\Controller;
use app\index\model\Product;
use app\index\model\Details;
use app\index\model\Category;
use app\index\model\Type;

class ProductController extends Controller
{
  /*显示所有资源*/
  public function product()
  {
    $pmodel = new Product;
    $dmodel = new Details;
    $cmodel = new Category;
    $tmodel = new Type;

    $list = $pmodel->order('id desc')->paginate();
    foreach ($list as $prow) {
      $v = $dmodel->where('pId',$prow['id'])->value('dPdf');
      if(!is_null($v)) {
        $prow['href'] = '/uploads/pdf/' . $v;
      } else {
        $prow['href'] = '';
      }
    }
    $page = $list->render();
    $this->assign('list',$list);
    $this->assign('page', $page);

    $toolbar = $this->setToolbar($list);
    $this->assign('toolbar', $toolbar);

    $crow = $cmodel->select();
    $trow = $tmodel->select();
    $this->assign('crows', $crow);
    $this->assign('trows', $trow);

    return $this->fetch();
  }

  /*显示详细资源*/
  public function detail()
  {
    $id = input('param.id');
    $pmodel = new Product;
    $dmodel = new Details;
    $cmodel = new Category;
    $tmodel = new Type;
    $prow = $pmodel->where('id', $id)->find();
    $drow = $dmodel->where('pId', $id)->find();
    $Parsedown = new Parsedown;
    $prow['dChara'] = $Parsedown->setMarkupEscaped(true)->setBreaksEnabled(true)->text($drow['dChara']);
    $prow['dDesc'] = $Parsedown->setMarkupEscaped(true)->setBreaksEnabled(true)->text($drow['dDesc']);
    $crow = $cmodel->where('id', $prow['cId'])->find();
    $prow['cName'] = $crow['cName'];
    $trow = $tmodel->where('id', $crow['tId'])->find();
    $prow['tName'] = $trow['tName'];

    return $prow;
  }

  /*显示类型资源*/
  public function category($id)
  {
    $pmodel = new Product;
    $dmodel = new Details;
    $cmodel = new Category;
    $tmodel = new Type;

    $list = $pmodel->where('cId', $id)->order('id desc')->paginate();
    foreach ($list as $prow) {
      $v = $dmodel->where('pId',$prow['id'])->value('dPdf');
      if(!is_null($v)) {
        $prow['href'] = '/uploads/pdf/' . $v;
      } else {
        $prow['href'] = '';
      }
    }
    $page = $list->render();
    $this->assign('list',$list);
    $this->assign('page', $page);

    $toolbar = $this->setToolbar($list);
    $this->assign('toolbar', $toolbar);

    $crow = $cmodel->select();
    $trow = $tmodel->select();
    $this->assign('crows', $crow);
    $this->assign('trows', $trow);
    
    $cname = $cmodel->where('id', $id)->value('cName');
    $this->assign('cname', $cname);

    return $this->fetch('product');
  }

  /*显示查询结果资源*/
  public function search()
  {
    $pmodel = new Product;
    $dmodel = new Details;
    $cmodel = new Category;
    $tmodel = new Type;

    $s = input('s');

    $list = $pmodel->where('pName', 'like', '%'.$s.'%')->order('id desc')->paginate();
    foreach ($list as $prow) {
      $v = $dmodel->where('pId',$prow['id'])->value('dPdf');
      if(!is_null($v)) {
        $prow['href'] = '/uploads/pdf/' . $v;
      } else {
        $prow['href'] = '';
      }
    }
    $page = $list->render();
    $this->assign('list',$list);
    $this->assign('page', $page);

    $toolbar = $this->setToolbar($list);
    $this->assign('toolbar', $toolbar);

    $crow = $cmodel->select();
    $trow = $tmodel->select();
    $this->assign('crows', $crow);
    $this->assign('trows', $trow);
    
    if($s) {
      $cname = 'Search Result';
    } else {
      return $this->redirect('/product');
    }
    
    $this->assign('cname', $cname);
    
    return $this->fetch('product');
  }

  /*显示单个资源*/
  public function view($id)
  {
    $pmodel = new Product;
    $dmodel = new Details;
    $cmodel = new Category;
    $tmodel = new Type;
    $prow = $pmodel->where('id', $id)->find();
    $drow = $dmodel->where('pId', $id)->find();
    $Parsedown = new Parsedown;
    $prow['dChara'] = $Parsedown->setMarkupEscaped(true)->setBreaksEnabled(true)->text($drow['dChara']);
    $prow['dDesc'] = $Parsedown->setMarkupEscaped(true)->setBreaksEnabled(true)->text($drow['dDesc']);
    $crow = $cmodel->where('id', $prow['cId'])->find();
    $prow['cName'] = $crow['cName'];
    $trow = $tmodel->where('id', $crow['tId'])->find();
    $prow['tName'] = $trow['tName'];

    $this->assign('prow',$prow);
    return $this->fetch();
  }

  /*设置结果数量*/
  public function setToolbar($list)
  {
    $toolbar = array();
    $cp = $list->currentPage();
    $lp = $list->lastPage();
    $lr = $list->listRows();
    $tt = $list->total();
    $toolbar['tt'] = $tt;
    if( $lp==$cp ) {
      if( $lp==1 ) {
        $toolbar['ls'] = 1;
        $toolbar['rs'] = $tt;
      } else {
        $toolbar['ls'] = ($cp-1)*$lr+1;
        $toolbar['rs'] = ($cp-1)*$lr+count($list);
      }
    } else {
      if( $cp==1 ) {
        $toolbar['ls'] = 1;
        $toolbar['rs'] = $cp*$lr;
      } else {
        $toolbar['ls'] = ($cp-1)*$lr+1;
        $toolbar['rs'] = $cp*$lr;
      }
    }

    return $toolbar;
  }

}
