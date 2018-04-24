<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WechatPoint extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_point';
	public $timestamps = false;
	protected $fillable = array('log_id', 'openid', 'keywords', 'createtime', 'wechat_id');
	protected $guarded = array();
}

?>
