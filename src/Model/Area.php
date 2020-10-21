<?php
/**
 * @copyright ©2008-2020 HaoMVC开发框架
 * @author 老田
 * @link http://www.haomvc.com/
 * @QQ 1446716651
 */


namespace HaoMVC\Model;
use think\Model;

class Area extends Model
{
	public function child()
    {
        return $this->hasMany(self::class);
    }
	
}

