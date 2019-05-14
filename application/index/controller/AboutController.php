<?php
namespace app\index\controller;

use think\Request;
use erusev\Parsedown;
use think\Controller;
use app\index\model\About;

class AboutController extends Controller
{
  /*显示关于信息资源*/
  public function about()
  {
    $amodel = new About;
    $arow = $amodel->where('id',1)->find();
    $Parsedown = new Parsedown;
    $arow['aInfo'] = $Parsedown->setMarkupEscaped(true)->setBreaksEnabled(true)->text($arow['aInfo']);

    $this->assign('arow',$arow);
    return $this->fetch();
  }
}
