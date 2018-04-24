<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class AdCustom extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'ad_custom';
	protected $primaryKey = 'ad_id';
	public $timestamps = false;
	protected $fillable = array('ad_type', 'ad_name', 'add_time', 'content', 'url', 'ad_status');
	protected $guarded = array();
}

?>
