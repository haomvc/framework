<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 1446716651
 */


namespace HaoMVC\Model;
use think\Model;


/**
 * 
 */
class Setting extends Model
{
	protected $pk = 'keys';
	
	public static function ArrAll()
	{
		$website = [];
		foreach (self::select()->toArray() as $key => $value) {
			$website[$value['keys']] = $value['value'];
		}
		return $website;
	}
	public static function saveStr($data)
	{
		$Setting = new static();
		$insData = [];
		foreach ($data as $key => $value) {
			$insData[] = ['keys'=>$key,'value'=>$value];
		}
		return $Setting->saveAll($insData);
	}
}
