<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WechatPrize extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_prize';
	public $timestamps = false;
	protected $fillable = array('wechat_id', 'openid', 'prize_name', 'issue_status', 'winner', 'dateline', 'prize_type', 'activity_type', 'wall_id');
	protected $guarded = array();
}

?>
