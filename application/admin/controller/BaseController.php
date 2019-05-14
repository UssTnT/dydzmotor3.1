<?php

namespace app\admin\controller;

use think\Request;
use think\Controller;

class BaseController extends Controller
{
	protected function _initialize()
	{	
		
		if( !session('?userid') && !in_array($this->request->action(),['login','logging']) ){
			return $this->redirect('/admin');
		}

		//录入员
		/*if(session('permission') == 1 && in_array($this->request->controller(),['delete'])){
			session('userid',null);
			session('username',null);
			session('permission',null);
			return $this->error('权限不足',url('/admin/index'));			
		}*/

		//管理员
		/*if(session('permission')  == 2 && $this->request->controller() == 'Admin' && in_array($this->request->action(),['delete','create'])){
			session('userid',null);
			session('username',null);
			session('permission',null);
			return $this->error('权限不足',url('/admin/index'));
		}*/
		
		//$this->_init(); 
	} 
	
	protected function _init()
	{
		
	}
}



