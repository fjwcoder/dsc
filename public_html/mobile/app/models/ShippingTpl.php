<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ShippingTpl extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'shipping_tpl';
	protected $primaryKey = 'st_id';
	public $timestamps = false;
	protected $fillable = array('shipping_id', 'ru_id', 'print_bg', 'print_model', 'config_lable', 'shipping_print', 'update_time');
	protected $guarded = array();
}

?>
