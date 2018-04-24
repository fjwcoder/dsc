<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class BonusType extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'bonus_type';
	protected $primaryKey = 'type_id';
	public $timestamps = false;
	protected $fillable = array('type_name', 'user_id', 'type_money', 'send_type', 'usebonus_type', 'min_amount', 'max_amount', 'send_start_date', 'send_end_date', 'use_start_date', 'use_end_date', 'min_goods_amount');
	protected $guarded = array();
}

?>
