<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 1446716651
 */


namespace HaoMVC\Model;
use think\Model;

class AdminMenu extends Model
{
	
	public function children()
    {
        return $this->hasMany(self::class);
    }
	public function child()
    {
        return $this->hasMany(self::class)->where('type',0)->order('id', 'asc');
    }
	public function par()
    {
        return $this->belongsTo(self::class)->field('id,name');
    }
	public static function GetSelfMenu()
	{
		return self::where('admin_menu_id',0)->order('id', 'asc')->withCount('child')->with('child.child')->select();
	}
	public function c()
    {
    	$User = session('Admin');dd($User['super']==1);
    	return $User['super']==1?$this->where('admin_menu_id',0)->with(['par','children'=>'par'])->select():$this->whereIn('id',$User['auth'])->where('admin_menu_id',0)->with(['par','children.par'])->select();
    }
	public function GetAllMenu()
	{
		return $this->where('admin_menu_id',0)->order('id', 'asc')->withCount('child')->with('child.child')->select();
	}
}


