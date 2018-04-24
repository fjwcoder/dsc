<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class AuctionLog extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'auction_log';
	protected $primaryKey = 'log_id';
	public $timestamps = false;
	protected $fillable = array('act_id', 'bid_user', 'bid_price', 'bid_time');
	protected $guarded = array();
}

?>
