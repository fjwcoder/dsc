<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class ValueCardRecord extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'value_card_record';
	protected $primaryKey = 'rid';
	public $timestamps = false;
	protected $fillable = array('vc_id', 'order_id', 'use_val', 'add_val', 'record_time');
	protected $guarded = array();
}

?>
