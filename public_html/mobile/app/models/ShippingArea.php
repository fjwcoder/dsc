<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ShippingArea extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'shipping_area';
	protected $primaryKey = 'shipping_area_id';
	public $timestamps = false;
	protected $fillable = array('shipping_area_name', 'shipping_id', 'configure', 'ru_id');
	protected $guarded = array();
}

?>
