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
	public function getTypeAttr($value)
    {
        $status = [1=>'操作',0=>'菜单'];
        return $status[$value];
    }
	public function children()
    {
        return $this->hasMany(self::class);
    }
	public function GetMenu()
    {
    	$User = session('Admin');
    	return $User['super']==1?$this->where('admin_menu_id',0)->with('children')->select():$this->whereIn('id',$User['auth'])->where('admin_menu_id',0)->with('children')->select();
    }
}

