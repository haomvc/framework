<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 321418616
 */


namespace HaoMVC;

use HaoMVC\Model\AdminUser;
use HaoMVC\Model\AdminMenu;
use think\facade\Db;
use think\facade\Config;
use think\facade\Session;

class AdminBase extends Base
{
    protected $Admin;
    protected $argss;
    protected function initialize()
    {
        $this->VerifyAuth();
    }
    public function getSqlField()
    {
    	Config::load(__DIR__. '/config/sqlfield.php', 'DBfield');
        return require_once __DIR__. '/config/sqlfield.php';
    }
    protected function getBackAuth()
    {
        return AdminMenu::where(['admin_menu_id'=>0])->with('children')->select()->toArray();
    }
	protected function VerifyAuth()
	{
		if(!Session::has('Admin')){
			Error('未登录，请重新登录',-10001,['url'=>'/Index/Login']);
		}
		$Admin = Session::get('Admin');
		if($Admin['super']==0){
			$ControlAction = $this->request->controller().'|'.$this->request->action();
			$aid = Config::get('AdminAuth.'.$ControlAction,false);
			if($Admin['isAuth']==1){
				if(!$aid || !in_array($aid, $Admin['auth'])){
					Error('无权限，请联系管理员',-10001,['url'=>'/Index/NoAuth']);
				}
			}else{
				if(!$aid || !in_array($aid, $Admin['group']['auth'])){
					Error('无权限，请联系管理员',-10001,['url'=>'/Index/NoAuth']);
				}
			}
		}
		
	}
	protected function CheckAction($auth)
	{
		
	}
    public function UeditControl()
    {
    	return Vendor\Ueditor::Method($this->request->param());
    }
    public function __call($method, $args)
    {
      return  json('方法不存在');
    }
}
