<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 1446716651
 */


namespace HaoMVC\Model;
use think\Model;

class AdminGroup extends Model
{
	protected $autoWriteTimestamp = true;
	protected $type = ['stop_time' =>'timestamp','auth'=>'serialize'];
	
	public function user()
    {
        return $this->hasMany(AdminUser::class);
    }
	public function Normaluser()
    {
        return $this->user()->where('stop',0);
    }
	public function Stopuser()
    {
        return $this->user()->where('stop',1);
    }
	public function Expireuser()
    {
        return $this->user()->where('stop_time','>',time());
    }
}

