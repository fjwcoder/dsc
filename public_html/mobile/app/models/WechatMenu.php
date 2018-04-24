<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WechatMenu extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_menu';
	public $timestamps = false;
	protected $fillable = array('wechat_id', 'pid', 'name', 'type', 'key', 'url', 'sort', 'status');
	protected $guarded = array();
}

?>
