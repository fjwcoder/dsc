<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class GoodsCat extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'goods_cat';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'cat_id');
	protected $guarded = array();
}

?>
