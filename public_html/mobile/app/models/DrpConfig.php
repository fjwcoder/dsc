<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class DrpConfig extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'drp_config';
	public $timestamps = false;
	protected $fillable = array('code', 'type', 'store_range', 'value', 'name', 'warning', 'sort_order');
	protected $guarded = array();
}

?>
