<?php
namespace app\index\controller;

use think\Request;
use erusev\Parsedown;
use think\Controller;
use app\index\model\Job;

class JobController extends Controller
{
  /*显示job资源*/
  public function job()
  {
    $model = new Job;
    $row = $model->where('id',1)->find();
    $Parsedown = new Parsedown;
    $row['jInfo'] = $Parsedown->setMarkupEscaped(true)->setBreaksEnabled(true)->text($row['jInfo']);

    $this->assign('row',$row);
    return $this->fetch();
  }
}
