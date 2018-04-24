<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WechatTemplateLog extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_template_log';
	public $timestamps = false;
	protected $fillable = array('code', 'openid', 'data', 'url', 'status');
	protected $guarded = array();
}

?>
