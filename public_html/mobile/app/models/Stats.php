<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class Stats extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'stats';
	public $timestamps = false;
	protected $fillable = array('access_time', 'ip_address', 'visit_times', 'browser', 'system', 'language', 'area', 'referer_domain', 'referer_path', 'access_url');
	protected $guarded = array();
}

?>
