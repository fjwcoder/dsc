<?php
// by bbs.52jscn.com   禁止倒卖 一经发现停止任何服务
namespace app\models;

class GiftGardType extends \Illuminate\Database\Eloquent\Model
{
	protected $table = 'gift_gard_type';
	protected $primaryKey = 'gift_id';
	public $timestamps = false;
	protected $fillable = array('ru_id', 'gift_name', 'gift_menory', 'gift_min_menory', 'gift_start_date', 'gift_end_date', 'gift_number');
	protected $guarded = array();
}

?>
