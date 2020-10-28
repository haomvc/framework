<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 321418616
 */


namespace HaoMVC\Vendor;

use HaoMVC\AdminBase;
use think\facade\Db;
use HaoMVC\Model\AdminMenu;
use HaoMVC\Model\AdminUser;
use HaoMVC\Vendor\MysqlManage;
use think\facade\Request;
use think\facade\View;

class Super
{
	public static function Method() {
		$Method = Request::only(['method'])['method'];
		return self::$Method(Request::except(['method']));
	}
	public static function SetMenu($arr)
	{
		if(Request::header('GTYPE')=='json'){
			$mid = isset($arr['admin_menu_id'])?$arr['admin_menu_id']:0;
			return json(AdminMenu::where('admin_menu_id',$mid)->with('children')->select());
		}
		return View::fetch('Super/SetMenu');
	}
	public static function AddMenu($arr)
	{
		if (Request::isPost()) {
            $save = AdminMenu::create($this -> request -> post());
			return $save?Success('成功'):Error('失败');
        }
		View::assign(['cate'=>AdminMenu::where('admin_menu_id', 0)->with('children')->select()]);
		return View::fetch('Super/EditMenu');
	}
	public static function EditMenu($arr)
	{
		if (!isset($arr['id'])) {
            Error();
        }
		if (Request::isPost()) {
            $save = AdminMenu::update($this -> request -> post());
			return $save?Success('成功'):Error('失败');
        }
		$id = $arr['id'];
		View::assign(['Menu'=>AdminMenu::find($id)]);
		View::assign(['cate'=>AdminMenu::where('id','<>',$id)->where('admin_menu_id', 0)->with(['children'=>function($query) use ($id){$query->where('id','<>',$id);}])->select()]);
		return View::fetch('Super/EditMenu');
	}
	public static function DelMenu($arr)
    {
        if (!isset($arr['id'])) {
            Error();
        }
        $id = $arr['id'];
        if (AdminMenu::where('admin_menu_id', $id) -> find()) {
            Error('存在下级,不可删除');
        } else {
           return AdminMenu::destroy($id) ? Success('删除成功') : Error('删除失败');
        }
    }
	
	public static function AddMenuConfig()
	{
		$data = '';
		$menu = AdminMenu::where('admin_menu_id',0)->with('children.children')->select();
		foreach ($menu as $key => $control) {
			foreach ($control['children'] as $keys => $action) {
				$data .= "\t"."// ".$action['title']. "\r\n";
				$data .= "\t".'"'.$control['href'].'|'.$action['href'].'" =>' . $action['id'].','. "\r\n";
				if(count($action['children'])>0){
					foreach ($action['children'] as $key3 => $value3) {
						$data .= "\t"."//  ".$value3['title']. "\r\n";
						$data .= "\t".'"'.$control['href'].'|'.$value3['href'].'" =>' . $value3['id'].','. "\r\n";
					}
				}
			}
		}
		$dd = self::buildConfig('AdminAuth',$data);
		return Success('成功');
	}
	
	
	public static function SetSql($arr)
	{
		if(Request::header('GTYPE')=='json'){
			$mid = isset($arr['admin_menu_id'])?$arr['admin_menu_id']:0;
			return json(Db::query('show table status'));
		}
		
		return View::fetch('Super/SetSql');
	}
	public static function EditSql($arr)
	{
		if(Request::header('GTYPE')=='json'){
			$back = [];
			if(isset($arr['table'])){
				$tb = new MysqlManage($arr['table'],'showcolumn');
				$back['data'] = $tb->Todo();
				$back['table'] = $arr['table'];
			}else{
				$back['data']= ['k'=>0];
				$back['table'] = FALSE;
			}
			$back['fields'] = config('DBfield');
			return json($back);
		}
		View::assign(['SqlField'=>config('DBfield')]);
		return View::fetch('Super/EditSql');
	}
	protected static function buildConfig($file,$data)
	{
		$files = file_put_contents(config_path().$file.'.php', self::buildFiles('Config',$data));
		return $files;
	}
	
	protected static function buildFiles(string $name,$config)
    {
        $stub = file_get_contents(__DIR__.'/tpl/'.$name.'.tpl');
        $namespace = trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
		$content = '';
		if(is_array($config)){
			foreach ($config as $key => $value) {
				$set = '';
				if(is_array($value)){
					$set2 = '';
					foreach ($value as $keys => $values) {
						$set2 .= '"'.$keys.'" => "'.$values.'",'."\r\n\r\n"; 
					}
					$set .= '"'.$key.'" => ['.$set2.'],';
				}else{
					$set .= $value;
				}
				$content .= '"'.$key.'" => "'.$set.'",'."\r\n\r\n"; 
			}
		}else{
			$content =$config;
		}
		
        return str_replace(['{%CONTENT%}'], [
            $content,
        ], $stub);
    }
}
