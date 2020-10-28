<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 321418616
 */


namespace HaoMVC\Vendor\Driver;
use think\facade\Request;


class Local
{
	protected $Config;
	
	public function __construct($Config)
    {
    	$this->Config = $Config;
    }
	public function toUpFile()
	{
		$files = Request::file();
		
		$save = [];
		foreach ($files as $key => $value) {
			$name = trim(trim($key),"_");
			if(is_array($value)){
				$saveF = [];
				foreach ($value as $keys => $file) {
					$saveFile = \think\facade\Filesystem::disk($this->Config['disk'])->putFile($this->Config['config']['rule'].$name, $file,function($e){
						return md5_file($e);
					});
					$saveF[$keys] = $this->Config['config']['url'] .$saveFile;
				}
				$save[$name] = $saveF;
			}else{
				$saveFile = \think\facade\Filesystem::disk($this->Config['disk'])->putFile($this->Config['config']['rule'].$name, $value,function($e){
					return md5_file($e);
				});
				$save[$name] = $this->Config['config']['url'] .$saveFile;
			}
			
		}
		return array_filter($save);
	}
	
	public function toDelFile()
	{
		$file = ltrim($this->Config['option'],$this->Config['config']['url']);
		try{
			
			$del = unlink($this->Config['config']['root'].$file);
			if($del){
				return 0;
			}else{
				return 1;
			}
		}catch(\Exception $e){
			return 2;
		}
		
		
	}
}
