<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class DeliveryGoods extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'delivery_goods';
	protected $primaryKey = 'rec_id';
	public $timestamps = false;
	protected $fillable = array('delivery_id', 'goods_id', 'product_id', 'product_sn', 'goods_name', 'brand_name', 'goods_sn', 'is_real', 'extension_code', 'parent_id', 'send_number', 'goods_attr');
	protected $guarded = array();
}

?>
