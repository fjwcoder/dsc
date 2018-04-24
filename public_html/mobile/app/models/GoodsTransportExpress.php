<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class GoodsTransportExpress extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'goods_transport_express';
	public $timestamps = false;
	protected $fillable = array('tid', 'ru_id', 'admin_id', 'shipping_id', 'shipping_fee');
	protected $guarded = array();
}

?>
