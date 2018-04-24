<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WechatMassHistory extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_mass_history';
	public $timestamps = false;
	protected $fillable = array('wechat_id', 'media_id', 'type', 'status', 'send_time', 'msg_id', 'totalcount', 'filtercount', 'sentcount', 'errorcount');
	protected $guarded = array();
}

?>
