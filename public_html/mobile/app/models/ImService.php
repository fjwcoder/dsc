<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ImService extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'im_service';
	protected $primaryKey = 'id';
	public $timestamps = false;
	protected $fillable = array('id', 'user_id', 'user_name', 'nick_name', 'post_desc', 'login_time', 'chat_status', 'status');
	protected $guarded = array();

	public function AdminUser()
	{
		return $this->belongsTo('app\\models\\AdminUser', 'user_id', 'user_id');
	}
}

?>
