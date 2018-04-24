<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ImMessage extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'im_message';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = array('id', 'from_user_id', 'to_user_id', 'dialog_id', 'message', 'add_time', 'user_type', 'status');
	protected $guarded = array();
}

?>
