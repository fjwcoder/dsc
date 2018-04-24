<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class MerchantsPercent extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'merchants_percent';
	protected $primaryKey = 'percent_id';
	public $timestamps = false;
	protected $fillable = array('percent_value', 'sort_order', 'add_time');
	protected $guarded = array();
}

?>
