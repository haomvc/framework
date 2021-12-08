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
class ArticleCate extends Model
{
	public function getSpreadAttr($value,$data)
    {
        return true;
    }
	public function articles()
    {
        return $this->hasMany(Article::class);
    }
	public function article()
    {
        return $this->articles()->withoutField('content');
    }
	public function children()
    {
        return $this->hasMany(self::class,'cid')->append(['spread']);
    }
	public function hot()
    {
        return $this->article()->where('hot',1)->withLimit(10);
    }
	public function top()
    {
        return $this->article()->where('top',1)->withLimit(10);
    }
	public function news()
    {
        return $this->article()->order('create_time','desc');
    }
	public static function FindThumbList($cid,$chunk=false)
	{
		$cate = self::where('cid',$cid)->column('id');
		$art = Article::where('article_cate_id',$cid)->field('id,title,article_cate_id,thumb')->select();
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

