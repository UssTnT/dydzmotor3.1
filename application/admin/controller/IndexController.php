<?php

namespace app\admin\controller;

use app\admin\model\Msg;
use app\admin\model\Product;
use app\admin\model\News;
use app\admin\controller\BaseController;
use think\Controller;
use think\Request;
use think\Db;
class IndexController extends BaseController
{
  /*显示数据资源列表*/
  public function index()
  {	
  	$info['php_version'] = PHP_VERSION;
  	$info['php_sapi'] = PHP_SAPI;
  	$info['os'] = PHP_OS;
  	$info['mysql_version'] = $this->getMysqlVersion();
  	$info['install_dir'] = ROOT_PATH;
  	$this->assign('info',$info);

    $mmodel = new Msg;
    $newInfo['msg'] = $mmodel->whereTime('iTime', 'd')->count();
    $pmodel = new Product;
    $newInfo['product'] = $pmodel->whereTime('pTime', 'd')->count();
    $nmodel = new News;
    $newInfo['news'] = $nmodel->whereTime('nTime', 'd')->count();
    $this->assign('newInfo',$newInfo);

    return $this->fetch();
  }
    
  protected function getMysqlVersion(){
  	$row = Db::query('select version() AS version');
  	return $row[0]['version'];
  }

}






