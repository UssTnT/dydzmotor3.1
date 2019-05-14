<?php
namespace app\index\controller;

use think\Request;
use think\Controller;
use app\index\model\Contact;
use app\index\model\Msg;

class ContactController extends Controller
{
  public function contact()
  {
    $model = new Contact;
    $rows = $model->order('id desc')->limit(2)->select();

    $id = input('param.id');
    $this->assign('rows',$rows);

    if(!empty($id)) {
      $this->assign('type',$id);
    }
    return $this->fetch();
  }

  public function send()
  {
    $model = new Msg;
    $data = input('post.');
    $data['iTime'] = date('Y-m-d H:i:s',time());

    
    $int = $model->insert($data);
    if($int) {
      return $this->redirect('/contact/1');
    } else {
      return $this->redirect('/contact/2');
    }
  }

}
