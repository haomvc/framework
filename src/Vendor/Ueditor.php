<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 321418616
 */

namespace HaoMVC\Vendor;
use think\facade\Config;
date_default_timezone_set("Asia/Chongqing");
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");

class Ueditor extends Uploader {
	
	public static function Method($data) {
		$CONFIG = Config::get('uedit');
		switch ($data['action']) {
			case 'config' :
				$result = json_encode($CONFIG);
				break;

			case 'uploadimage' :
			case 'uploadscrawl' :
			case 'uploadvideo' :
			case 'uploadfile' :
				$result = self::ActionUpload($data);
				break;

			case 'listimage' :
				$result = self::ActionList($data);
				break;
			case 'listfile' :
				$result = self::ActionList($data);
				break;

			case 'catchimage' :
				$result = self::ActionCrawler($data);
				break;

			default :
				$result = json_encode(array('state' => '请求地址出错'),JSON_UNESCAPED_UNICODE);
				break;
		}
		if (isset($data["callback"])) {
			if (preg_match("/^[\w_]+$/", $data["callback"])) {
				echo htmlspecialchars($data["callback"]) . '(' . $result . ')';exit;
			} else {
				echo json_encode(array('state' => 'callback参数不合法'),JSON_UNESCAPED_UNICODE);exit;
			}
		} else {
			echo $result;exit;
		}
	}

	public static function ActionUpload() {
		$CONFIG = Config::get('uedit');
		$base64 = "upload";
		switch (htmlspecialchars($data['action'])) {
			case 'uploadimage' :
				$config = array("pathFormat" => $CONFIG['imagePathFormat'], "maxSize" => $CONFIG['imageMaxSize'], "allowFiles" => $CONFIG['imageAllowFiles']);
				$fieldName = $CONFIG['imageFieldName'];
				break;
			case 'uploadscrawl' :
				$config = array("pathFormat" => $CONFIG['scrawlPathFormat'], "maxSize" => $CONFIG['scrawlMaxSize'], "allowFiles" => $CONFIG['scrawlAllowFiles'], "oriName" => "scrawl.png");
				$fieldName = $CONFIG['scrawlFieldName'];
				$base64 = "base64";
				break;
			case 'uploadvideo' :
				$config = array("pathFormat" => $CONFIG['videoPathFormat'], "maxSize" => $CONFIG['videoMaxSize'], "allowFiles" => $CONFIG['videoAllowFiles']);
				$fieldName = $CONFIG['videoFieldName'];
				break;
			case 'uploadfile' :
			default :
				$config = array("pathFormat" => $CONFIG['filePathFormat'], "maxSize" => $CONFIG['fileMaxSize'], "allowFiles" => $CONFIG['fileAllowFiles']);
				$fieldName = $CONFIG['fileFieldName'];
				break;
		}
		$up = new Uploader($fieldName, $config, $base64);
		return json_encode($up -> getFileInfo(),JSON_UNESCAPED_UNICODE);exit;
	}

	public static function ActionList() {
		$CONFIG = Config::get('uedit');
		/* 判断类型 */
		switch ($data['action']) {
			/* 列出文件 */
			case 'listfile' :
				$allowFiles = $CONFIG['fileManagerAllowFiles'];
				$listSize = $CONFIG['fileManagerListSize'];
				$path = $CONFIG['fileManagerListPath'];
				break;
			/* 列出图片 */
			case 'listimage' :
			default :
				$allowFiles = $CONFIG['imageManagerAllowFiles'];
				$listSize = $CONFIG['imageManagerListSize'];
				$path = $CONFIG['imageManagerListPath'];
		}
		$allowFiles = substr(str_replace(".", "|", join("", $allowFiles)), 1);

		/* 获取参数 */
		$size = isset($data['size']) ? htmlspecialchars($data['size']) : $listSize;
		$start = isset($data['start']) ? htmlspecialchars($data['start']) : 0;
		$end = $start + $size;

		/* 获取文件列表 */
		$path = $_SERVER['DOCUMENT_ROOT'] . (substr($path, 0, 1) == "/" ? "" : "/") . $path;
		$files = getfiles($path, $allowFiles);
		if (!count($files)) {
			return json_encode(array("state" => "no match file", "list" => array(), "start" => $start, "total" => count($files)),JSON_UNESCAPED_UNICODE);exit;
		}

		/* 获取指定范围的列表 */
		$len = count($files);
		for ($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--) {
			$list[] = $files[$i];
		}
		//倒序
		//for ($i = $end, $list = array(); $i < $len && $i < $end; $i++){
		//    $list[] = $files[$i];
		//}

		/* 返回数据 */
		$result = json_encode(array("state" => "SUCCESS", "list" => $list, "start" => $start, "total" => count($files)),JSON_UNESCAPED_UNICODE);exit;

		return $result;


	}

	public static function ActionCrawler() {
		set_time_limit(0);
		$CONFIG = Config::get('uedit');
		/* 上传配置 */
		$config = array("pathFormat" => $CONFIG['catcherPathFormat'], "maxSize" => $CONFIG['catcherMaxSize'], "allowFiles" => $CONFIG['catcherAllowFiles'], "oriName" => "remote.png");
		$fieldName = $CONFIG['catcherFieldName'];

		/* 抓取远程图片 */
		$list = array();
		if (isset($_POST[$fieldName])) {
			$source = $_POST[$fieldName];
		} else {
			$source = $_GET[$fieldName];
		}
		foreach ($source as $imgUrl) {
			$item = new Uploader($imgUrl, $config, "remote");
			$info = $item -> getFileInfo();
			array_push($list, array("state" => $info["state"], "url" => $info["url"], "size" => $info["size"], "title" => htmlspecialchars($info["title"]), "original" => htmlspecialchars($info["original"]), "source" => htmlspecialchars($imgUrl)));
		}

		/* 返回抓取数据 */
		return json_encode(array('state' => count($list) ? 'SUCCESS' : 'ERROR', 'list' => $list),JSON_UNESCAPED_UNICODE);exit;
	}

}
