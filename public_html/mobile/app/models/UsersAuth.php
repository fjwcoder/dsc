<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class UsersAuth extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'users_auth';
	public $timestamps = false;
	protected $fillable = array('user_id', 'user_name', 'identity_type', 'identifier', 'credential', 'verified', 'add_time', 'update_time');
	protected $guarded = array();
}

?>
