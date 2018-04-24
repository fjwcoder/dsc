<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class Shipping extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'shipping';
	protected $primaryKey = 'shipping_id';
	public $timestamps = false;
	protected $fillable = array('shipping_code', 'shipping_name', 'shipping_desc', 'insure', 'support_cod', 'enabled', 'shipping_print', 'print_bg', 'config_lable', 'print_model', 'shipping_order');
	protected $guarded = array();
}

?>
