<?php
namespace app\index\controller;

use think\Request;
use think\Controller;
use erusev\Parsedown;
use app\index\model\Product;
use app\index\model\About;
use app\index\model\Contact;
use app\index\model\Details;
use app\index\model\News;

class IndexController extends Controller
{
  /* 显示首页资源 */
  public function index()
  {
    
    $pmodel = new Product;
    $dmodel = new Details;
    $amodel = new About;
    $nmodel = new News;
    $cmodel = new Contact;

    $prows = $pmodel->where('isNew',1)->order('id desc')->limit(12)->select();
    foreach ($prows as $prow) {
      $v = $dmodel->where('pId',$prow['id'])->value('dPdf');
      if(!is_null($v)) {
        $prow['href'] = '/uploads/pdf/' . $v;
      } else {
        $prow['href'] = '/product/' . $prow['id'];
      }
    }
    $this->assign('prows',$prows);

    $Parsedown = new Parsedown;
    $pattern = "/<p>[\s\S]*?<\/p>/i"; /* 匹配第一个p标签内容 */
    $aInfo = $amodel->where('id',1)->value('aInfo');
    $description = $Parsedown->setMarkupEscaped(true)->text($aInfo);//->setBreaksEnabled(true)
    preg_match($pattern,$description,$dir);
    if(!empty($dir)) {
      $arow['aInfo'] = substr($dir[0],0,300).'...';
    } else {
      $arow['aInfo'] = 'There is no content';
    }
    $this->assign('arow',$arow);


    $nrows = $nmodel->order('nTime desc')->limit(5)->select();
    foreach ($nrows as $nrow) {
      $nrow['nTime'] = date("Y-m-d", strtotime($nrow['nTime']));
    }
    $this->assign('nrows',$nrows);
    
    $crow = $cmodel->where('id',1)->find();
    $this->assign('crow',$crow);



    return $this->fetch();
  }
}
