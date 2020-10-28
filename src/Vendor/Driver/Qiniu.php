<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 321418616
 */

namespace HaoMVC\Vendor\Driver;
use think\facade\Request;
use Qiniu\Auth;
use Qiniu\Storage\UploadManager;
use Qiniu\Storage\BucketManager;

class Qiniu
{
	protected $Config;
	protected $bucket = '';
	protected $auth;
	public function __construct($Config)
    {
    	$this->Config = $Config;
		$this->bucket = $Config['config']['bucket'];
		$this->auth = new Auth($Config['config']['accessKey'], $Config['config']['secretKey']);
    }
	public function toUpFile()
	{
		$files = Request::file();
		// 生成上传Token
		$token = $this->auth->uploadToken($this->bucket);
		// 构建 UploadManager 对象
		$uploadMgr = new UploadManager();
		$save = [];
		foreach ($files as $key => $value) {
			$name = trim(trim($key),"_");
			$UrlName = $this->Config['config']['rule'].$name.'/';
			if(is_array($value)){
				$saveF = [];
				foreach ($value as $keys => $file) {
					list($ret, $err) = $uploadMgr->putFile($token, $UrlName.md5_file($file).'.'.$file->getOriginalExtension(), $file);
					$saveF[$keys] = $this->Config['config']['url'] .$ret['key'];
				}
				$save[$name] = $saveF;
			}else{
				list($ret, $err) = $uploadMgr->putFile($token,$UrlName.md5_file($value).'.'.$value->getOriginalExtension(), $value);
				$save[$name] = $this->Config['config']['url'] .$ret['key'];
			}
			
		}
		return array_filter($save);
	}
	
	public function ListFile()
	{
		
	}
	
	public function toDelFile()
	{
		$config = new \Qiniu\Config();
		$bucketManager = new BucketManager($this->auth, $config);
		$files = [];
		if(is_string($this->Config['option'])){
			$files[] = $this->Config['option'];
		}
		
		foreach ($this->Config['option'] as $key => $value) {
			if(is_array($value)){
				foreach ($value as $keys => $values) {
					$files[] = ltrim($values,$this->Config['config']['url']);
				}
			}else{
				$files[] = ltrim($value,$this->Config['config']['url']);
			}
		}
		$ops = $bucketManager->buildBatchDelete($this->bucket, $files);
		list($ret, $err) = $bucketManager->batch($ops);
		if ($err) {
			return false;
		}else {
			return true;
		}
		
		
		
	}
}
