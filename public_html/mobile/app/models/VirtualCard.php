<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class VirtualCard extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'virtual_card';
	protected $primaryKey = 'card_id';
	public $timestamps = false;
	protected $fillable = array('goods_id', 'card_sn', 'card_password', 'add_date', 'end_date', 'is_saled', 'order_sn', 'crc32');
	protected $guarded = array();
}

?>
