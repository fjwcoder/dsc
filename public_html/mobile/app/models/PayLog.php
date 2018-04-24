<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class PayLog extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'pay_log';
	protected $primaryKey = 'log_id';
	public $timestamps = false;
	protected $fillable = array('order_id', 'order_amount', 'order_type', 'is_paid');
	protected $guarded = array();
}

?>
