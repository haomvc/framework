<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 321418616
 */


namespace HaoMVC\Model;

use think\Model;
use think\facade\Session;
use think\facade\Config;

class AdminUser extends Model
{
	protected $type = ['stop_time' =>'timestamp','login_time' =>'timestamp','auth'=>'serialize'];
	public function getGroupAttr($value,$data)
    {
        $status = Config::get('HaoMVC.AdminAuth');
        return $status[$data['gid']];
    }
	public static function doLogin($data)
	{
		$Admin = self::where(['login_name'=>$data['username']])->find();
		if(!$Admin){
			return 0;
		}
		if(PSW_MD6($data['password']) != $Admin['login_pass']){
			return 0;
		}else{
			unset($Admin['password']);
		}
		if($Admin['sta'] ==0){
			return 2;
		}
		
		$access_token = md5($Admin['login_name'].time());
		$Admin->token = $access_token;
		$Admin->login_ip = request()->ip();
		$Admin->login_time = time();
		$Admin->save();
		Session::set('Admin',$Admin->toArray());
		return $access_token;
	}
	public static function ToUpdate($post)
	{
		if(empty($post['oldpass'])){
			return ['code'=>1,'msg'=>'必须填写原密码'];
		}
		$Admin = self::find(session('Admin.id'));
		if(PSW_MD6($post['oldpass']) != $Admin['login_pass']){
			return ['code'=>2,'msg'=>'原密码不正确'];
		}
		
		if(isset($post['newpass']) && !empty($post['newpass'])){
			$Admin->login_pass = PSW_MD6($post['newpass']);
			$save = $Admin->save();
			Session::delete('Admin');
			return ['code'=>1000,'msg'=>'修改成功'];
		}else{
			return ['code'=>3,'msg'=>'必须填写新密码'];
		}
	}
}
