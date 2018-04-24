<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WechatCustomMessage extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_custom_message';
	public $timestamps = false;
	protected $fillable = array('wechat_admin_id', 'uid', 'msg', 'wechat_id', 'send_time');
	protected $guarded = array();
}

?>
