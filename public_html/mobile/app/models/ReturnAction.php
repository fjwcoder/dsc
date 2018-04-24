<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ReturnAction extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'return_action';
	protected $primaryKey = 'action_id';
	public $timestamps = false;
	protected $fillable = array('ret_id', 'action_user', 'return_status', 'refound_status', 'action_place', 'action_note', 'log_time');
	protected $guarded = array();
}

?>
