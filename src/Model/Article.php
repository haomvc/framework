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
class Article extends Model
{
//	protected $json = ['thumb'];
	protected $jsonAssoc = true;
	protected $type = ['tags'=>'serialize'];
	public function cate()
    {
        return $this->belongsTo(ArticleCate::class);
    }
	public function catename()
    {
        return $this->cate()->bind(['catename'=>'title']);
    }
	public function listThumb()
	{
		dump($this->data);
	}
	public static function FindThumbList($cid,$chunk=false)
	{
		$art = self::where('article_cate_id',$cid)->field('id,title,article_cate_id,thumb')->select();
		$newArr = [];
		foreach ($art as $key => $value) {
			for ($i=0; $i < count($value['thumb']); $i++) { 
				$newArr[] = ['id'=>$value['id'],'title'=>$value['title'],'src'=>$value['thumb'][$i]];
			}
		}
		if($chunk){
			return array_chunk($newArr, $chunk);
		}else{
			return $newArr;
		}
	}
}

