<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 321418616
 */

namespace HaoMVC\Vendor;
use think\facade\Config;
use think\facade\Request;

class FileManage
{
	
	public static function Upload($option=[],$device=false)
	{
		if(!isEmptyArray(Request::file())){
			return ['code'=>400,'msg'=>'未上传文件'];
		}else{
			return self::MakeConfig($option,$device)->toUpFile();
		}
	}
	public static function Del($option=[],$device=false)
	{
		return self::MakeConfig($option,$device)->toDelFile();
	}
	
	public static function MakeConfig($option=[],$device=false)
	{
		$device = $device?$device:Config::get('filesystem.default');
		$Config = Config::get('filesystem.disks.'.$device);
		$Driver = "HaoMVC\Vendor\Driver\\".ucfirst($Config['type']);
		$Config['ruleKey'] = get_match_all("/({%)(.*?)(%})/", $Config['name_rules']);
		try{
			$Config['rule'] = str_replace($Config['ruleKey'], $option,$Config['name_rules']);
		}catch(\Exception $e){
		}
		$Configs = ['disk'=> $device,'config' => $Config,'option' => $option];
		return new $Driver($Configs);
	}

}
