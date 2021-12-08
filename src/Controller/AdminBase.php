<?php
// +----------------------------------------------------------------------
// | Created by Laotian on 2021-07-30.
// | Copyright 2021 HaoMVC. All rights reserved.
// +----------------------------------------------------------------------
declare (strict_types = 1);


namespace HaoMVC\Controller;

use HaoMVC\Model\AdminUser;

/**
 * 
 */

class AdminBase extends Base
{
	protected $Admin;
	public function initialize()
	{
		parent::initialize();
		if(!$this->request->header('Authorization')){
			return Error(['code'=>50008,'message'=>'未登录，请重新登录','head'=>$this->request->header('Authorization')]);
		}else{
			$AdminInfo = AdminUser::where('token',$this->request->header('Authorization'))->field('token,name,avatar')->find();
			if(!$AdminInfo){
				return Error(['code'=>50008,'message'=>'未登录，请重新登录','head'=>$this->request->header('Authorization')]);
			}else{
				$this->Admin = $AdminInfo;
			}
		}
	}
}

