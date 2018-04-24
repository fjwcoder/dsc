<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class WechatQrcode extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'wechat_qrcode';
	public $timestamps = false;
	protected $fillable = array('type', 'expire_seconds', 'scene_id', 'username', 'function', 'ticket', 'qrcode_url', 'endtime', 'scan_num', 'wechat_id', 'status', 'sort');
	protected $guarded = array();
}

?>
