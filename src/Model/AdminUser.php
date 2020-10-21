<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 1446716651
 */


namespace HaoMVC\Model;
use think\Model;

class AdminUser extends Model
{
	protected $autoWriteTimestamp = true;
	protected $type = ['end_time' =>'timestamp','auth'=>'serialize'];
	
	public static function doLogin($data)
	{
		$Admin = self::where(['login_name'=>$data['name']])->find();
		if(!$Admin){
			return ['code'=>2,'msg'=>'用户名错误'];
		}
		
		if($Admin['login_pass'] != PSW_MD6($data['password'])){
			return ['code'=>3,'msg'=>'密码错误'];
		}
		if($Admin['status']==0){
			return ['code'=>4,'msg'=>'账户已禁用'];
		}
		if($Admin['end_time'] >0 && $Admin['end_time'] >=time()){
			return ['code'=>5,'msg'=>'账户已到期'];
		}
		unset($Admin['login_pass']);
		$access_token = md5(json_encode($data).time());
		$Admin->token = $access_token;
		$Admin->group;
		$Admin->save();
		
		session('Admin',$Admin->toArray());
		return ['code'=>0,'msg'=>'登陆成功','url'=>'/'];
	}
	
	public function group()
    {
        return $this->belongsTo(AdminGroup::class);
    }
}

