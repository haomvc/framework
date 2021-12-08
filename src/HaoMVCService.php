<?php
// +----------------------------------------------------------------------
// | Created by Laotian on 2021-07-30.
// | Copyright 2021 HaoMVC. All rights reserved.
// +----------------------------------------------------------------------
declare (strict_types = 1);


namespace HaoMVC;
use think\Service;
use think\Route;
use HaoMVC\Model\AdminUser;
use think\facade\Config;
/**
 * 
 */

class HaoMVCService extends Service
{

	public function register()
    {
        // 服务注册
    }

    public function boot(Route $route)
    {
    	$middleware = [
            \think\middleware\AllowCrossDomain::class,
        ];
        $route->rule('User/Login', "\\HaoMVC\\Controller\\Publics@Login")
            ->middleware($middleware);
		$route->rule('User/logout', "\\HaoMVC\\Controller\\Users@logout")
            ->middleware($middleware);
    }
}

