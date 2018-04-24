<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class TouchAuth extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'touch_auth';
	public $timestamps = false;
	protected $fillable = array('auth_config', 'type', 'sort', 'status');
	protected $guarded = array();
}

?>
