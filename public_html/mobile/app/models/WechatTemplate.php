<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WechatTemplate extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_template';
	public $timestamps = false;
	protected $fillable = array('template_id', 'code', 'content', 'template', 'title', 'add_time', 'status', 'wechat_id');
	protected $guarded = array();
}

?>
