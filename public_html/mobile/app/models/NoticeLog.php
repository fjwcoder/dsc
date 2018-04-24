<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class NoticeLog extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'notice_log';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'email', 'send_ok', 'send_type', 'send_time');
	protected $guarded = array();
}

?>
