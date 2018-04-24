<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class AdminMessage extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'admin_message';
	protected $primaryKey = 'message_id';
	public $timestamps = false;
	protected $fillable = array('sender_id', 'receiver_id', 'sent_time', 'read_time', 'readed', 'deleted', 'title', 'message');
	protected $guarded = array();
}

?>
