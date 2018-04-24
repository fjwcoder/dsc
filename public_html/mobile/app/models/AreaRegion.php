<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class AreaRegion extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'area_region';
	public $timestamps = false;
	protected $fillable = array('shipping_area_id', 'region_id', 'ru_id');
	protected $guarded = array();
}

?>
