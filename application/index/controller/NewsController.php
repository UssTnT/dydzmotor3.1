<?php
namespace app\index\controller;

use think\Request;
use erusev\Parsedown;
use think\Controller;
use app\index\model\News;

class NewsController extends Controller
{
  /*显示所有资源*/
  public function news()
  {
    $model = new News;
    $list = $model->order('id desc')->paginate();
    $page = $list->render();
    $this->assign('list',$list);
    $this->assign('page', $page);

    return $this->fetch();
  }

  /*显示单个资源*/
  public function view($id)
  {
    $model = new News;
    $row = $model->where('id', $id)->find();
    $Parsedown = new Parsedown;
    $row['nInfo'] = $Parsedown->setMarkupEscaped(true)->setBreaksEnabled(true)->text($row['nInfo']);
    $this->assign('row',$row);

    return $this->fetch();
  }

}
