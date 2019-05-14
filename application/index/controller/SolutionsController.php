<?php
namespace app\index\controller;

use think\Request;
use erusev\Parsedown;
use think\Controller;
use app\index\model\Solution;

class SolutionsController extends Controller
{
  /*显示关于信息资源*/
  public function solutions()
  {
    $model = new Solution;
    $row = $model->where('id',1)->find();
    $Parsedown = new Parsedown;
    $row['sInfo'] = $Parsedown->setMarkupEscaped(true)->setBreaksEnabled(true)->text($row['sInfo']);

    $this->assign('row',$row);
    return $this->fetch();
  }

}
