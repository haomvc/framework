<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 321418616
 */


return [

	'数值类型'=>[
		'tinyint'=>'tinyint(3)',
		'smallint'=> 'smallint(5)',
		'mediumint'=>'mediumint(8)',
		'int'=>'int(10)',
		'bigint'=>'bigint(20)',
		'float' => 'float(3,1)',
		'double'=>'double(10,5)',
		'decimal'=>'decimal(10,2)'
	],
	'字符串类型'=>[
		'char'=> 'char(255)',
		'varchar'=>'varchar(1000)',
		'tinytext'=>'tinytext',
		'text'=>'text',
		'textblob'=>'textblob',
		'mediumtext'=>'mediumtext',
		'longtext'=>'longtext',
		'longblob'=>'longblob',
		'enum'=>'enmu("男","女")',
		'set'=>'set("value1","value2", ...)'
		
	],
	'时间日期类型'=>[
		'date'=>'YYYY-MM-DD->date',
		'time'=>'hh:mm:ss->time',
		'datetime'=>'YYYY-MM-DD hh:mm:ss->datetime',
		'timestamp'=>'YYYYMMDDhhmmss->timestamp',
		'year'=>'YYYY ->year',
	],

];
