<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ValueCard extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'value_card';
	protected $primaryKey = 'vid';
	public $timestamps = false;
	protected $fillable = array('tid', 'value_card_sn', 'value_card_password', 'user_id', 'vc_value', 'card_money', 'bind_time', 'end_time');
	protected $guarded = array();
}

?>
