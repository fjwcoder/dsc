<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WechatUserTaglist extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_user_taglist';
	public $timestamps = false;
	protected $fillable = array('wechat_id', 'tag_id', 'name', 'count', 'sort');
	protected $guarded = array();
}

?>
