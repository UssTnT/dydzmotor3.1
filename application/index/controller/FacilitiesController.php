<?php
namespace app\index\controller;

use think\Request;
use think\Controller;
use app\index\model\Facility;

class FacilitiesController extends Controller
{
  /*显示资源*/
  public function facilities()
  {
    $model = new Facility;
    $rows = $model->order('id desc')->limit(8)->select();
    $this->assign('rows',$rows);
    return $this->fetch();
  }
}
