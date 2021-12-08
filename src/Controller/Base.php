<?php
// +----------------------------------------------------------------------
// | Created by Laotian on 2021-07-30.
// | Copyright 2021 HaoMVC. All rights reserved.
// +----------------------------------------------------------------------

namespace HaoMVC\Controller;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use think\facade\Config;
use think\facade\Cache;
use think\facade\Session;
use HaoMVC\Model\Setting;


/**
 * 控制器基础类
 */
abstract class Base
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];
	
	protected $WebSite = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
    	if(!Cache::get('WebSite')){
    		$this->WebSite = Setting::ArrAll();
			Cache::set('WebSite',$this->WebSite);
    	}else{
    		$this->WebSite = Cache::get('WebSite');
    	}
		$this->view->config(['view_dir_name' =>'view'.DIRECTORY_SEPARATOR.$this->WebSite['templet'],'tpl_cache'=>false]);
		$this->view->assign('WebSite',$this->WebSite);
    }

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
	public function __get($method)
	{
		return $this->app[$method];
	}
	protected function getTemplet()
	{
		return getfiles(root_path().'view/'.$this->WebSite['templet'],'html');
	}
	protected function FilesUpload($files,$path='topic')
	{
		$filesConfig = Config::get('filesystem');
		$LocalPath = $filesConfig['disks'][$filesConfig['default']]['url'];
		try{
			if(is_array($files)){
				$fileArr = [];
				foreach($files as $key=>$file){
					$name = $file->md5() . '.' . $file->extension();
        			$fileArr[$key]['url'] = $LocalPath. \think\facade\Filesystem::putFileAs($path, $file,$name);
					$fileArr[$key]['size'] = $file->getSize();
					$fileArr[$key]['name'] = $name;
					$fileArr[$key]['ext'] = $file->extension();
    			}
				return $fileArr;
			}else{
				$name = $files->md5() . '.' . $files->extension();
        		$fileArr['url'] = $LocalPath. \think\facade\Filesystem::putFileAs($path, $files,$name);
				$fileArr['size'] = $files->getSize();
				$fileArr['name'] = $name;
				$fileArr['ext'] = $files->extension();
				return $fileArr;
			}
		}catch (\think\exception\ValidateException $e) {
        	return Error($e->getMessage());
    	}
	}
	protected function FilesDel($name)
	{
		$Config = Config::get('filesystem');
		$fileConfig =  $Config['disks'][$Config['default']];
		if($fileConfig['type'] !='local'){
			$str = str_replace($fileConfig['url'],'',$name);
			$del = \think\facade\Filesystem::delFileAs($str);
			if($del){
				return Success('删除成功');
			}else{
				return Error($del);
			}
		}else{
			$file = public_path(). $name;
			if (!unlink($file)){
				return Error('删除失败');
			}else{
				return Success('删除成功');
			}
		}
	}

}
