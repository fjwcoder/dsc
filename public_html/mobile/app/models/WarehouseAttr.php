<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WarehouseAttr extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'warehouse_attr';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'goods_attr_id', 'warehouse_id', 'attr_price');
	protected $guarded = array();
}

?>
