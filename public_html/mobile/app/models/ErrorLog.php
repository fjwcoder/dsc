<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ErrorLog extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'error_log';
	public $timestamps = false;
	protected $fillable = array('info', 'file', 'time');
	protected $guarded = array();
}

?>
