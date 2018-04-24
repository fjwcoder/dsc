<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class AdminLog extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'admin_log';
	protected $primaryKey = 'log_id';
	public $timestamps = false;
	protected $fillable = array('log_time', 'user_id', 'log_info', 'ip_address');
	protected $guarded = array();
}

?>
