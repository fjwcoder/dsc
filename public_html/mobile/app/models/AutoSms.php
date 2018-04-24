<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class AutoSms extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'auto_sms';
	protected $primaryKey = 'item_id';
	public $timestamps = false;
	protected $fillable = array('item_type', 'user_id', 'ru_id', 'order_id', 'add_time');
	protected $guarded = array();
}

?>
