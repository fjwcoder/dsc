<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WechatUser extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_user';
	protected $primaryKey = 'uid';
	public $timestamps = false;
	protected $fillable = array('wechat_id', 'subscribe', 'openid', 'nickname', 'sex', 'city', 'country', 'province', 'language', 'headimgurl', 'subscribe_time', 'remark', 'privilege', 'unionid', 'groupid', 'ect_uid', 'bein_kefu', 'parent_id');
	protected $guarded = array();
}

?>
