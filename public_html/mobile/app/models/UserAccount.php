<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class UserAccount extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'user_account';
	public $timestamps = false;
	protected $fillable = array('user_id', 'admin_user', 'amount', 'add_time', 'paid_time', 'admin_note', 'user_note', 'process_type', 'payment', 'is_paid');
	protected $guarded = array();
}

?>
