<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WechatMarketing extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_marketing';
	public $timestamps = false;
	protected $fillable = array('wechat_id', 'marketing_type', 'name', 'keywords', 'command', 'description', 'starttime', 'endtime', 'addtime', 'logo', 'background', 'config', 'support', 'status', 'qrcode');
	protected $guarded = array();
}

?>
