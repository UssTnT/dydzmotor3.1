<?php
namespace app\admin\validate;
use think\Validate;
class Admin extends Validate{
/*  数据验证开始  */  
    protected $rule = [
      'username'  => 'require|min:6',
      'password' => 'require|max:32'
    ];
    protected $message  =   [
        'username.require' => '名称必须',
        'username.min'     => '名称最少6个字符',
        'password.require'   => '密码必须',
        'password.max'  => '密码最多不能超过25个字符',
    ];        
/*  结束  */
 
/*  添加场景验证  */
  protected $scene = [          
      /*
       * 'add'  =>  ['username'=>'require','password'],
       * 在add页面，只验证 username的require（必填），对别的不验证
       */
       
       /* 在add页面添加验证用户名 username  密码password*/
         'add'  =>  ['username','password'],
         
    ];
}
 
?>
