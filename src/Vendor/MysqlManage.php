<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 321418616
 */


namespace HaoMVC\Vendor;

use think\facade\Db;

class MysqlManage
{
	protected $Array;
	protected $data;
	protected $table;
	protected $method;
	
	
    public function __construct($Array,$method='create')
    {
    	
        $this->Array     = $Array;
		$this->method     = mb_strtolower($method);
		if(is_array($Array)){
			if(count($Array)!=2){
				Error('缺少必要参数');
			}else{
				$this->CheckData();
			}
		}else{
			$this->table = $Array;
		}
        $this->initialize();
    }
	protected function initialize()
    {
    }
	
	public function Todo()
	{
		
		switch ($this->method) {
			case 'create':
				$sql = 'CREATE TABLE IF NOT EXISTS '.$this->table.$this->CheckSql();
				break;
			case 'add':
				$sql = 'alter TABLE '.$this->table.' add'.$this->CheckSql();
				break;
			case 'modify':
				$sql = 'alter TABLE '.$this->table.' modify'.$this->CheckSql();
				break;
			case 'dropcolumn':
				$sql = "alter TABLE ".$this->table." drop column ".$this->data;
				break;
			case 'deltable':
				$sql = "DROP TABLE ".$this->table;
				break;
			case 'truncate':
				$sql = "truncate  FROM ".$this->table;
				break;
			case 'clear':
				$sql = "DELETE FROM ".$this->table;
				break;
			case 'showcolumn':
				$sql = "SHOW FULL FIELDS FROM ".$this->table;
				return Db::query($sql);
				break;
			default:
				return json(['code'=>-2,'msg'=>'操作方法错误']);exit;
				break;
		}
		try {
			Db::execute($sql);
			return json(['code'=>0,'msg'=>'成功']);
		} catch (\Exception $e) {
	   	 	return json(['code'=>-2,'msg'=>$e->getMessage()]);
		}
		
		
		
	}
	protected function CheckTable()
	{
		
	}
	protected function CheckData()
	{
		foreach ($this->Array as $key => $value) {
			if(is_array($value)){
				$this->data = $value;
			}else{
				$this->table = env('database.prefix').$value;
			}
		}
	}
	protected function CheckSql()
	{
		
		$sql=' (';
		foreach ($this->data as $key => $value) {
			$sql .= $value['name'].' ';
			$sql .= $value['decimal']=='0'?$value['field'].'('.$value['length'].')':$value['field'].'('.$value['length'].','.$value['decimal'].')';
			$sql .= $value['unsigned']==1?' unsigned':'';
			$sql .= $value['not']==1?' NOT NULL':'';
			
			$sql .= $value['primary']==1?' PRIMARY KEY':'';
			
			
			$sql .=  $value['primary']==0?' DEFAULT '.$value['default']:'';
			$sql .= ' COMMENT "'.$value['comment'].'"';
			$sql .= $value['auto']==1?' AUTO_INCREMENT':'';
			if($value != end($this->data)) {
				$sql .=  ',';
			}
		}
		$sql .= ')';
		return $sql;
	}
	protected function CheckMsg()
	{
		$msg = [
				'create'=>'创建数据表',
				'add'=>'添加字段',
				'modify'=>'修改字段',
				'drop'=>'删除字段',
				'del'=>'删除表',
				];
		return $msg[$this->method];
	}
	protected function getSqlField()
	{
		return require_once __DIR__. '../config/sqlfield.php';
	}
}

