<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ShopConfig extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'shop_config';
	public $timestamps = false;
	protected $fillable = array('parent_id', 'code', 'type', 'store_range', 'store_dir', 'value', 'sort_order');
	protected $guarded = array();
}

?>
