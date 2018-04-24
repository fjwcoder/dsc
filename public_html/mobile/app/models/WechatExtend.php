<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WechatExtend extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_extend';
	public $timestamps = false;
	protected $fillable = array('name', 'keywords', 'command', 'config', 'type', 'enable', 'author', 'website', 'wechat_id');
	protected $guarded = array();
}

?>
