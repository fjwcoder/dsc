<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class StoreGoods extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'store_goods';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'store_id', 'ru_id', 'goods_number', 'extend_goods_number');
	protected $guarded = array();
}

?>
