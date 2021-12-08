<?php
// +----------------------------------------------------------------------
// | Created by Laotian on 2021-07-30.
// | Copyright 2021 HaoMVC. All rights reserved.
// +----------------------------------------------------------------------
declare (strict_types = 1);


namespace HaoMVC\Controller;

use think\exception\ValidateException;
use HaoMVC\Model\AdminUser;

/**
 * 
 */

class Publics extends Base
{
	protected $rule =   [
        'username'  => 'require|max:25',
        'password'   => 'require',
    ];
    
    protected $message  =   [
        'username.require' => '必须填写用户名',
        'username.max'     => '用户名最多不能超过25个字符',
        'password.require' => '必须填写密码',
    ];
	protected $loginMsg = ['账号or密码错误','密码错误','账户禁用，请联系管理员','账户已到期'];
	
	public function Login()
    {
    	if($this->request->isPost()){
    		$data = $this->request->only(['username','password'],'post');
    		try {
            	$this->validate($data,$this->rule,$this->message);
				$login = AdminUser::doLogin($data);
				if(is_numeric($login)){
					return Error($this->loginMsg[$login]);
				}else{
					return Success(['code'=>20000,'msg'=>'登录成功','token'=>$login]);
				}
				
        	} catch (ValidateException $e) {
           		Error($e->getError());
        	}
			
    	}else{
    		return 'no';
    	}
        
    }
	
}

