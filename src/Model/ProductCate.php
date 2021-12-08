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
class ProductCate extends Model
{
	public function getSpreadAttr($value,$data)
    {
        return true;
    }
	public function products()
    {
        return $this->hasMany(Product::class);
    }
	public function product()
    {
        return $this->products()->field('id,title,thumb,top,remarks,product_cate_id');
    }
	public function producttop()
    {
        return $this->products()->withLimit(6);
    }
	public function children()
    {
        return $this->hasMany(self::class,'cid')->append(['spread']);
    }
	public static function FindThumbList($cid,$chunk=false)
	{
		$cate = self::where('cid',$cid)->column('id');
		$art = Product::where('product_cate_id','in',$cate)->field('id,title,product_cate_id,thumb')->select();
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

