<?php
// +----------------------------------------------------------------------
// | Created by Laotian on 2021-07-30.
// | Copyright 2021 HaoMVC. All rights reserved.
// +----------------------------------------------------------------------


namespace HaoMVC\Model;
use think\Model;


/**
 * 
 */
class Product extends Model
{
	protected $json = ['thumb','tag'];
	protected $jsonAssoc = true;
	public function cate()
    {
        return $this->belongsTo(ProductCate::class);
    }
	public function catename()
    {
        return $this->cate()->bind(['catename'=>'title']);
    }
	public function scopeTitle($query, $tag)
    {
    	$where  = [];
		foreach ($tag as $key => $value) {
			$where[] = '%' . $value . '%';
		}
    	$query->where('title', 'like', $where,'OR');
    }
	public static function ArticleLike($tag,$field='*',$limit=10)
	{
		if(empty($tag)){
			return [];
		}
		$where  = [];
		foreach ($tag as $key => $value) {
			$where[] = '%' . $value . '%';
		}
		return Article::where('title', 'like', $where,'OR')->field($field)->limit($limit)->select()->toArray();
	}
}

